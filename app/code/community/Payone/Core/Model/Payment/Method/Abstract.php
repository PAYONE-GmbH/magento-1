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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_Payment_Method_Abstract
        extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'payone_abstract';

    protected $_isGateway = false;
    protected $_canAuthorize = false;
    protected $_canCapture = true;
    protected $_canCapturePartial = true;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canVoid = false;
    protected $_canUseInternal = true;
    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = false;
    protected $_isInitializeNeeded = true;
    protected $_mustTransimitInvoicingData = false;
    protected $_mustTransimitInvoicingItemTypes = false;

    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    protected $methodType = '';
    protected $redirectUrl = '';

    protected $_defaultApiResponseErrorMessage = 'There has been an error processing your payment';
    
    /**
     * @var Payone_Core_Model_Config_Interface
     */
    protected $configStore = null;
    /**
     * @var Payone_Core_Model_Config_Payment_Method_Interface
     */
    protected $config = null;

    protected $_blIpMandatory = false;
    
    /**
     * override parent method to get the user-configured title, not the one from config.xml
     *
     * @return string
     */
    public function getTitle()
    {
        if ($this->getConfig() instanceof Payone_Core_Model_Config_Payment_Method_Interface) {
            return $this->getConfig()->getName();
        }
        try {
            // order has higher priority than quote
            $order = $this->getInfoInstance()->getOrder();
            if ($order instanceof Mage_Sales_Model_Order and $order->hasData()) {
                return $this->getConfigByOrder($order)->getName();
            }
            /** @var $session Mage_Checkout_Model_Session */
            $session = Mage::getSingleton('checkout/session');
            $quote = $session->getQuote();
            if (!$quote instanceof Mage_Sales_Model_Quote or !$quote->getId()) {
                $quote = $this->getInfoInstance()->getQuote();
            }
            if ($quote instanceof Mage_Sales_Model_Quote and $quote->getId()) {
                return $this->getConfigForQuote($quote)->getName();
            }
        }
        catch (Exception $e) {
            return parent::getTitle(); // if for some reason config was not found, use parent method
        }
        // call parent method if no config available
        return parent::getTitle();
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if (is_null($quote)) {
            // No quote given, availability check is basic (e.g. method is enabled, not marked as deleted)
            $configPayment = $this->getConfigPayment();
        }
        else {
            // Quote is given, availability check is detailed (includes store, country settings, min/max quote totals, etc.)
            $configPayment = $this->helperConfig()->getConfigPaymentByQuote($quote);
        }
        $isAvailable = $configPayment->isAvailable($this->getMethodType(), $quote);

        return $this->dispatchPaymentMethodIsActive($isAvailable, $quote);


    }

    /**
     * To check billing country is allowed for the payment method
     * Is used during Magento Onepage Checkout
     *
     * @override
     *
     * @param string $country
     * @return bool
     */
    public function canUseForCountry($country)
    {
        $paymentInfo = $this->getData('info_instance');

        if (!($paymentInfo instanceof Mage_Payment_Model_Info)) {
            /**
             * @important !! store can be either an int or Mage_Core_Model_Store !!
             * @comment Config should be initialized before by calling isAvailable
             * @comment but to be sure the right config is loaded we detect it again
             */
            $store = $this->getData('store');
            $storeId = ($store instanceof Mage_Core_Model_Store) ? $store->getId() : $store;
        }
        elseif ($paymentInfo instanceof Mage_Sales_Model_Order_Payment) {
            $storeId = $paymentInfo->getOrder()->getStoreId();
        }
        elseif ($paymentInfo instanceof Mage_Sales_Model_Quote_Payment) {
            $storeId = $paymentInfo->getQuote()->getStoreId();
        }
        else {
            $storeId = null;
        }

        $configPayment = $this->getConfigPayment($storeId);
        $canUse = $configPayment->canUseForCountry($this->getMethodType(), $country);
        return $canUse;
    }

    /**
     * @param Varien_Object $payment
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function cancel(Varien_Object $payment)
    {
        $status = $payment->getOrder()->getPayoneTransactionStatus();

        if (empty($status) or $status == 'REDIRECT') {
            return $this; // Don´t send cancel to PAYONE on orders without TxStatus
        }

        if ($this->getCode() == Payone_Core_Model_System_Config_PaymentMethodCode::CREDITCARD
                or $this->getCode() == Payone_Core_Model_System_Config_PaymentMethodCode::SAFEINVOICE
        ) {
            // Capture with amount=0, to notify PAYONE that the order is complete (invoiced/cancelled all items)
            // Only works with Creditcard at the moment (15.10.2013)
            $this->helperRegistry()->registerPaymentCancel($this->getInfoInstance());
            $this->capture($payment, 0.0000);
        }


        return $this;
    }

    /**
     * Called before initalize to determine action needed
     *
     * @return string
     */
    public function getConfigPaymentAction()
    {
        /** @var $order Mage_Sales_Model_Order */
        $order = $this->getInfoInstance()->getOrder();
        $config = $this->helperConfig()->getConfigPaymentMethodByOrder($order);
        return $config->getRequestType();
    }

    /**
     * @param string $paymentAction
     * @param Varien_Object $stateObject
     * @return Payone_Core_Model_Payment_Method_Abstract
     *
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    public function initialize($paymentAction, $stateObject)
    {
        /** @var $payment Mage_Sales_Model_Order_Payment */
        $payment = $this->getInfoInstance();

        /** @var $order Mage_Sales_Model_Order */
        $order = $payment->getOrder();
        $configPayment = $this->getConfigByOrder($order);

        // Never send confirmation email, we do it during Tx-Status processing
        $order->setCanSendNewEmailFlag(false);

        // Execute Payment Initialization
        $service = $this->getFactory()->getServiceInitializePayment($configPayment);
        $service->setConfigStore($this->getConfigStore($order->getStoreId()));
        $response = $service->execute($payment);

        // @comment by default state=new and status=pending
        if ($this->getRedirectUrl() != '') {
            $stateObject->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT);
            $this->setRedirectToQuotePaymentMethod();
        }
        return $this;
    }

    /**
     * Tells Magento Checkout where to redirect after checkout.
     * @note: Onepage checkout retrieves it´s redirect url from the quote, NOT from order.
     *
     * @see Mage_Checkout_Model_Type_Onepage::saveOrder()
     *
     */
    protected function setRedirectToQuotePaymentMethod()
    {
        /** @var $quote Mage_Sales_Model_Quote */
        $quote = $this->getInfoInstance()->getOrder()->getQuote();
        if (!($quote instanceof Mage_Sales_Model_Quote)) {
            // In case quote is not on info instance, workaround:
            // Onepage checkout retrieves its payment method instance from session.
            $quote = $this->getFactory()->getSingletonCheckoutSession()->getQuote();
        }

        /** @var $paymentMethodInstance Payone_Core_Model_Payment_Method_Abstract */
        $paymentMethodInstance = $quote->getPayment()->getMethodInstance();
        // Yes, this is an object of the same type as $this, unfortunately, there are two instances during a Magento checkout
        $paymentMethodInstance->setRedirectUrl($this->getRedirectUrl());
    }

    /**
     * @param Varien_Object $payment
     * @param float $amount
     * @return Payone_Core_Model_Payment_Method_Abstract
     */
    public function capture(Varien_Object $payment, $amount)
    {
        /** @var $payment Mage_Sales_Model_Order_Payment */
        if ($this->canCapture()) {
            /** @var $order Mage_Sales_Model_Order */
            $order = $payment->getOrder();
            $config = $this->getConfigByOrder($order);
            $service = $this->getFactory()->getServicePaymentCapture($config);
            $service->setConfigStore($this->getConfigStore($order->getStoreId()));
            $service->execute($payment, $amount);
        }
        return $this;
    }

    public function refund(Varien_Object $payment, $amount)
    {
        /** @var $payment Mage_Sales_Model_Order_Payment */
        if ($this->canRefund()) {
            /** @var $order Mage_Sales_Model_Order */
            $order = $payment->getOrder();
            $config = $this->getConfigByOrder($order);
            $service = $this->getFactory()->getServicePaymentDebit($config);
            $service->setConfigStore($this->getConfigStore($order->getStoreId()));
            $service->execute($payment, $amount);
        }
        return $this;
    }

    /**
     * @param Payone_Core_Model_Config_Interface $config
     */
    public function setConfigStore(Payone_Core_Model_Config_Interface $config)
    {
        $this->configStore = $config;
    }

    /**
     * @param string $storeId
     * @return Payone_Core_Model_Config_Interface
     */
    public function getConfigStore($storeId = null)
    {
        if ($this->configStore === null) {
            $this->initConfigStore($storeId);
        }
        return $this->configStore;
    }

    /**
     * @param int $storeId
     */
    protected function initConfigStore($storeId = null)
    {
        $this->configStore = $this->helperConfig()->getConfigStore($storeId);
    }

    /**
     * @param null $storeId
     * @return Payone_Core_Model_Config_Payment
     */
    public function getConfigPayment($storeId = null)
    {
        return $this->helperConfig()->getConfigPayment($storeId);
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     */
    public function setConfig(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $this->config = $config;
    }

    /**
     * Get a payment configuration that is applicable for the order
     *
     * @param Mage_Sales_Model_Order $order
     * @return null|Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfigByOrder(Mage_Sales_Model_Order $order = null)
    {
        if (is_null($this->config)) {
            if (is_null($order)) {
                $order = $this->getInfoInstance()->getOrder();
            }
            $this->config = $this->helperConfig()->getConfigPaymentMethodByOrder($order);
        }
        return $this->config;
    }

    /**
     * Get a payment configuration that is applicable for the quote
     *
     * @param Mage_Sales_Model_Quote $quote is need to get various values like order_total
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    public function getConfigForQuote(Mage_Sales_Model_Quote $quote = null)
    {
        if (is_null($this->config)) {
            if (is_null($quote)) {
                /** @var $session Mage_Checkout_Model_Session */
                $session = Mage::getSingleton('checkout/session');
                $quote = $session->getQuote();
            }
            if (is_null($quote)) {
                $quote = $this->getInfoInstance()->getQuote();
            }
            $this->config = $this->helperConfig()->getConfigPaymentMethodForQuote($this->getMethodType(), $quote);
        }
        return $this->config;
    }

    /**
     * @param string $field
     * @param int $storeId
     * @return mixed
     */
    public function getConfigData($field, $storeId = null)
    {
        if ($field == 'sort_order') {
            try {
                $data = $this->getConfigForQuote()->getSortOrder();
            }
            catch (Payone_Core_Exception_PaymentMethodConfigNotFound $e) {
                return 0;
            }
        }
        else {
            $data = parent::getConfigData($field, $storeId);
        }
        return $data;
    }

    /**
     * Trigger Magento Standard Event, to allow changing of isAvailable() result
     *
     * @param bool $isAvailable
     * @param Mage_Sales_Model_Quote $quote
     * @return bool
     */
    protected function dispatchPaymentMethodIsActive($isAvailable, $quote)
    {
        $checkResult = new StdClass;
        $checkResult->isAvailable = $isAvailable;

        $this->dispatchEvent('payment_method_is_active', array(
                'result' => $checkResult,
                'method_instance' => $this,
                'quote' => $quote,
        ));

        return $checkResult->isAvailable;
    }

    /**
     * Wrapper for Mage::dispatchEvent()
     *
     * @param $name
     * @param array $data
     *
     * @return Mage_Core_Model_App
     */
    protected function dispatchEvent($name, array $data = array())
    {
        return Mage::dispatchEvent($name, $data);
    }

    /**
     * Some Payment methods require transmitting of invoicing data, regardless of configuration.
     *
     * @return bool
     */
    public function mustTransmitInvoicingData()
    {
        return $this->_mustTransimitInvoicingData;
    }

    /**
     * Some Payment methods require transmitting of invoicing item types.
     *
     * @return bool
     */
    public function mustTransmitInvoicingItemTypes()
    {
        return $this->_mustTransimitInvoicingItemTypes;
    }

    /**
     * This is called during Mage_Checkout_Model_Type_Onepage::saveOrder()
     *
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @note Getter is
     * @param $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return Payone_Core_Helper_Data
     */
    protected function helper()
    {
        return $this->getFactory()->helper();
    }

    /**
     * @return Payone_Core_Helper_Registry
     */
    protected function helperRegistry()
    {
        return $this->getFactory()->helperRegistry();
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function helperConfig()
    {
        return $this->getFactory()->helperConfig();
    }


    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = Mage::getModel('payone_core/factory');
        }
        return $this->factory;
    }

    /**
     * @param string $methodType
     */
    public function setMethodType($methodType)
    {
        $this->methodType = $methodType;
    }

    /**
     * @return string
     */
    public function getMethodType()
    {
        return $this->methodType;
    }
    
    public function getApiResponseErrorMessage($response)
    {
        return $this->_defaultApiResponseErrorMessage;
    }
    
    public function getIsIpMandatory()
    {
        return $this->_blIpMandatory;
    }

}