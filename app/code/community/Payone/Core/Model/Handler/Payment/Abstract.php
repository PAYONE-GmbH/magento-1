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
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_Handler_Payment_Abstract
    extends Payone_Core_Model_Handler_Abstract
    implements Payone_Core_Model_Handler_Payment_Interface
{
    /**
     * @var Payone_Core_Model_Config_Payment_Method_Interface
     */
    protected $configPaymentMethod = null;
    /**
     * @var Mage_Sales_Model_Order_Payment
     */
    protected $payment = null;
    /**
     * @var Payone_Core_Model_Service_Sales_OrderStatus
     */
    protected $serviceOrderStatus = null;

    /**
     * @var Payone_Core_Model_Service_Sales_OrderComment
     */
    protected $serviceOrderComment = null;

    /**
     * @var Payone_Core_Model_Service_Transaction_Create
     */
    protected $serviceTransactionCreate = null;

    /** @var Payone_Core_Model_Service_Transaction_Update */
    protected $serviceTransactionUpdate = null;

    /**
     * @var Payone_Api_Request_Interface
     */
    protected $request = null;

    protected function _isIframePaymentOrder($oRequest) {
        if($this->_isYapitalOrder($oRequest) || $this->_isCreditcardIframe($oRequest)) {
            return true;
        }
        return false;
    }
    
    protected function _getPaymentMethod() {
        $oOrder = Mage::getSingleton('checkout/session')->getQuote();
        $oPayment = $oOrder->getPayment();
        return $oPayment->getMethod();
    }
    
    protected function _isCreditcardIframe($oRequest) {
        if($this->_getPaymentMethod() == 'payone_creditcard_iframe') {
            return true;
        }
        return false;
    }
    
    protected function _isYapitalOrder($oRequest) {
        if($oRequest->getClearingtype() == 'wlt') {
            $oPayment = $oRequest->getPayment();
            if($oPayment->getWallettype() == 'YPL') {
                return true;
            }
        }
        return false;
    }
    
    /**
     * @param Payone_Api_Response_Interface $response
     * @return Payone_Core_Model_Handler_Payment_Abstract
     */
    public function handle(Payone_Api_Response_Interface $response)
    {
        $order = $this->getOrder();
        $paymentMethod = $this->getPaymentMethod();
        $request = $this->getRequest();

        if ($response->isError()) {
            return $this;
        }

        if ($response->isApproved()) {
            $this->sendAvsMail($response);
        } elseif ($response->isRedirect()) {
            $sRedirectUrl = $response->getRedirecturl();
            if($this->_isIframePaymentOrder($request)) {
                $oSession = Mage::getSingleton('checkout/session');
                $oSession->setPayoneIframeUrl($sRedirectUrl);
                $oSession->setPayonePaymentType($this->_getPaymentMethod());
                $sRedirectUrl = Mage::helper('payone_core/url')->getMagentoUrl('payone_core/iframe/show');
            }
            $paymentMethod->setRedirectUrl($sRedirectUrl);
        }

        $this->updatePaymentByResponse($response);

        // Set Payment Initialized
        $this->updatePaymentByOrder($order);

        if ($response instanceof Payone_Api_Response_Authorization_Abstract ||
            $response instanceof Payone_Api_Response_Authorization_Redirect) {
            // Create Transaction
            $this->getServiceTransactionCreate()->createByApiResponse($order, $response, $request);
        }
        else
        {
            $this->getServiceTransactionUpdate()->updateByApiResponse($response);
        }

        // Update Order Status
        $this->getServiceOrderStatus()->setConfigStore($this->getConfigStore());
        $this->getServiceOrderStatus()->updateByApiResponse($order, $response);

        // Add Order Comment
        $this->getServiceOrderComment()->addByApiResponse($order, $response);

        // Update Order
        $this->updateOrder($order);

        if(method_exists($response, 'getAddPaydataInstructionNotes') && $response->getAddPaydataInstructionNotes()) {
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->setPayoneBarzahlenHtml(urldecode($response->getAddPaydataInstructionNotes()));
        }
        
        // Update Customer
        $this->updateCustomerByResponse($response);

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    protected function updatePaymentByOrder(Mage_Sales_Model_Order $order)
    {
        // Set Amount Authorized
        $this->getPayment()->setAmountAuthorized($order->getTotalDue());
        $this->getPayment()->setBaseAmountAuthorized($order->getBaseTotalDue());
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    protected function updateOrder(Mage_Sales_Model_Order $order)
    {
        if ($this->getPaymentMethod() instanceof Payone_Core_Model_Payment_Method_Creditcard) {
            $order->setData('payone_payment_method_type', $this->getPayment()->getData('cc_type'));
        }
        elseif ($this->getPaymentMethod() instanceof Payone_Core_Model_Payment_Method_OnlineBankTransfer) {
            $order->setData('payone_payment_method_type',
                $this->getPayment()->getData('payone_onlinebanktransfer_type'));
        }
        elseif ($this->getPaymentMethod() instanceof Payone_Core_Model_Payment_Method_Financing) {
            $order->setData('payone_payment_method_type',
                $this->getPayment()->getData('payone_financing_type'));
        }
        elseif ($this->getPaymentMethod() instanceof Payone_Core_Model_Payment_Method_SafeInvoice) {
            $order->setData('payone_payment_method_type',
                $this->getPayment()->getData('payone_safe_invoice_type'));
        }
        elseif ($this->getPaymentMethod() instanceof Payone_Core_Model_Payment_Method_Payolution) {
            $order->setData('payone_payment_method_type',
                $this->getPayment()->getData('payone_payolution_type'));
        }
    }

    /**
     * @param Payone_Api_Response_Interface $response
     */
    protected function updatePaymentByResponse(Payone_Api_Response_Interface $response)
    {
        $payment = $this->getPayment();
        $paymentMethod = $this->getPaymentMethod();

        $payment->setLastTransId($response->getTxid());

        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_AdvancePayment
            or $paymentMethod instanceof Payone_Core_Model_Payment_Method_Invoice
            or $paymentMethod instanceof Payone_Core_Model_Payment_Method_CashOnDelivery
            or ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice and $response instanceof Payone_Api_Response_Capture_Approved)
        ) {
            /** @var $response Payone_Api_Response_Authorization_Approved */
            $payment->setPayoneClearingBankAccountholder($response->getClearingBankaccountholder());
            $payment->setPayoneClearingBankCountry($response->getClearingBankcountry());
            $payment->setPayoneClearingBankAccount($response->getClearingBankaccount());
            $payment->setPayoneClearingBankCode($response->getClearingBankcode());
            $payment->setPayoneClearingBankIban($response->getClearingBankiban());
            $payment->setPayoneClearingBankBic($response->getClearingBankbic());
            $payment->setPayoneClearingBankCity($response->getClearingBankcity());
            $payment->setPayoneClearingBankName($response->getClearingBankname());

            if($response instanceof Payone_Api_Response_Capture_Approved)
            {
                $payment->setPayoneClearingReference($response->getClearingReference());
                $payment->setPayoneClearingInstructionnote($response->getClearingInstructionnote());
                $payment->setPayoneClearingLegalnote($response->getClearingLegalnote());
                $payment->setPayoneClearingDuedate($response->getClearingDuedate());
            }
        } elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_Ratepay) {
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->unsRatePayFingerprint();
        }
        
        if($response instanceof Payone_Api_Response_Authorization_Abstract) {
            if($response->getAddPaydataClearingReference()) {
                $payment->setPayoneClearingReference($response->getAddPaydataClearingReference());
            } elseif($response->getClearingReference()) {
                $payment->setPayoneClearingReference($response->getClearingReference());
            }
        }
    }

    /**
     * @param Payone_Api_Response_Interface $response
     *
     * @return void
     */
    protected function updateCustomerByResponse(Payone_Api_Response_Interface $response)
    {
        if (!$response instanceof Payone_Api_Response_Authorization_Abstract) {
            return;
        }

        $customerId = $this->getOrder()->getCustomerId();
        if (empty($customerId)) {
            return;
        }

        $customer = $this->getFactory()->getModelCustomer();
        $customer->load($customerId);

        if (!$customer->hasData()) {
            return;
        }

        $customer->setPayoneUserId($response->getUserid());
        $customer->save();
    }

    /**
     * @param Payone_Api_Response_Authorization_Approved|Payone_Api_Response_Refund_Approved|Payone_Api_Response_Interface $response
     */
    protected function sendAvsMail(Payone_Api_Response_Interface $response)
    {
        $storeId = $this->getOrder()->getStore()->getId();

        $configMisc = $this->helperConfig()->getConfigMisc($storeId);
        $configEmailAvs = $configMisc->getEmailAvs();

        if ($response instanceof Payone_Api_Response_Refund_Approved ||
            $response instanceof Payone_Api_Response_Authorization_Approved ||
            $response instanceof Payone_Api_Response_Preauthorization_Approved
        ) {
            if ($configEmailAvs->isResultAvsInConfig($response->getProtectResultAvs())) {

                // Mailtemplates need an Varien_Object if we want to use Getter from the Object
                $responseMailObject = new Varien_Object($response->toArray());

                $helperEmail = $this->helperEmail();
                $helperEmail->setStoreId($storeId);
                $result = $helperEmail->send($configEmailAvs, array('response' => $responseMailObject));
            }
        }
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     */
    public function setPayment(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Mage_Sales_Model_Order_Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @return Payone_Core_Model_Payment_Method_Abstract
     */
    protected function getPaymentMethod()
    {
        return $this->getPayment()->getMethodInstance();
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    protected function getOrder()
    {
        return $this->getPayment()->getOrder();
    }

    /**
     * @param Payone_Core_Model_Service_Sales_OrderStatus $service
     */
    public function setServiceOrderStatus(Payone_Core_Model_Service_Sales_OrderStatus $service)
    {
        $this->serviceOrderStatus = $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_OrderStatus
     */
    public function getServiceOrderStatus()
    {
        return $this->serviceOrderStatus;
    }

    /**
     * @param Payone_Core_Model_Service_Sales_OrderComment $service
     */
    public function setServiceOrderComment(Payone_Core_Model_Service_Sales_OrderComment $service)
    {
        $this->serviceOrderComment = $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_OrderComment
     */
    public function getServiceOrderComment()
    {
        return $this->serviceOrderComment;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     */
    public function setConfigPaymentMethod(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $this->configPaymentMethod = $config;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfigPaymentMethod()
    {
        return $this->configPaymentMethod;
    }

    /**
     * @param Payone_Core_Model_Service_Transaction_Create $service
     */
    public function setServiceTransactionCreate(Payone_Core_Model_Service_Transaction_Create $service)
    {
        $this->serviceTransactionCreate = $service;
    }

    /**
     * @return Payone_Core_Model_Service_Transaction_Create
     */
    public function getServiceTransactionCreate()
    {
        return $this->serviceTransactionCreate;
    }

    /**
     * @param Payone_Api_Request_Interface $request
     */
    public function setRequest(Payone_Api_Request_Interface $request)
    {
        $this->request = $request;
    }

    /**
     * @return Payone_Api_Request_Interface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Payone_Core_Model_Service_Transaction_Update $serviceTransactionUpdate
     */
    public function setServiceTransactionUpdate($serviceTransactionUpdate)
    {
        $this->serviceTransactionUpdate = $serviceTransactionUpdate;
    }

    /**
     * @return \Payone_Core_Model_Service_Transaction_Update
     */
    public function getServiceTransactionUpdate()
    {
        return $this->serviceTransactionUpdate;
    }

}
