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
 * @subpackage      Paydirekt_Express_Review
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Paydirekt_Express_Review_PaymentMethod extends Mage_Core_Block_Template
{
    /** @var int */
    protected $quoteId;

    public function init()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($this->quoteId);
        if (!$quote) {
            throw new Payone_Core_Exception_OrderNotFound('Quote with ID ' . $this->quoteId . ' was not found.');
        }
    }

    /**
     * @return int
     */
    public function getQuoteId()
    {
        return $this->quoteId;
    }

    /**
     * @param int $quoteId
     * @return Payone_Core_Block_Paydirekt_Express_Review_PaymentMethod
     */
    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        /** @var Payone_Core_Model_System_Config_PaymentMethodType $config */
        $config = Mage::getModel('payone_core/system_config_paymentmethodtype')->toArray();
        if (!isset($config[Payone_Core_Model_System_Config_PaymentMethodType::WALLETPAYDIREKTEXPRESS])) {
            return ucfirst(Payone_Core_Model_System_Config_PaymentMethodType::WALLETPAYDIREKTEXPRESS);
        }

        return $config[Payone_Core_Model_System_Config_PaymentMethodType::WALLETPAYDIREKTEXPRESS];
    }
}
