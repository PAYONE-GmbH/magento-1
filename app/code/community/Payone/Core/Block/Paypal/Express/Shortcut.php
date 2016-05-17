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
 * @subpackage      Adminhtml
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

class Payone_Core_Block_Paypal_Express_Shortcut extends Mage_Core_Block_Template
{
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
    protected $_paymentMethodCode = Payone_Core_Model_System_Config_PaymentMethodCode::WALLET;

    /**
     * Payment method type for Paypal Express
     *
     * @var string
     */
    protected $_paymentMethodType = Payone_Api_Enum_WalletType::PAYPAL_EXPRESS;

    /**
     * Start express action
     *
     * @var string
     */
    protected $_startAction = 'payone_core/pexpress/start';

    protected function _beforeToHtml()
    {
        $result = parent::_beforeToHtml();
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        // check payment method availability
        $methodInstance = Mage::helper('payment')->getMethodInstance($this->_paymentMethodCode);
        if (!$methodInstance || !$methodInstance->isAvailable($quote)) {
            $this->_shouldRender = false;
            return $result;
        }

        // check payment method type availability
        $configMethod = $methodInstance->getConfigForQuote($quote);
        $methodTypes = $configMethod->getTypes();
        if(!in_array($this->_paymentMethodType, $methodTypes)) {
            $this->_shouldRender = false;
            return $result;
        }

        // check visibility button on shopping cart
        $shortcutOnShoppingCart = $this->getHelperConfig()->getConfigGeneral($quote->getStoreId())->getPaymentPaypalExpressCheckout()->getPaypalExpressCheckoutVisibleOnCart();
        if(empty($shortcutOnShoppingCart)) {
            $this->_shouldRender = false;
            return $result;
        }

        // check if we are in a secure environment
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        // set misc data
        $this->setShortcutHtmlId($this->helper('core')->uniqHash('ppe_shortcut_'))
            ->setCheckoutUrl($this->getUrl($this->_startAction, array('_secure' => $isSecure)));

        $this->setImageUrl(Mage::getModel('payone_core/service_paypal_express_checkout', array(
            'quote'  => $quote,
            'config' => $configMethod
        ))->getCheckoutShortcutImageUrl());


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
}