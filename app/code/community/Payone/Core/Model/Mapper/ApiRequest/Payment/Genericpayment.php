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
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment
    extends Payone_Core_Model_Mapper_ApiRequest_Payment_Abstract
{
    const EVENT_TYPE = 'genericpayment';

    /**
     * @return Payone_Api_Request_Genericpayment
     */
    public function getRequest()
    {
        return $this->getFactory()->getRequestPaymentGenericpayment();
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Payone_Api_Request_Genericpayment
     */
    public function mapFromPayment(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->init($payment);

        $request = $this->getRequest();

        $this->mapDefaultParameters($request);

//        $this->mapDefaultDebitParameters($request);
//        $business = $this->mapBusinessParameters();
//        $request->setBusiness($business);

        /** Set Invoiceing-Parameter only if enabled in Config */
//        if ($this->mustTransmitInvoiceData()) {
//            $invoicing = $this->mapInvoicingParameters();
//            $request->setInvoicing($invoicing);
//        }

        $this->dispatchEvent($this->getEventName(), array('request' => $request));
        $this->dispatchEvent($this->getEventPrefix() . '_all', array('request' => $request));
        return $request;
    }

    /**
     * @param $quote
     * @param null $workOrderId
     * @return Payone_Api_Request_Genericpayment
     */
    public function mapExpressCheckoutParameters($quote, $workOrderId = null)
    {
        $request = $this->getRequest();
        $this->mapDefaultParameters($request);
        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        if(null === $workOrderId) {
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYPAL_ECS_SET_EXPRESSCHECKOUT)
                )
            );
        } else {
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYPAL_ECS_GET_EXPRESSCHECKOUTDETAILS)
                )
            );
            $request->setWorkorderId($workOrderId);
        }

        $request->setPaydata($paydata);
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setClearingtype(Payone_Enum_ClearingType::WALLET);
        $request->setAmount($quote->getGrandTotal());
        $request->setCurrency($quote->getQuoteCurrencyCode());

        $this->checkCurrencyConversion($request, $quote);

        $request->setWallet(
            new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet(
                array(
                'wallettype' => Payone_Api_Enum_WalletType::PAYPAL_EXPRESS,
                'successurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/return'),
                'errorurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/error'),
                'backurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/cancel')
                )
            )
        );
        return $request;
    }

    /**
     * @return Payone_Api_Request_PaydirektExpressSetCheckout
     */
    public function getPaydirektExpressInitCheckoutRequest()
    {
        $request = $this->getFactory()->getRequestPaydirektExpressSetCheckout();
        $this->mapDefaultParameters($request);

        return $request;
    }

    /**
     * @return Payone_Api_Request_PaydirektExpressGetStatus
     */
    public function getPaydirektExpressGetStatusRequest()
    {
        $request = $this->getFactory()->getRequestPaydirektExpressGetStatus();
        $this->mapDefaultParameters($request);

        return $request;
    }

    /**
     * @param $amount
     * @param $sRatePayShopId
     * @param $sCurrency
     * @param $rate
     * @param null $month
     * @param $configParams
     * @param string $calculationType
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     * @throws Exception
     */
    public function ratePayCalculationRequest($amount, $sRatePayShopId, $sCurrency, $rate, $month = NULL, $configParams, $calculationType = 'calculation-by-rate')
    {
        /* @var $helper Payone_Core_Model_Mapper_Abstract */
        $helper = $this->helper();
        /* @var $request Payone_Api_Request_Genericpayment */
        $request = $this->getRequest();
        /**
         * set default
         */
        $request->setApiVersion('3.10');
        $solutionName = 'fatchip';
        $solutionVersion = $helper->getPayoneVersion();
        $integratorName = 'magento';
        $integratorVersion = $helper->getMagentoVersion();

        $request->setEncoding('UTF-8');

        $request->setMid($configParams[0]->getMid());
        $request->setPortalid($configParams[0]->getPortalId());
        $request->setMode($configParams[0]->getMode());
        $request->setKey($configParams[0]->getKey());
        $request->setIntegratorName($integratorName);
        $request->setIntegratorVersion($integratorVersion);
        $request->setSolutionName($solutionName);
        $request->setSolutionVersion($solutionVersion);

        $types = $configParams[0]->getTypes();
        /**
         * set ratePay spefific parameters
         */
        $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::RATEPAY_PROFILE)
            )
        );
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::RATEPAY_REQUEST_TYPE_CALCULATION)
            )
        );

        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'shop_id', 'data' => $sRatePayShopId)
            )
        );

        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'calculation_type', 'data' => $calculationType)
            )
        );

        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'customer_allow_credit_inquiry', 'data' => 'yes')
            )
        );

        if($calculationType == 'calculation-by-rate'){
            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'rate', 'data' => $rate)
                )
            );
        }

        if($calculationType == 'calculation-by-time'){
            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'month', 'data' => $month)
                )
            );
        }

        $request->setPaydata($payData);

        $request->setClearingtype(Payone_Enum_ClearingType::RATEPAY);
        $request->setCurrency($sCurrency);
        $request->setAmount($amount);
        if(is_array($types) && count($types) > 0){
            foreach($types as $type){
                if($type == 'RPS'){
                    $request->setFinancingType(Payone_Api_Enum_RatepayType::RPS);
                } else if($type == 'RPV') {
                    $request->setFinancingType(Payone_Api_Enum_RatepayInvoicingType::RPV);
                }
            }
        } else {
            $request->setFinancingType(Payone_Api_Enum_RatepayType::RPS);
        }

        $request->setAid($configParams[0]->getAid());

        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
        return $response;
    }


    /**
     * @param $sRatePayShopId
     * @param $sCurrency
     * @param string $sRatePayType
     * @return Payone_Api_Request_Genericpayment
     */
    public function addRatePayParameters($sRatePayShopId, $sCurrency, $sRatePayType = Payone_Api_Enum_RatepayInvoicingType::RPV)
    {
        $request = $this->getRequest();
        $this->mapDefaultParameters($request);

        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::RATEPAY_PROFILE)
            )
        );
        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'shop_id', 'data' => $sRatePayShopId)
            )
        );

        $request->setPaydata($paydata);
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setClearingtype(Payone_Enum_ClearingType::FINANCING);
        $request->setCurrency($sCurrency);
        $request->setFinancingType($sRatePayType);

        return $request;
    }
    
    /**
     * @param $date
     * @return string
     */
    public function formatBirthday($date)
    {
        if (strlen($date) > 0) {
            $date = substr($date, 0, 4) . substr($date, 5, 2) . substr($date, 8, 2);
        }

        return $date;
    }
    
    public function addPayolutionPreCheckParameters($oQuote, $aRequestParams) 
    {
        $request = $this->getRequest();
        $this->mapDefaultParameters($request);

        $oAddress = $oQuote->getBillingAddress();
        if ($oAddress->getCompany()) {
            $request->setCompany($oAddress->getCompany());
        }

        $request->setFirstname($oAddress->getFirstname());
        $request->setLastname($oAddress->getLastname());
        $request->setStreet($this->helper()->normalizeStreet($oAddress->getStreet()));
        $request->setZip($oAddress->getPostcode());
        $request->setCity($oAddress->getCity());
        $request->setCountry($oAddress->getCountry());

        $request->setAmount($oQuote->getGrandTotal());
        $request->setApiVersion('3.10');
        if(isset($aRequestParams['payone_customer_dob'])) {
            $request->setBirthday($this->formatBirthday($aRequestParams['payone_customer_dob']));
        } elseif($oQuote->getCustomerDob()) {
            $request->setBirthday($this->formatBirthday($oQuote->getCustomerDob()));
        }

        $request->setEmail($oQuote->getCustomerEmail());
        $request->setIp(Mage::helper('core/http')->getRemoteAddr());
        $request->setLanguage($this->helper()->getDefaultLanguage());

        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYOLUTION_PRE_CHECK)
            )
        );
        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'payment_type', 'data' => Payone_Api_Enum_PayolutionType::getLongType($aRequestParams['payone_payolution_type']))
            )
        );
        if(isset($aRequestParams['payone_trade_registry_number'])) {
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'b2b', 'data' => 'yes')
                )
            );
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'company_trade_registry_number', 'data' => $aRequestParams['payone_trade_registry_number'])
                )
            );
        }
        if(isset($aRequestParams['payone_vat_id'])) {
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'b2b', 'data' => 'yes')
                )
            );
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'company_uid', 'data' => $aRequestParams['payone_vat_id'])
                )
            );
        }

        if ($oAddress->getCountry() == 'NL') {
            $sTelephone = $oAddress->getTelephone();
            if (empty($sTelephone)) {
                $sTelephone = $aRequestParams['payone_customer_telephone'];
            }
            $request->setTelephonenumber($sTelephone);
        }

        $request->setPaydata($paydata);
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setCurrency($oQuote->getQuoteCurrencyCode());
        $request->setClearingtype(Payone_Enum_ClearingType::FINANCING);
        $request->setFinancingType($aRequestParams['payone_payolution_type']);
        return $request;
    }
    
    public function addPayolutionCalculationParameters($oQuote) 
    {
        $request = $this->getRequest();
        $this->mapDefaultParameters($request);

        $oAddress = $oQuote->getBillingAddress();
        $request->setCountry($oAddress->getCountry());
        $request->setLastname($oAddress->getLastname());
        $request->setAmount($oQuote->getGrandTotal());
        $request->setApiVersion('3.10');
        
        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYOLUTION_CALCULATION)
            )
        );
        $request->setPaydata($paydata);
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setCurrency($oQuote->getQuoteCurrencyCode());
        $request->setClearingtype(Payone_Enum_ClearingType::FINANCING);
        $request->setFinancingType(Payone_Api_Enum_PayolutionType::PYS);
        return $request;
    }

    /**
     * @param string $currency
     * @return \Payone_Api_Request_Genericpayment
     */
    public function requestAmazonPayGetConfiguration($currency = 'EUR')
    {
        $request = $this->getRequest();
        $this->mapDefaultParameters($request);
        $request->setApiVersion('3.10');
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setClearingtype(\Payone_Enum_ClearingType::AMAZONPAY);
        $request->setCurrency($currency);
        $request->setWallet(new \Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet([
            'wallettype' => \Payone_Api_Enum_WalletType::AMAZONPAY,
        ]));
        $request->setPaydata(new \Payone_Api_Request_Parameter_Paydata_Paydata(['items' => [
            new \Payone_Api_Request_Parameter_Paydata_DataItem([
                'key' => 'action', 'data' => \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_GETCONFIGURATION
            ]),
        ]]));
        return $request;
    }

    /**
     * @param string $workOrderId
     * @param array $data
     * @param string $currency
     * @param integer $amount
     * @return \Payone_Api_Request_Genericpayment
     */
    public function requestAmazonPayOrderReferenceDetails($workOrderId, $data = [], $currency = 'EUR', $amount = null)
    {
        $request = $this->getRequest();
        $this->mapDefaultParameters($request);
        $request->setApiVersion('3.10');
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setClearingtype(\Payone_Enum_ClearingType::AMAZONPAY);
        $request->setCurrency($currency);
        $request->setAmount($amount);
        $request->setWallet(new \Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet([
            'wallettype' => \Payone_Api_Enum_WalletType::AMAZONPAY,
        ]));
        $items = [];
        foreach ($data as $index => $value) {
            array_push($items, new \Payone_Api_Request_Parameter_Paydata_DataItem([
                'key'  => $index,
                'data' => $value,
            ]));
        }
        $request->setPaydata(new \Payone_Api_Request_Parameter_Paydata_Paydata(['items' => $items]));
        $request->setWorkorderId($workOrderId);
        return $request;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return self::EVENT_TYPE;
    }

    /**
     * @param Payone_Api_Request_Genericpayment $request
     * @param Mage_Sales_Model_Quote $quote
     */
    private function checkCurrencyConversion(Payone_Api_Request_Genericpayment $request, Mage_Sales_Model_Quote $quote)
    {
        $config = $this->getConfigPayment();
        if($config->getCurrencyConvert()) {
            $request->setCurrency($quote->getBaseCurrencyCode());
            $request->setAmount($quote->getBaseGrandTotal());
        }
    }

    /**
     * @param string $workOrderId
     * @param array $data
     * @param string $currency
     * @param integer $amount
     * @return \Payone_Api_Request_Genericpayment
     */
    public function requestAmazonPayConfirmOrderReference($workOrderId, $data = [], $currency = 'EUR', $amount = null)
    {
        $request = $this->getRequest();

        $this->mapDefaultParameters($request);
        $request->setApiVersion('3.10');
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setCurrency($currency);
        $request->setAmount($amount);

        $request->setClearingtype(\Payone_Enum_ClearingType::AMAZONPAY);
        $request->setWallet(new \Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet([
            'wallettype' => \Payone_Api_Enum_WalletType::AMAZONPAY,
            'successurl' => $data['successUrl'],
            'errorurl'   => $data['errorUrl']
        ]));
        $request->setWorkorderId($workOrderId);

        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        if (!empty($data['action'])) {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                    'key' => 'action',
                    'data' => $data['action']
            ]));
        }
        if (!empty($data['amazonReferenceId'])) {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key'  => 'amazon_reference_id',
                'data' => $data['amazonReferenceId']
            ]));
        }
        if (!empty($data['shopOrderReference'])) {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key'  => 'reference',
                'data' => $data['shopOrderReference']
            ]));
        }
        $request->setPaydata($paydata);

        return $request;
    }

    /**
     * @param string $workOrderId
     * @param array $data
     * @param string $currency
     * @param integer $amount
     * @return \Payone_Api_Request_Genericpayment
     */
    public function requestAmazonPayCancelOrderReference($workOrderId, $data = [], $currency = 'EUR', $amount = null)
    {
        $request = $this->getRequest();

        $this->mapDefaultParameters($request);
        $request->setApiVersion('3.10');
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setCurrency($currency);
        $request->setAmount($amount);

        $request->setClearingtype(\Payone_Enum_ClearingType::AMAZONPAY);
        $request->setWallet(new \Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet([
            'wallettype' => \Payone_Api_Enum_WalletType::AMAZONPAY,
            'successurl' => '',
            'errorurl'   => ''
        ]));
        $request->setWorkorderId($workOrderId);

        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        if (!empty($data['action'])) {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key' => 'action',
                'data' => $data['action']
            ]));
        }
        if (!empty($data['amazonReferenceId'])) {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key'  => 'amazon_reference_id',
                'data' => $data['amazonReferenceId']
            ]));
        }
        $request->setPaydata($paydata);

        return $request;
    }

    /**
     * @param array $data
     *
     * @return Payone_Api_Request_Genericpayment
     */
    public function requestKlarnaStartSession($data = array())
    {
        $request = $this->getRequest();

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $data['quote'];

        $this->mapDefaultParameters($request);
        $request->setApiVersion('3.10');
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setCurrency($quote->getQuoteCurrencyCode());
        $request->setAmount($quote->getGrandTotal());

        if ($data['method'] == Payone_Core_Model_System_Config_PaymentMethodType::KLARNADIRECTDEBIT) {
            $request->setClearingtype(Payone_Enum_ClearingType::KLARNADIRECTDEBIT);
            $request->setFinancingType(Payone_Api_Enum_KlarnaDirectDebitType::KDD);

        } elseif ($data['method'] == Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINSTALLMENT) {
            $request->setClearingtype(Payone_Enum_ClearingType::KLARNAINSTALLMENT);
            $request->setFinancingType(Payone_Api_Enum_KlarnaInstallmentType::KIS);
        } else {
            $request->setClearingtype(Payone_Enum_ClearingType::KLARNAINVOICING);
            $request->setFinancingType(Payone_Api_Enum_KlarnaInvoicingType::KIV);
        }

        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        if (!empty($data['action'])) {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key' => 'action',
                'data' => $data['action']
            ]));
        }


        /** @var Mage_Customer_Model_Customer $customer */
        $billingAddress = $quote->getBillingAddress();
        $request->setFirstname($billingAddress->getFirstname());
        $request->setLastname($billingAddress->getLastname());
        $request->setEmail($billingAddress->getEmail());
        $request->setStreet($billingAddress->getStreetFull());
        $request->setZip($billingAddress->getPostcode());
        $request->setCity($billingAddress->getCity());
        $request->setCountry($billingAddress->getCountry());
        $request->setTelephonenumber($billingAddress->getTelephone());

        $shippingAddress = $quote->getShippingAddress();
        if ($shippingAddress) {
            $deliveryData = new Payone_Api_Request_Parameter_Authorization_DeliveryData();

            $shippingCountry = $shippingAddress->getCountry();

            $deliveryData->setShippingFirstname($shippingAddress->getFirstname());
            $deliveryData->setShippingLastname($shippingAddress->getLastname());
            $street = $this->helper()->normalizeStreet($shippingAddress->getStreetFull());
            $deliveryData->setShippingStreet($street);
            $deliveryData->setShippingZip($shippingAddress->getPostcode());
            $deliveryData->setShippingCity($shippingAddress->getCity());
            $deliveryData->setShippingCountry($shippingCountry);
            $deliveryData->setShippingState('');
            $deliveryData->setShippingAddressaddition('');
            if (empty($shippingAddress->getCompany())) {
                $deliveryData->setShippingCompany('');
            } else {
                $deliveryData->setShippingCompany($shippingAddress->getCompany());
            }

            // US, CA, CN, JP, MX, BR, AR, ID, TH, IN always need shipping_state paramters
            if ($shippingCountry == 'US' or $shippingCountry == 'CA' or $shippingCountry == 'CN' or $shippingCountry == 'JP' or $shippingCountry == 'MX' or
                $shippingCountry == 'BR' or $shippingCountry == 'AR' or $shippingCountry == 'ID' or $shippingCountry == 'TH' or $shippingCountry == 'IN') {
                $regionCode = $shippingAddress->getRegionCode();
                if (empty($regionCode)) {
                    $regionCode = $shippingAddress->getRegion();
                }

                $deliveryData->setShippingState($regionCode);
            }
            $request->setDeliveryData($deliveryData);

            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key' => 'shipping_email',
                'data' => $quote->getCustomerEmail()
            ]));
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key' => 'shipping_title',
                'data' => ''
            ]));
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key' => 'shipping_telephonenumber',
                'data' => !empty($shippingAddress->getTelephone()) ? $shippingAddress->getTelephone() : $billingAddress->getTelephone()
            ]));
        }

        $request->setPaydata($paydata);


        $invoicing = new Payone_Api_Request_Parameter_Invoicing_Transaction();
        $this->_initQuoteItems($invoicing, $quote);
        $this->_initsetShippingItem($invoicing, $quote);
        $this->_initDiscountItem($invoicing, $quote);
        $request->setInvoicing($invoicing);

        return $request;
    }

    /**
     * @param Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing
     * @param Mage_Sales_Model_Quote $quote
     */
    protected function _initQuoteItems(
        Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing,
        Mage_Sales_Model_Quote $quote
    ) {
        foreach ($quote->getItemsCollection() as $key => $itemData) {
            /** @var $itemData Mage_Sales_Model_Quote_Item */
            $number = $itemData->getQty();
            if ($number <= 0 || $itemData->getParentItemId()) {
                continue; // Do not map items with zero quantity
            }

            $params['it'] = Payone_Api_Enum_InvoicingItemType::GOODS;
            $params['id'] = $itemData->getSku();
            $params['pr'] = round($this->_convertItemPrice($itemData) * 100, 2);
            $params['no'] = $number;
            $params['de'] = $itemData->getName();
            $params['va'] = round($itemData->getTaxPercent() * 100, 2);

            $item = new Payone_Api_Request_Parameter_Invoicing_Item();
            $item->init($params);
            $invoicing->addItem($item);
        }
    }

    /**
     * @param Mage_Sales_Model_Quote_Item $itemData
     * @return float
     */
    protected function _convertItemPrice(Mage_Sales_Model_Quote_Item $itemData)
    {
        // If tax is applied after discount, the item hold the tax compensation for that discount
        // we have then to substract it from the item price
        $dTC = $itemData->getDiscountTaxCompensation();
        if ($this->configPayment->getCurrencyConvert()) {
            return $itemData->getBasePriceInclTax() - $dTC;
        }

        return $itemData->getPriceInclTax() - $dTC;
    }

    /**
     * @param Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing
     * @param Mage_Sales_Model_Quote $quote
     */
    protected function _initsetShippingItem(
        Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing,
        Mage_Sales_Model_Quote $quote
    ) {
        $shippingAmount = $this->_convertShippingAmount($quote->getShippingAddress());
        if ($shippingAmount != 0) {
            $configMiscShipping = $this->getHelperConfig()->getConfigMisc($quote->getStoreId())->getShippingCosts();
            $sku = $configMiscShipping->getSku();
            if (!empty($sku)) {
                $sku = $this->getFactory()->helper()->__(self::DEFAULT_SHIPPING_SKU);
            }

            $shippingVatRatio = $quote->getShippingAddress()->getShippingTaxAmount()
                / $quote->getShippingAddress()->getShippingAmount();
            if (is_nan($shippingVatRatio) || !is_numeric($shippingVatRatio)) {
                $shippingVatRatio = 0;
            }

            $params['it'] = Payone_Api_Enum_InvoicingItemType::SHIPMENT;
            $params['id'] = $sku;
            $params['pr'] = round($this->_convertShippingAmount($quote->getShippingAddress()) * 100, 2);
            $params['no'] = 1;
            $params['de'] = 'Shipping Costs';
            $params['va'] = round($shippingVatRatio * 100, 2);

            $item = new Payone_Api_Request_Parameter_Invoicing_Item();
            $item->init($params);
            $invoicing->addItem($item);
        }
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $shippingAddress
     * @return float
     */
    protected function _convertShippingAmount(Mage_Sales_Model_Quote_Address $shippingAddress)
    {
        if ($this->configPayment->getCurrencyConvert()) {
            return $shippingAddress->getBaseShippingInclTax();
        }

        return $shippingAddress->getShippingInclTax();
    }

    /**
     * @param Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing
     * @param Mage_Sales_Model_Quote $quote
     */
    protected function _initDiscountItem(
        Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing,
        Mage_Sales_Model_Quote $quote
    ) {
        $discountAmount = $this->_convertDiscountAmount($quote);
        if ($discountAmount != 0) {
            $configMiscDiscount = $this->getHelperConfig()->getConfigMisc()->getDiscount();
            $sku = $configMiscDiscount->getSku();
            $description = $configMiscDiscount->getDescription();
            if (empty($sku)) {
                $sku = $this->getFactory()->helper()->__(self::DEFAULT_DISCOUNT_SKU);
            }

            if (empty($description)) {
                $description = $this->getFactory()->helper()->__(self::DEFAULT_DISCOUNT_SKU);
            }

            $params['it'] = Payone_Api_Enum_InvoicingItemType::VOUCHER;
            $params['id'] = $sku;
            $params['pr'] = $discountAmount;
            $params['no'] = 1;
            $params['de'] = $description;
            $params['va'] = 0;

            $item = new Payone_Api_Request_Parameter_Invoicing_Item();
            $item->init($params);

            $invoicing->addItem($item);
        }
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return float
     */
    protected function _convertDiscountAmount(Mage_Sales_Model_Quote $quote)
    {
        if ($this->configPayment->getCurrencyConvert()) {
            return $quote->getShippingAddress()->getBaseDiscountAmount();
        }

        return $quote->getShippingAddress()->getDiscountAmount();
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function getHelperConfig()
    {
        return $this->getFactory()->helperConfig();
    }
}
