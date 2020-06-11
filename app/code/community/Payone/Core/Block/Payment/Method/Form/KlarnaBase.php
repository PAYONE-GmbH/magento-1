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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/**
 * Class Payone_Core_Block_Payment_Method_Form_KlarnaBase
 */
class Payone_Core_Block_Payment_Method_Form_KlarnaBase extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    protected $klarnaMethods = array (
        Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINVOICING => Payone_Core_Model_System_Config_PaymentMethodCode::KLARNAINVOICING,
        Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINSTALLMENT => Payone_Core_Model_System_Config_PaymentMethodCode::KLARNAINSTALLMENT,
        Payone_Core_Model_System_Config_PaymentMethodType::KLARNADIRECTDEBIT => Payone_Core_Model_System_Config_PaymentMethodCode::KLARNADIRECTDEBIT,
    );

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/klarna_base.phtml');
    }

    /**
     * @param int $quoteId
     * @return array
     */
    public function getKlarnaMethods($quoteId)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote');
        $quote->load($quoteId);

        /** @var Payone_Core_Model_Config_Payment $configPayment */
        $configPayment = $this->getFactory()->helperConfig()->getConfigPayment($quote->getStoreId());

        $availableMethods = array();
        foreach ($this->klarnaMethods as $type => $code) {
            if ($configPayment->isAvailable($type, $quote)) {
                $availableMethods[$type] = $code;
            }
        }

        return $availableMethods;
    }

    /**
     * @return string
     */
    public function getStartSessionUrl()
    {
        return $this->getUrl('payone_core/klarna/startSession', array('_secure' => true));
    }

    /**
     * @return array
     */
    public function getKlarnaMethodNames()
    {
        return $this->klarnaMethods;
    }

    /**
     * @return array
     */
    public function getKlarnaMethodConfigId()
    {
        $klarnaMethodConfigIds = array(
            Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINVOICING => null,
            Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINSTALLMENT => null,
            Payone_Core_Model_System_Config_PaymentMethodType::KLARNADIRECTDEBIT => null,
        );
        foreach ($this->klarnaMethods as $type => $id) {
            $config = $this->helperConfig()->getConfigPaymentMethodByType($this->getQuote()->getStoreId(), $type);
            $klarnaMethodConfigIds[$type] = $config->getId();
        }

        return $klarnaMethodConfigIds;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        /** @var Mage_Checkout_Model_Session $checkoutSession */
        $checkoutSession = Mage::getSingleton('checkout/session');
        return $checkoutSession->getQuote();
    }
}
