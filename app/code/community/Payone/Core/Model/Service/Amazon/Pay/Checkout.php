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
    protected $checkoutSession = null;

    /**
     * @var \Mage_Customer_Model_Session|null
     */
    protected $customerSession = null;

    /**
     * @var \Payone_Core_Model_Config_Payment_Method|null
     */
    protected $config = null;

    /**
     * @var \Mage_Sales_Model_Quote|null
     */
    protected $quote = null;

    /**
     * @var string|null
     */
    protected $workOrderId = null;

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
            $this->quote = $params['quote'];
        } else {
            throw new \Exception('Quote object is required.');
        }
        if (isset($params['config']) && $params['config'] instanceof \Payone_Core_Model_Config_Payment_Method) {
            $this->config = $params['config'];
        } else {
            throw new \Exception('Configuration object is required.');
        }
        $this->checkoutSession = Mage::getSingleton('checkout/session');
        $this->customerSession = Mage::getSingleton('customer/session');
    }

    /**
     * @param string|null $fromSession
     * @return string
     */
    public function initWorkOrder($fromSession = null)
    {
        if (!empty($fromSession)) {
            $this->workOrderId = $fromSession;
        }
        if (!empty($this->workOrderId)) {
            return $this->workOrderId;
        }
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayGetConfiguration($this->quote->getQuoteCurrencyCode());
        $this->checkCurrencyConversion($request);

        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);

        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok) {
            $this->workOrderId = $response->getWorkorderId();
        } else {
            Mage::throwException(Mage::helper('payone_core')->__('Unable to initialize PAYONE Amazon Checkout.'));
        }

        return $this->workOrderId;
    }

    /**
     * @param array $params
     * @return array
     */
    public function confirmSelection($params)
    {
        $data = [];

        $amazonReferenceId = $params['amazonOrderReferenceId'];
        $addressConsentToken = $params['addressConsentToken'];

        $response = $this->requestGetOrderReferenceDetails($amazonReferenceId, $addressConsentToken);

        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok) {
            $data = $response->getPayDataArray();
        } else {
            Mage::throwException(
                Mage::helper('payone_core')->__('Unable to proceed with PAYONE Amazon Checkout.')
            );
        }
        $paymentMethodCode = \Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY;

        $this->fillAddressFields('shipping', $this->quote->getShippingAddress(), $data)
            ->setSameAsBilling(false)
            ->setCollectShippingRates(true)
            ->setData('should_ignore_validation', true)
            ->setData('payment_method', $paymentMethodCode);
        $this->fillAddressFields('billing', $this->quote->getBillingAddress(), $data)
            ->setSameAsBilling(false)
            ->setData('should_ignore_validation', true)
            ->setData('payment_method', $paymentMethodCode);

        $this->quote->getPayment()->importData([
            'method'                          => $paymentMethodCode,
            'payone_config_payment_method_id' => $this->config->getId(),
            'checks'                          => [],
        ]);

        $this->quote->setTotalsCollectedFlag(false);
        $coupon = $this->checkoutSession->getData('cart_coupon_code');
        if (!empty($coupon)) {
            $this->quote->setCouponCode($coupon);
        }
        $baseGrandTotal = $this->quote
            ->collectTotals()
            ->getBaseGrandTotal();
        $shippingRates = $this->quote
            ->getShippingAddress()
            ->collectShippingRates()
            ->getGroupedAllShippingRates();
        $this->quote->save();
        if (empty($shippingRates) || !$this->isCountryAllowed($this->quote->getShippingAddress()->getCountry())) {
            Mage::throwException(
                Mage::helper('payone_core')->__('Shipping to the selected address is not available.')
            );
        }
        $shippingRatesCount = 0;
        foreach ($shippingRates as $carrier => $methods) {
            foreach ($methods as $index => $method) {
                /** @var \Mage_Sales_Model_Quote_Address_Rate $method */
                $shippingRates[$carrier][$index] = $method->getData();
                $shippingRatesCount++;
            }
        }
        /** @var \Payone_Core_AmazonPayController $controller */
        $controller = $params['controller'];
        $layout = $controller->getLayout();
        $update = $layout->getUpdate()
            ->load('checkout_onepage_shippingmethod');
        $layout->generateXml()->generateBlocks();
        $shippingRatesHtml = $layout->getOutput();
        $update->resetHandles()->setCacheId(0)
            ->load('checkout_onepage_review');
        $layout->removeOutputBlock('checkout_review_submit');
        $layout->generateXml()->generateBlocks();
        $orderReviewHtml = $layout->getOutput();
        
        //convert the float value to a formatted string to avoind float rounding issues
        $quoteGrandTotal = number_format($this->quote->getGrandTotal(), 4, '.', '');
        
        $this->checkoutSession->setPayoneGenericpaymentGrandTotal($quoteGrandTotal); // MAGE-374: Force the total to be stored in session for further check
        if ($shippingRatesCount === 1) {
            $params['shippingMethodCode'] = array_values($shippingRates)[0][0]['code'];
            if ($this->quote->getShippingAddress()->getShippingMethod() !== $params['shippingMethodCode']) {
                $orderReviewHtml = $this->chooseMethod($params)['orderReviewHtml'];
            }
        }

        return [
            'successful'          => true,
            'quoteBaseGrandTotal' => $baseGrandTotal,
            'shippingRates'       => $shippingRates,
            'shippingRatesHtml'   => $shippingRatesHtml,
            'orderReviewHtml'     => $orderReviewHtml,
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function chooseMethod($params)
    {
        if (empty($params['shippingMethodCode'])) {
            Mage::throwException(
                Mage::helper('payone_core')->__('Please select a shipping method.')
            );
        }
        $this->quote->getShippingAddress()->setShippingMethod($params['shippingMethodCode']);
        $this->quote->setTotalsCollectedFlag(false)->collectTotals()->save();

        $amazonReferenceId = $params['amazonOrderReferenceId'];
        $addressConsentToken = $params['addressConsentToken'];

        $response = $this->requestSetOrderReferenceDetails($amazonReferenceId, $addressConsentToken);
        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok !== true) {
            Mage::throwException(
                Mage::helper('payone_core')->__('Unable to proceed with PAYONE Amazon Checkout.')
            );
        }

        /** @var \Payone_Core_AmazonPayController $controller */
        $controller = $params['controller'];
        $layout = $controller->getLayout();
        $layout->getUpdate()->load('checkout_onepage_review');
        $layout->removeOutputBlock('checkout_review_submit');
        $layout->generateXml()->generateBlocks();
        $orderReviewHtml = $layout->getOutput();

        return [
            'successful'      => true,
            'orderReviewHtml' => $orderReviewHtml,
        ];
    }

    /**
     * Unset session variable, add error-message to session and redirect back to the cart
     *
     * @param  \Payone_Core_Model_Session $oSession
     * @param  string $sTest
     * @return array
     */
    protected function cancelAmazonPayment($oSession, $sTest)
    {
        $oSession->unsetData('work_order_id');
        $oSession->unsetData('amazon_add_paydata');
        $this->checkoutSession->addError(Mage::helper('payone_core')->__($sTest));
        return [
            'successful'  => true,
            'shouldLogout' => true,
            'redirectUrl' => Mage::getUrl('checkout/cart/index'),
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function placeOrder($params)
    {
        /** @var \Mage_Checkout_Helper_Data $checkoutHelper */
        $checkoutHelper = Mage::helper('checkout');
        $requiredAgreements = $checkoutHelper->getRequiredAgreementIds();
        if ($requiredAgreements) {
            $postedAgreements = array_keys(isset($params['agreement']) ? $params['agreement'] : []);
            $diff = array_diff($requiredAgreements, $postedAgreements);
            if ($diff) {
                $agreementsErrorMessage = 'RequiredAgreementsNotAccepted';
                Mage::throwException($agreementsErrorMessage);
            }
        }

        $amazonReferenceId = $params['amazonOrderReferenceId'];
        $addressConsentToken = $params['addressConsentToken'];
        /** @var \Payone_Core_Model_Session $session */
        $session = Mage::getSingleton('payone_core/session');
        if (empty($this->quote->getReservedOrderId())) {
            $this->quote->reserveOrderId()->save();
            $session->setData('amazon_shop_order_reference', $this->quote->getReservedOrderId());
        }
        $session->setData('amazon_add_paydata', [
            'amazon_reference_id'  => $amazonReferenceId,
            'amazon_address_token' => $addressConsentToken,
        ]);
        $session->setData('amazon_reference_id', $amazonReferenceId);

        // Play this request again to update the totals
        $this->requestSetOrderReferenceDetails($amazonReferenceId, $addressConsentToken);

        $successUrl = Mage::getUrl('payone_core/amazonpay/confirmOrderReferenceSuccess', []);
        $errorUrl = Mage::getUrl('payone_core/amazonpay/confirmOrderReferenceError', []);
        $response = $this->requestConfirmOrderReference($amazonReferenceId, $successUrl, $errorUrl);

        $result = ($response instanceof \Payone_Api_Response_Genericpayment_Ok !== true)
            ? 'ERROR'
            : $response->getStatus();

        return array(
            'successful'  => true,
            'result' => $result,
            'shouldLogout' => true,
            'failureRedirectUrl' => $errorUrl
        );
    }

    /**
     * @return array
     * @throws Exception
     */
    public function finalizeOrder()
    {
        $this->quote->getBillingAddress()
            ->setData('should_ignore_validation', true);
        $this->quote->getShippingAddress()
            ->setData('should_ignore_validation', true);
        $this->quote->collectTotals()->save();
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerId = $customer->getId();
            $customerEmail = $customer->getEmail();
            $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            $this->quote->setCustomerId($customerId)
                ->setCustomerEmail($customerEmail)
                ->setCustomerIsGuest(false)
                ->setCustomerGroupId($customerGroupId);
        } else {
            $this->quote->setCustomerId(null)
                ->setCustomerFirstname($this->quote->getBillingAddress()->getFirstname())
                ->setCustomerLastname($this->quote->getBillingAddress()->getLastname())
                ->setCustomerEmail($this->quote->getBillingAddress()->getEmail())
                ->setCustomerIsGuest(true)
                ->setCustomerGroupId(\Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
        }
        /** @var \Payone_Core_Model_Session $session */
        $session = Mage::getSingleton('payone_core/session');
        $amazonOrderReferenceId = $session->getData('amazon_reference_id');
        
        //compare the values with the same string format to avoid strange float rounding issues
        $quoteGrandTotal    = number_format(
            $this->quote->getGrandTotal(),
            4,
            '.',
            ''
        );

        $sessionGrandTotal  = number_format(
            $this->checkoutSession->getPayoneGenericpaymentGrandTotal(),
            4,
            '.',
            ''
        );
        
        //check the difference between the values
        //calculate the difference in cents to avoid rounding issues again
        //
        // => using floats is evil try that:

        //php > echo abs((float) '244.68' - (float) '244.67');
        // => output: 0.010000000000019
        $difference = number_format(abs((float) $quoteGrandTotal - (float) $sessionGrandTotal), 2) * 100;

        //@todo: add a feature toggle to the config for the 1 cent rounding tolerance
        //if the values are different and the difference is more than one cent (rounding issue) => cancel the payment
        if ($quoteGrandTotal != $sessionGrandTotal && $difference > 1) {
            // The basket was changed - abort current checkout
            $text = 'Sorry, your transaction with Amazon Pay was not successful. Please try again.';
            return $this->cancelAmazonPayment($session, $text);
        }

        try {
            if (!empty($session->getData('amazon_shop_order_reference'))) {
                $this->quote->setReservedOrderId($session->getData('amazon_shop_order_reference'));
            }

            /** @var \Mage_Sales_Model_Service_Quote $service */
            $service = Mage::getModel('sales/service_quote', $this->quote);
            $service->submitAll();
        } catch (\Exception $e) {
            if (in_array($e->getCode(), [981, 985, 986])) { // send to widgets
                $session->setData('amazon_lock_order', true);
                $session->setData('amazon_reference_id', $amazonOrderReferenceId);
                $session->setData('amazon_shop_order_reference', $this->quote->getReservedOrderId());
                $session->unsetData('amazon_add_paydata');
            } else { // logout and send to basket
                // Transaction cannot be completed by Amazon
                // and the order reference object was closed
                $text = 'Sorry, your transaction with Amazon Pay was not successful. Please choose another payment method.';
                $session->unsetData('amazon_shop_order_reference');
                return $this->cancelAmazonPayment($session, $text);
            }
            throw $e;
        }
        $session->unsetData('amazon_add_paydata');
        $session->unsetData('amazon_shop_order_reference');
        $this->checkoutSession->setData('last_quote_id', $this->quote->getId());
        $this->checkoutSession->setData('last_success_quote_id', $this->quote->getId());
        $this->checkoutSession->clearHelperData();
        $order = $service->getOrder();
        if ($order) {
            Mage::dispatchEvent(
                'checkout_type_onepage_save_order_after',
                ['order' => $order, 'quote' => $this->quote]
            );
            Mage::dispatchEvent(
                'sales_quote_payment_amazon_pay_place_order_after',
                ['quote' => $this->quote]
            );
            if ($order->getCanSendNewEmailFlag()) {
                try {
                    $order->queueNewOrderEmail();
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            }
            // add order information to the session
            $this->checkoutSession->setData('last_order_id', $order->getId());
            $this->checkoutSession->setData('last_real_order_id', $order->getIncrementId());
            // as well a billing agreement can be created
            $agreement = $order->getPayment()->getBillingAgreement();
            if ($agreement) {
                $this->checkoutSession->setData('last_billing_agreement_id', $agreement->getId());
            }
        }
        Mage::dispatchEvent(
            'checkout_submit_all_after',
            ['order' => $order, 'quote' => $this->quote, 'recurring_profiles' => []]
        );
        $session->unsetData('work_order_id');

        return [
            'successful'  => true,
            'redirectUrl' => Mage::getUrl('payone_core/checkout_onepage_payment/success'),
        ];
    }

    /**
     * @param string $amazonOrderReferenceId
     * @param string $addressConsentToken
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     */
    protected function requestGetOrderReferenceDetails($amazonOrderReferenceId, $addressConsentToken)
    {
        $action = \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_GETORDERREFERENCEDETAILS;
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayOrderReferenceDetails(
            $this->workOrderId,
            [
                'action'               => $action,
                'amazon_reference_id'  => $amazonOrderReferenceId,
                'amazon_address_token' => $addressConsentToken,
            ],
            $this->quote->getQuoteCurrencyCode()
        );
        $this->checkCurrencyConversion($request);

        return $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
    }

    /**
     * @param string $amazonOrderReferenceId
     * @param string $addressConsentToken
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     */
    protected function requestSetOrderReferenceDetails($amazonOrderReferenceId, $addressConsentToken)
    {
        $action = \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_SETORDERREFERENCEDETAILS;
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayOrderReferenceDetails(
            $this->workOrderId,
            [
                'action'               => $action,
                'amazon_reference_id'  => $amazonOrderReferenceId,
                'amazon_address_token' => $addressConsentToken,
                'storename'            => Mage::app()->getStore()->getGroup()->getName(),
            ],
            $this->quote->getQuoteCurrencyCode(),
            $this->quote->getGrandTotal()
        );
        $this->checkCurrencyConversion($request);
        
        $quoteGrandTotal    = number_format($this->quote->getGrandTotal(), 4, '.', '');
        $this->checkoutSession->setPayoneGenericpaymentGrandTotal($quoteGrandTotal);

        return $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
    }

    /**
     * @param string $amazonReferenceId
     * @param string $successUrl
     * @param string $errorUrl
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     */
    protected function requestConfirmOrderReference($amazonReferenceId, $successUrl, $errorUrl)
    {
        $params = array(
            'action' => \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_CONFIRMORDERREFERENCE,
            'amazonReferenceId'   => $amazonReferenceId,
            'shopOrderReference' => $this->quote->getReservedOrderId(),
            'successUrl' => $successUrl,
            'errorUrl' => $errorUrl
        );

        $service = $this->getFactory()->getServicePaymentGenericpayment($this->config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayConfirmOrderReference(
            $this->workOrderId,
            $params,
            $this->quote->getQuoteCurrencyCode(),
            $this->quote->getGrandTotal()
        );
        $this->checkCurrencyConversion($request);

        return $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
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
            'firstname'       => 'firstname',
            'lastname'        => 'lastname',
            'email'           => 'email',
            'telephonenumber' => 'telephone',
            'company'         => 'company',
            'street'          => 'street',
            'zip'             => 'postcode',
            'city'            => 'city',
            'state'           => 'region',
            'country'         => 'country_id',
        ];
        foreach ($data as $key => $value) {
            $key = array_key_exists($key, $mapping) ?
                $key : str_replace("{$type}_", "", $key);
            if (array_key_exists($key, $mapping) && !empty($value)) {
                $address->setData($mapping[$key], $value);
            }
        }

        return $address;
    }

    /**
     * @param $country
     * @return bool
     */
    private function isCountryAllowed($country)
    {
        return in_array(strtoupper($country), explode(',', Mage::getStoreConfig('general/country/allow')));
    }

    /**
     * @param Payone_Api_Request_Genericpayment $request
     */
    private function checkCurrencyConversion(Payone_Api_Request_Genericpayment $request)
    {
        if($this->config->getCurrencyConvert()) {
            $request->setCurrency($this->quote->getBaseCurrencyCode());
            $request->setAmount($this->quote->getBaseGrandTotal());
        }
    }
}
