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
 * @package         Payone_Core_Block
 * @subpackage      Paydirekt_Express
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Paydirekt_Express_Shortcut extends Mage_Core_Block_Template
{
    const INIT_CHECKOUT_URL = 'payone_core/paydirektExpress/initCheckout';

    const DEFAULT_LINK_IMAGE = 'https://www.paydirekt.de/presse/medien/logos/ohne-schutzzone/4c_ohne_schutzzone/paydirekt_logo_claim_4c.png';

    /**
     * Position of "OR" label against shortcut
     */
    const POSITION_BEFORE = 'before';
    const POSITION_AFTER = 'after';

    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * Whether the block should be eventually rendered
     *
     * @var bool
     */
    protected $shouldRender = true;

    /**
     * Payment method code
     *
     * @var string
     */
    protected $paymentMethodCode = Payone_Core_Model_System_Config_PaymentMethodCode::WALLETPAYDIREKTEXPRESS;

    /**
     * @var string
     */
    protected $quoteId;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @return bool
     */
    public function isPaydirektExpressActive()
    {
        $config = $this->getConfiguration();

        return !empty($config);
    }

    /**
     * @return string
     */
    public function getInitUrl()
    {
        return Mage::getBaseUrl() . self::INIT_CHECKOUT_URL;
    }

    /**
     * @return string
     */
    public function getQuoteId()
    {
        if ($this->quoteId == null) {
            $this->quoteId = Mage::getSingleton('checkout/session')->getQuoteId();
        }

        return $this->quoteId;
    }

    /**
     * @param string $quoteId
     */
    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        if ($this->customerId == null) {
            $this->customerId = Mage::getSingleton('customer/session')->getCustomerId();
        }

        return $this->customerId;
    }

    /**
     * @param string $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $result = parent::_beforeToHtml();
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        // check payment method availability
        $methodInstance = Mage::helper('payment')->getMethodInstance($this->paymentMethodCode);
        if (!$methodInstance || !$methodInstance->isAvailable($quote)) {
            $this->shouldRender = false;
            return $result;
        }

        // check visibility button on shopping cart
        $shortcutOnShoppingCart = $this->getHelperConfig()->getConfigGeneral($quote->getStoreId())->getPaymentPaydirektExpressCheckout()->getPaydirektExpressCheckoutVisibleOnCart();
        if(empty($shortcutOnShoppingCart)) {
            $this->shouldRender = false;
            return $result;
        }

        // check if we are in a secure environment
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        // set misc data
        $this->setShortcutHtmlId($this->helper('core')->uniqHash('pde_shortcut_'))
            ->setCheckoutUrl($this->getUrl(self::INIT_CHECKOUT_URL, array('_secure' => $isSecure)));

        $this->setImageUrl($this->_getCheckoutShortcutImageUrl($quote));

        return $result;
    }

    /**
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->shouldRender) {
            return '';
        }

        return parent::_toHtml();
    }

    /**
     * Check is "OR" label position before shortcut
     *
     * @return bool
     */
    public function isOrPositionBefore()
    {
        return ($this->getShowOrPosition() && $this->getShowOrPosition() == self::POSITION_BEFORE);
    }

    /**
     * Check is "OR" label position after shortcut
     *
     * @return bool
     */
    public function isOrPositionAfter()
    {
        return ($this->getShowOrPosition() && $this->getShowOrPosition() == self::POSITION_AFTER);
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function getHelperConfig()
    {
        return $this->getFactory()->helperConfig();
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }

    /**
     * Checkout with Paydirekt image URL getter
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return string
     */
    protected function _getCheckoutShortcutImageUrl($quote)
    {
        $localUrl = $this->getHelperConfig()->getConfigGeneral($quote->getStoreId())->getPaymentPaydirektExpressCheckout()->getPaydirektExpressCheckoutImage();
        if ($localUrl) {
            return Mage::getBaseUrl('media') . 'payone' . DS . $localUrl;
        }

        return self::DEFAULT_LINK_IMAGE;
    }

    /**
     * @return \Payone_Core_Model_Config_Payment_Method
     */
    private function getConfiguration()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');

        /** @var \Mage_Sales_Model_Quote $quote */
        $quote = $session->getQuote();

        /** @var \Mage_Payment_Helper_Data $paymentHelper */
        $paymentHelper = Mage::helper('payment');

        /** @var \Payone_Core_Model_Payment_Method_WalletPaydirektExpress $paymentMethod */
        $paymentMethod = $paymentHelper->getMethodInstance(Payone_Core_Model_System_Config_PaymentMethodCode::WALLETPAYDIREKTEXPRESS);

        try {
            /** @var \Payone_Core_Model_Config_Payment_Method $paymentConfig */
            $paymentConfig = $paymentMethod->getConfigForQuote($quote);
        } catch (\Payone_Core_Exception_PaymentMethodConfigNotFound $e) {
            return null;
        }

        return $paymentConfig;
    }

    /**
     * We try to collect the shipping rates *
     * If not found, the method is not usable, and not displayed
     *
     * *2 tries :
     *   - First try with the configured shipping method
     *   - Second try, check if any other rate exists, if so, pick it
     * The same mechanism will be used when processing the quote for real (during Checkout)
     *
     * Note for improvement : this might bring slight perf reduction on systems with many shipping options
     *
     * @return bool
     */
    public function isApplicable()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($this->getQuoteId());

        if (is_null($quote)) {
            return false;
        }

        if (!empty($quote->getShippingAddress()->getShippingMethod())) {
            return true;
        }

        $shippingAddress = $quote->getShippingAddress()
            ->setCountryId('DE')
            ->setCity('Stadt')
            ->setPostcode('12345');
        $shippingAddress->requestShippingRates();
        /** @var Payone_Core_Model_Payment_Method_Abstract $methodInstance */
        $methodInstance = Mage::helper('payment')->getMethodInstance(Payone_Core_Model_System_Config_PaymentMethodCode::WALLETPAYDIREKTEXPRESS);
        /** @var Payone_Core_Model_Config_Payment_Method $config */
        $config = $methodInstance->getConfigForQuote($quote);
        $configShippingMethod = $config->getAssociatedShippingMethod();

        // Try with configured shipping method
        $found = $this->rateExistsForMethod($shippingAddress, $configShippingMethod);
        if (!empty($found)) {
            $shippingAddress->save();
            return true;
        }

        // Try with first available shipping method
        $rates = $shippingAddress->getShippingRatesCollection()->getItems();
        if (!empty($rates)) {
            /** @var Mage_Sales_Model_Quote_Address_Rate $availableRate */
            $availableRate = array_shift($rates);
            $shippingAddress->setShippingMethod($availableRate->getMethod())->save();

            return true;
        }

        return false;
    }

    /**
     * @param Mage_Sales_Model_Quote_Address $shippingAddress
     * @param string $configShippingMethod
     * @return bool
     */
    private function rateExistsForMethod($shippingAddress, $configShippingMethod)
    {
        $shippingAddress->setShippingMethod($configShippingMethod);
        $shippingAddress
            ->setCollectShippingRates(1)
            ->collectShippingRates();

        return !empty($shippingAddress->getShippingRateByCode($configShippingMethod));
    }
}
