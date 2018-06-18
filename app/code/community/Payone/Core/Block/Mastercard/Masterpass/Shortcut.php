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

    protected function getMockTestUrl()
    {
        return Mage::getBaseUrl() . 'payone_core/mastercardMasterpass/test';
    }
}
