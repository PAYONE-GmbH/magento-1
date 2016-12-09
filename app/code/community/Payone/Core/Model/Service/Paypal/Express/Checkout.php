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
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

class Payone_Core_Model_Service_Paypal_Express_Checkout
{
    const PAYONE_EXPRESS_CHECKOUT_WORKORDERID = 'payone_express_checkout_workorderid';
    const PAYMENT_INFO_TRANSPORT_SHIPPING_OVERRIDEN = 'paypal_express_checkout_shipping_overriden';
    const PAYMENT_INFO_TRANSPORT_SHIPPING_METHOD = 'paypal_express_checkout_shipping_method';
    const PAYMENT_INFO_TRANSPORT_PAYER_ID = 'paypal_express_checkout_payer_id';

    /**
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote = null;

    /**
     * Payone method config instance
     * @var Payone_Core_Model_Config_Payment_Method
     */
    protected $_config = null;

    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * State helper variables
     * @var string
     */
    protected $_redirectUrl = '';
    protected $_workorderid;

    /**
     * Order
     *
     * @var Mage_Sales_Model_Order
     */
    protected $_order = null;

    /**
     * Recurring payment profiles
     *
     * @var array
     */
    protected $_recurringPaymentProfiles = array();

    /**
     * Billing agreement that might be created during order placing
     *
     * @var Mage_Sales_Model_Billing_Agreement
     */
    protected $_billingAgreement = null;

    /**
     * Locale codes supported by misc images (marks, shortcuts etc)
     *
     * @var array
     * @link https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_ECButtonIntegration#id089QD0O0TX4__id08AH904I0YK
     */
    protected $_supportedImageLocales = array('de_DE', 'en_AU', 'en_GB', 'en_US', 'es_ES', 'es_XC', 'fr_FR',
        'fr_XC', 'it_IT', 'ja_JP', 'nl_NL', 'pl_PL', 'zh_CN', 'zh_XC',
    );

    /**
     * @var Mage_Customer_Model_Session
     */
    protected $_customerSession;

    /**
     * Set quote and config instances
     * @param array $params
     */
    public function __construct($params = array())
    {
        if (isset($params['quote']) && $params['quote'] instanceof Mage_Sales_Model_Quote) {
            $this->_quote = $params['quote'];
        } else {
            throw new Exception('Quote instance is required.');
        }

        if (isset($params['config']) && $params['config'] instanceof Payone_Core_Model_Config_Payment_Method) {
            $this->_config = $params['config'];
        } else {
            throw new Exception('Config instance is required.');
        }

        $this->_customerSession = Mage::getSingleton('customer/session');
    }

    /**
     * Checkout with PayPal image URL getter
     * @return string
     */
    public function getCheckoutShortcutImageUrl()
    {

        $localUrl = $this->getHelperConfig()->getConfigGeneral($this->_quote->getStoreId())->getPaymentPaypalExpressCheckout()->getPaypalExpressCheckoutImage();
        if($localUrl) {
            return Mage::getBaseUrl('media') . 'payone' . DS . $localUrl;
        }

        return sprintf('https://www.paypal.com/%s/i/btn/btn_xpressCheckout.gif', $this->_getSupportedLocaleCode(Mage::app()->getLocale()->getLocaleCode()));
    }

    /**
     * Specify quote payment method
     *
     * @param   array $data
     * @return  array
     */
    public function savePayment($data)
    {
        if ($this->_quote->isVirtual()) {
            $this->_quote->getBillingAddress()->setPaymentMethod(isset($data['method']) ? $data['method'] : null);
        } else {
            $this->_quote->getShippingAddress()->setPaymentMethod(isset($data['method']) ? $data['method'] : null);
        }

        // shipping totals may be affected by payment method
        if (!$this->_quote->isVirtual() && $this->_quote->getShippingAddress()) {
            $this->_quote->getShippingAddress()->setCollectShippingRates(true);
        }

//        $data['checks'] = Mage_Payment_Model_Method_Abstract::CHECK_USE_CHECKOUT
//            | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_COUNTRY
//            | Mage_Payment_Model_Method_Abstract::CHECK_USE_FOR_CURRENCY
//            | Mage_Payment_Model_Method_Abstract::CHECK_ORDER_TOTAL_MIN_MAX
//            | Mage_Payment_Model_Method_Abstract::CHECK_ZERO_TOTAL;

        $data['checks'] = array();

        $payment = $this->_quote->getPayment();

        $payment->importData($data);

        $this->_quote->save();
    }

    /**
     * Reserve order ID for specified quote and start checkout on PayPal
     *
     * @return mixed
     */
    public function start()
    {
        $this->_quote->collectTotals();

        if (!$this->_quote->getGrandTotal() && !$this->_quote->hasNominalItems()) {
            Mage::throwException(Mage::helper('payone_core')->__('PayPal does not support processing orders with zero amount. To complete your purchase, proceed to the standard checkout process.'));
        }

        $this->_quote->reserveOrderId()->save();
        
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->_config);
        $mapper = $service->getMapper();
        $request = $mapper->mapExpressCheckoutParameters($this->_quote);
        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);

        if($response instanceof Payone_Api_Response_Genericpayment_Redirect) {
            $this->_redirectUrl = $response->getRedirecturl();
            $this->_workorderid = $response->getWorkorderId();
        }
    }

    /**
     * Request with $workOrderId and saving the address
     *
     * @param string $workOrderId
     */
    public function returnFromPaypal($workOrderId)
    {
        $service = $this->getFactory()->getServicePaymentGenericpayment($this->_config);
        $mapper = $service->getMapper();
        $request = $mapper->mapExpressCheckoutParameters($this->_quote, $workOrderId);

        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);

        $this->_ignoreAddressValidation();

        if($response instanceof Payone_Api_Response_Genericpayment_Ok) {
            // @var Mage_Sales_Model_Quote_Address
            $billingAddress = $this->_quote->getBillingAddress();
            // @var Mage_Sales_Model_Quote_Address
            $shippingAddress = $this->_quote->getShippingAddress();
            foreach($response->getPayData()->getItems() as $item) {
                if($item->getKey() == 'email') {
                    $billingAddress->setEmail($item->getData());
                    $shippingAddress->setEmail($item->getData());
                }

                if($item->getKey() == 'shipping_zip') {
                    $billingAddress->setPostcode($item->getData());
                    $shippingAddress->setPostcode($item->getData());
                }

                if($item->getKey() == 'shipping_country') {
                    $billingAddress->setCountryId($item->getData());
                    $shippingAddress->setCountryId($item->getData());
                }

                if($item->getKey() == 'shipping_state' && $item->getData() != 'Empty') {
                    $billingAddress->setRegion($item->getData());
                    $shippingAddress->setRegion($item->getData());
                }

                if($item->getKey() == 'shipping_city') {
                    $billingAddress->setCity($item->getData());
                    $shippingAddress->setCity($item->getData());
                }

                if($item->getKey() == 'shipping_street') {
                    $billingAddress->setStreet($item->getData());
                    $shippingAddress->setStreet($item->getData());
                }

                if($item->getKey() == 'shipping_firstname') {
                    $billingAddress->setFirstname($item->getData());
                    $shippingAddress->setFirstname($item->getData());
                    $this->_quote->setCustomerFirstname($item->getData());
                }

                if($item->getKey() == 'shipping_lastname') {
                    $billingAddress->setLastname($item->getData());
                    $shippingAddress->setLastname($item->getData());
                    $this->_quote->setCustomerLastname($item->getData());
                }
            }

            $this->_quote->setBillingAddress($billingAddress);
            if (!$this->_quote->getIsVirtual()) {
                $shippingAddress->setCollectShippingRates(true);
                $shippingAddress->setSameAsBilling(0);
                $this->_quote->setShippingAddress($shippingAddress);
            }

            $this->_quote->getPayment()->setAdditionalInformation(self::PAYONE_EXPRESS_CHECKOUT_WORKORDERID, $workOrderId)
                ->setAdditionalInformation(self::PAYMENT_INFO_TRANSPORT_PAYER_ID, $workOrderId);

            $this->_quote->collectTotals()->save();
        }

    }

    /**
     * Preparing order review
     */
    public function prepareOrderReview()
    {
        $payment = $this->_quote->getPayment();
        if (!$payment || !$payment->getAdditionalInformation(self::PAYMENT_INFO_TRANSPORT_PAYER_ID)) {
            Mage::throwException(Mage::helper('paypal')->__('Payer is not identified.'));
        }

        $this->_quote->setMayEditShippingAddress(
            1 != $this->_quote->getPayment()->getAdditionalInformation(self::PAYMENT_INFO_TRANSPORT_SHIPPING_OVERRIDEN)
        );
        $this->_quote->setMayEditShippingMethod(
            '' == $this->_quote->getPayment()->getAdditionalInformation(self::PAYMENT_INFO_TRANSPORT_SHIPPING_METHOD)
        );
        $this->_ignoreAddressValidation();
        $this->_quote->collectTotals()->save();
    }

    /**
     * Place the order and recurring payment profiles when customer returned from paypal
     * Until this moment all quote data must be valid
     *
     * @param string $workOrderId
     * @param string $shippingMethodCode
     */
    public function place($workOrderId, $shippingMethodCode = null)
    {
        if ($shippingMethodCode) {
            $this->updateShippingMethod($shippingMethodCode);
        }

        $isNewCustomer = false;
        switch ($this->getCheckoutMethod()) {
            case Mage_Checkout_Model_Type_Onepage::METHOD_GUEST:
                $this->_prepareGuestQuote();
                break;
            case Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER:
                $this->_prepareNewCustomerQuote();
                $isNewCustomer = true;
                break;
            default:
                $this->_prepareCustomerQuote();
                break;
        }

        $this->_ignoreAddressValidation();
        $this->_quote->collectTotals();
        $service = Mage::getModel('sales/service_quote', $this->_quote);
        $service->submitAll();
        $this->_quote->save();

        if ($isNewCustomer) {
            try {
                $this->_involveNewCustomer();
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }

        $this->_recurringPaymentProfiles = $service->getRecurringPaymentProfiles();

        $order = $service->getOrder();
        if (!$order) {
            return;
        }

        $this->_billingAgreement = $order->getPayment()->getBillingAgreement();

        switch ($order->getState()) {
            // even after placement paypal can disallow to authorize/capture, but will wait until bank transfers money
            case Mage_Sales_Model_Order::STATE_PENDING_PAYMENT:
                // TODO
                break;
            // regular placement, when everything is ok
            case Mage_Sales_Model_Order::STATE_PROCESSING:
            case Mage_Sales_Model_Order::STATE_COMPLETE:
            case Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW:
                $order->sendNewOrderEmail();
                break;
        }

        $this->_order = $order;


    }

    /**
     * Return order
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Set shipping method to quote, if needed
     * @param string $methodCode
     */
    public function updateShippingMethod($methodCode)
    {
        if (!$this->_quote->getIsVirtual() && $shippingAddress = $this->_quote->getShippingAddress()) {
            if ($methodCode != $shippingAddress->getShippingMethod()) {
                $this->_ignoreAddressValidation();
                $shippingAddress->setShippingMethod($methodCode)->setCollectShippingRates(true);
                $this->_quote->collectTotals();
            }
        }
    }

    /**
     * Make sure addresses will be saved without validation errors
     */
    private function _ignoreAddressValidation()
    {
        $this->_quote->getBillingAddress()->setShouldIgnoreValidation(true);
        if (!$this->_quote->getIsVirtual()) {
            $this->_quote->getShippingAddress()->setShouldIgnoreValidation(true);
        }
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
     * @return Payone_Core_Helper_Config
     */
    protected function getHelperConfig()
    {
        return $this->getFactory()->helperConfig();
    }

    /**
     * Check whether specified locale code is supported. Fallback to en_US
     *
     * @param string $localeCode
     * @return string
     */
    protected function _getSupportedLocaleCode($localeCode = null)
    {
        if (!$localeCode || !in_array($localeCode, $this->_supportedImageLocales)) {
            return 'en_US';
        }

        return $localeCode;
    }

    /**
     * Determine whether redirect somewhere specifically is required
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->_redirectUrl;
    }

    /**
     * Determine whether redirect somewhere specifically is required
     *
     * @return integer
     */
    public function getWorkOrderId()
    {
        return $this->_workorderid;
    }

    /**
     * Get checkout method
     *
     * @return string
     */
    public function getCheckoutMethod()
    {
        if ($this->getCustomerSession()->isLoggedIn()) {
            return Mage_Checkout_Model_Type_Onepage::METHOD_CUSTOMER;
        }

        if (!$this->_quote->getCheckoutMethod()) {
            if (Mage::helper('checkout')->isAllowedGuestCheckout($this->_quote)) {
                $this->_quote->setCheckoutMethod(Mage_Checkout_Model_Type_Onepage::METHOD_GUEST);
            } else {
                $this->_quote->setCheckoutMethod(Mage_Checkout_Model_Type_Onepage::METHOD_REGISTER);
            }
        }

        return $this->_quote->getCheckoutMethod();
    }

    /**
     * Get customer session object
     *
     * @return Mage_Customer_Model_Session
     */
    public function getCustomerSession()
    {
        return $this->_customerSession;
    }

    /**
     * Prepare quote for guest checkout order submit
     *
     * @return Mage_Paypal_Model_Express_Checkout
     */
    protected function _prepareGuestQuote()
    {
        $quote = $this->_quote;
        $quote->setCustomerId(null)
            ->setCustomerEmail($quote->getBillingAddress()->getEmail())
            ->setCustomerIsGuest(true)
            ->setCustomerGroupId(Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
        return $this;
    }

    /**
     * Prepare quote for customer registration and customer order submit
     * and restore magento customer data from quote
     *
     * @return Mage_Paypal_Model_Express_Checkout
     */
    protected function _prepareNewCustomerQuote()
    {
        $quote      = $this->_quote;
        $billing    = $quote->getBillingAddress();
        $shipping   = $quote->isVirtual() ? null : $quote->getShippingAddress();

        $customerId = $this->_lookupCustomerId();
        if ($customerId) {
            $this->getCustomerSession()->loginById($customerId);
            return $this->_prepareCustomerQuote();
        }

        $customer = $quote->getCustomer();
        /** @var $customer Mage_Customer_Model_Customer */
        $customerBilling = $billing->exportCustomerAddress();
        $customer->addAddress($customerBilling);
        $billing->setCustomerAddress($customerBilling);
        $customerBilling->setIsDefaultBilling(true);
        if ($shipping && !$shipping->getSameAsBilling()) {
            $customerShipping = $shipping->exportCustomerAddress();
            $customer->addAddress($customerShipping);
            $shipping->setCustomerAddress($customerShipping);
            $customerShipping->setIsDefaultShipping(true);
        } elseif ($shipping) {
            $customerBilling->setIsDefaultShipping(true);
        }

        /**
         * @todo integration with dynamica attributes customer_dob, customer_taxvat, customer_gender
         */
        if ($quote->getCustomerDob() && !$billing->getCustomerDob()) {
            $billing->setCustomerDob($quote->getCustomerDob());
        }

        if ($quote->getCustomerTaxvat() && !$billing->getCustomerTaxvat()) {
            $billing->setCustomerTaxvat($quote->getCustomerTaxvat());
        }

        if ($quote->getCustomerGender() && !$billing->getCustomerGender()) {
            $billing->setCustomerGender($quote->getCustomerGender());
        }

        Mage::helper('core')->copyFieldset('checkout_onepage_billing', 'to_customer', $billing, $customer);
        $customer->setEmail($quote->getCustomerEmail());
        $customer->setPrefix($quote->getCustomerPrefix());
        $customer->setFirstname($quote->getCustomerFirstname());
        $customer->setMiddlename($quote->getCustomerMiddlename());
        $customer->setLastname($quote->getCustomerLastname());
        $customer->setSuffix($quote->getCustomerSuffix());
        $customer->setPassword($customer->decryptPassword($quote->getPasswordHash()));
        $customer->setPasswordHash($customer->hashPassword($customer->getPassword()));
        $customer->save();
        $quote->setCustomer($customer);

        return $this;
    }

    /**
     * Prepare quote for customer order submit
     *
     * @return Mage_Paypal_Model_Express_Checkout
     */
    protected function _prepareCustomerQuote()
    {
        $quote      = $this->_quote;
        $billing    = $quote->getBillingAddress();
        $shipping   = $quote->isVirtual() ? null : $quote->getShippingAddress();

        $customer = $this->getCustomerSession()->getCustomer();
        if (!$billing->getCustomerId() || $billing->getSaveInAddressBook()) {
            $customerBilling = $billing->exportCustomerAddress();
            $customer->addAddress($customerBilling);
            $billing->setCustomerAddress($customerBilling);
        }

        if ($shipping && ((!$shipping->getCustomerId() && !$shipping->getSameAsBilling())
                || (!$shipping->getSameAsBilling() && $shipping->getSaveInAddressBook()))) {
            $customerShipping = $shipping->exportCustomerAddress();
            $customer->addAddress($customerShipping);
            $shipping->setCustomerAddress($customerShipping);
        }

        if (isset($customerBilling) && !$customer->getDefaultBilling()) {
            $customerBilling->setIsDefaultBilling(true);
        }

        if ($shipping && isset($customerBilling) && !$customer->getDefaultShipping() && $shipping->getSameAsBilling()) {
            $customerBilling->setIsDefaultShipping(true);
        } elseif ($shipping && isset($customerShipping) && !$customer->getDefaultShipping()) {
            $customerShipping->setIsDefaultShipping(true);
        }

        $quote->setCustomer($customer);

        return $this;
    }

    /**
     * Checks if customer with email coming from Express checkout exists
     *
     * @return int
     */
    protected function _lookupCustomerId()
    {
        return Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getWebsite()->getId())
            ->loadByEmail($this->_quote->getCustomerEmail())
            ->getId();
    }

    /**
     * Involve new customer to system
     *
     * @return Mage_Paypal_Model_Express_Checkout
     */
    protected function _involveNewCustomer()
    {
        $customer = $this->_quote->getCustomer();
        if ($customer->isConfirmationRequired()) {
            $customer->sendNewAccountEmail('confirmation');
            $url = Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail());
            $this->getCustomerSession()->addSuccess(
                Mage::helper('customer')->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.', $url)
            );
        } else {
            $customer->sendNewAccountEmail();
            $this->getCustomerSession()->loginById($customer->getId());
        }

        return $this;
    }

    /**
     * Return recurring payment profiles
     *
     * @return array
     */
    public function getRecurringPaymentProfiles()
    {
        return $this->_recurringPaymentProfiles;
    }

    /**
     * Get created billing agreement
     *
     * @return Mage_Sales_Model_Billing_Agreement|null
     */
    public function getBillingAgreement()
    {
        return $this->_billingAgreement;
    }

}