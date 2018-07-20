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
 * @subpackage      Mastercard_Masterpass
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Block_Mastercard_Masterpass_Shortcut extends Mage_Core_Block_Template
{
    const INIT_CHECKOUT_URL = 'payone_core/mastercardMasterpass/initCheckout';

    const MASTERPASS_LIB_TEST_URL = 'https://sandbox.masterpass.com/lightbox/Switch/integration/MasterPass.client.js';
    const MASTERPASS_LIB_LIVE_URL = 'https://www.masterpass.com/lightbox/Switch/integration/MasterPass.client.js';
    const MASTERPASS_LEARN_MORE_BASEURL = 'https://www.mastercard.com/mc_us/wallet/learnmore/';

    /**
     * Position of "OR" label against shortcut
     */
    const POSITION_BEFORE = 'before';
    const POSITION_AFTER = 'after';

    /** @var Payone_Core_Model_Factory */
    private $factory = null;

    /**
     * Whether the block should be eventually rendered
     *
     * @var bool
     */
    protected $_shouldRender = true;

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_paymentMethodCode = Payone_Core_Model_System_Config_PaymentMethodCode::MASTERPASS;

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
    public function isMasterpassActive()
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
     * @return string
     */
    public function getMasterpassLibraryUrl()
    {
        $config = $this->getConfiguration();

        if ($config && $config->getMode() === Payone_Enum_Mode::LIVE) {
            return self::MASTERPASS_LIB_LIVE_URL;
        }

        return self::MASTERPASS_LIB_TEST_URL;
    }

    protected function _beforeToHtml()
    {
        $result = parent::_beforeToHtml();
        return $result;
    }

    /**
     * Render the block if needed
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_shouldRender) {
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

        /** @var \Payone_Core_Model_Payment_Method_AmazonPay $paymentMethod */
        $paymentMethod = $paymentHelper->getMethodInstance(Payone_Core_Model_System_Config_PaymentMethodCode::MASTERPASS);

        try {
            /** @var \Payone_Core_Model_Config_Payment_Method $paymentConfig */
            $paymentConfig = $paymentMethod->getConfigForQuote($quote);
        } catch (\Payone_Core_Exception_PaymentMethodConfigNotFound $e) {
            return null;
        }

        return $paymentConfig;
    }

    /**
     * @return string
     */
    public function getInfoLink()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');

        $storeLocale = Mage::getStoreConfig(
            Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE,
            $session->getQuote()->getStoreId()
        );
        $storeLocale = str_replace('_', '/', $storeLocale);

        $linkUrl = self::MASTERPASS_LEARN_MORE_BASEURL . $storeLocale . '/';

        return '<a target="blank" href="'. $linkUrl . '">' . $this->__('Learn more') . '</a>';
    }
}
