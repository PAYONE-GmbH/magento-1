<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPL 3)
 * that is bundled with this package in the file LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Payone_Core to newer
 * versions in the future. If you wish to customize Payone_Core for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Service_Amazon_Pay_Checkout
{
    /**
     * @var \Mage_Checkout_Model_Session|null
     */
    protected $_checkoutSession = null;

    /**
     * @var \Mage_Customer_Model_Session|null
     */
    protected $_customerSession = null;

    /**
     * @var \Payone_Core_Model_Config_Payment_Method|null
     */
    protected $_config = null;

    /**
     * @var \Mage_Sales_Model_Quote|null
     */
    protected $_quote = null;

    /**
     * @var string|null
     */
    protected $_workOrderId = null;

    /**
     * @var \Payone_Core_Model_Factory|null
     */
    protected $factory = null;

    /**
     * @param array $params
     * @throws \Exception
     */
    public function __construct($params = [])
    {
        if (isset($params['quote']) && $params['quote'] instanceof \Mage_Sales_Model_Quote) {
            $this->_quote = $params['quote'];
        } else {
            throw new \Exception('Quote object is required.');
        }
        if (isset($params['config']) && $params['config'] instanceof \Payone_Core_Model_Config_Payment_Method) {
            $this->_config = $params['config'];
        } else {
            throw new \Exception('Configuration object is required.');
        }
        $this->_checkoutSession = Mage::getSingleton('checkout/session');
        $this->_customerSession = Mage::getSingleton('customer/session');
    }

    /**
     * @param string|null $fromSession
     * @return string
     */
    public function initWorkOrder($fromSession = null)
    {
        if (!empty($fromSession)) {
            $this->_workOrderId = $fromSession;
        }
        if (!empty($this->_workOrderId)) {
            return $this->_workOrderId;
        }
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->_config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayGetConfiguration($this->_quote->getQuoteCurrencyCode());
        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);

        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok) {
            $this->_workOrderId = $response->getWorkorderId();
        } else {
            Mage::throwException(Mage::helper('payone_core')->__('Unable to initialize PAYONE Amazon Checkout.'));
        }

        return $this->_workOrderId;
    }

    /**
     * @param array $params
     * @return array
     */
    public function selectAddress($params)
    {
        $data = [];
        $action = \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_GETORDERREFERENCEDETAILS;
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->_config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayOrderReferenceDetails(
            $this->_workOrderId,
            [
                'action'               => $action,
                'amazon_reference_id'  => $params['amazonOrderReferenceId'],
                'amazon_address_token' => $params['addressConsentToken'],
            ],
            $this->_quote->getQuoteCurrencyCode()
        );
        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok) {
            $data = $response->getPayDataArray();
        } else {
            Mage::throwException(
                Mage::helper('payone_core')->__('Unable to proceed with PAYONE Amazon Checkout.')
            );
        }
        $paymentMethodCode = \Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY;
        $this->_quote->getPayment()->importData([
            'method'                          => $paymentMethodCode,
            'payone_config_payment_method_id' => $this->_config->getId(),
            'checks'                          => [],
        ]);
        $this->fillAddressFields('shipping', $this->_quote->getShippingAddress(), $data)
            ->setSameAsBilling(false)
            ->setCollectShippingRates(true)
            ->setData('should_ignore_validation', true)
            ->setData('payment_method', $paymentMethodCode);
        $this->_quote->setTotalsCollectedFlag(false);
        $coupon = $this->_checkoutSession->getData('cart_coupon_code');
        if (!empty($coupon)) {
            $this->_quote->setCouponCode($coupon);
        }
        $baseGrandTotal = $this->_quote
            ->collectTotals()
            ->getBaseGrandTotal();
        $shippingRates = $this->_quote
            ->getShippingAddress()
            ->collectShippingRates()
            ->getGroupedAllShippingRates();
        $this->_quote->save();
        if (empty($shippingRates)) {
            Mage::throwException(
                Mage::helper('payone_core')->__('Unfortunately shipping to this destination is not available.')
            );
        }
        foreach ($shippingRates as $carrier => $methods) {
            foreach ($methods as $index => $method) {
                /** @var \Mage_Sales_Model_Quote_Address_Rate $method */
                $shippingRates[$carrier][$index] = $method->getData();
            }
        }
        /** @var \Payone_Core_AmazonPayController $controller */
        $controller = $params['controller'];
        $layout = $controller->getLayout();
        $update = $layout->getUpdate();
        $update->load('checkout_onepage_shippingmethod');
        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();

        return [
            'successful'          => true,
            'quoteBaseGrandTotal' => $baseGrandTotal,
            'shippingRates'       => $shippingRates,
            'shippingRatesHtml'   => $output,
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function selectMethod($params)
    {
        $this->_quote->getShippingAddress()->setShippingMethod($params['shippingMethodCode']);
        $this->_quote->setTotalsCollectedFlag(false)->collectTotals()->save();
        $action = \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_SETORDERREFERENCEDETAILS;
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->_config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayOrderReferenceDetails(
            $this->_workOrderId,
            [
                'action'               => $action,
                'amazon_reference_id'  => $params['amazonOrderReferenceId'],
                'amazon_address_token' => $params['addressConsentToken'],
            ],
            $this->_quote->getQuoteCurrencyCode(),
            $this->_quote->getGrandTotal()
        );
        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok !== true) {
            Mage::throwException(
                Mage::helper('payone_core')->__('Unable to proceed with PAYONE Amazon Checkout.')
            );
        }

        return [
            'successful'   => true,
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function selectWallet($params)
    {
        $data = [];
        $action = \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_GETORDERREFERENCEDETAILS;
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->_config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayOrderReferenceDetails(
            $this->_workOrderId,
            [
                'action'               => $action,
                'amazon_reference_id'  => $params['amazonOrderReferenceId'],
                'amazon_address_token' => $params['addressConsentToken'],
            ],
            $this->_quote->getQuoteCurrencyCode()
        );
        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok) {
            $data = $response->getPayDataArray();
        } else {
            Mage::throwException(
                Mage::helper('payone_core')->__('Unable to proceed with PAYONE Amazon Checkout.')
            );
        }

        return [
            'successful'   => true,
        ];
    }

    /**
     * @return \Payone_Core_Model_Factory
     */
    private function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = Mage::getModel('payone_core/factory');
        }

        return $this->factory;
    }

    /**
     * @param string                           $type
     * @param \Mage_Sales_Model_Quote_Address  $address
     * @param array                            $data
     * @return \Mage_Sales_Model_Quote_Address
     */
    private function fillAddressFields($type, $address, $data)
    {
        $mapping = [
            'email'           => 'email',
            'zip'             => 'postcode',
            'country'         => 'country_id',
            'state'           => 'region',
            'city'            => 'city',
            'street'          => 'street',
            'firstname'       => 'firstname',
            'lastname'        => 'lastname',
            'telephonenumber' => 'telephone',
        ];
        foreach ($data as $key => $value) {
            $key = array_key_exists($key, $mapping) ?
                $key : str_replace("{$type}_", "", $key);
            if (array_key_exists($key, $mapping)) {
                $address->setData($mapping[$key], $value);
            }
        }

        return $address;
    }
}
