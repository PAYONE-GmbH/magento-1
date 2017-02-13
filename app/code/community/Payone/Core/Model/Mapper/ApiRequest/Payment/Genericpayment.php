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
                    $request->setFinancingType(Payone_Api_Enum_RatepayType::RPV);
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
     * @return Payone_Api_Request_Genericpayment
     */
    public function addRatePayParameters($sRatePayShopId, $sCurrency) 
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
        $request->setFinancingType(Payone_Api_Enum_RatepayType::RPV);

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
     * @return string
     */
    public function getEventType()
    {
        return self::EVENT_TYPE;
    }
}