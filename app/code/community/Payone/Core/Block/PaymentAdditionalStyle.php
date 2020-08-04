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
 * @subpackage
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Block_PaymentAdditionalStyle extends Mage_Core_Block_Template
{
    private $stylesheetUrls = array(
        Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY => 'payone/core/amazonpay_button.css',
        Payone_Core_Model_System_Config_PaymentMethodCode::WALLETPAYDIREKTEXPRESS => 'payone/core/paydirektexpress.css',
        Payone_Core_Model_System_Config_PaymentMethodCode::KLARNAINVOICING => 'payone/core/klarna.css',
        Payone_Core_Model_System_Config_PaymentMethodCode::KLARNAINSTALLMENT => 'payone/core/klarna.css',
        Payone_Core_Model_System_Config_PaymentMethodCode::KLARNADIRECTDEBIT => 'payone/core/klarna.css',
    );

    /**
     * @return array
     */
    public function getActiveShortcuts()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');

        /** @var \Mage_Payment_Helper_Data $paymentHelper */
        $paymentHelper = Mage::helper('payment');

        $activeMethods = array();
        foreach ($this->stylesheetUrls as $methodCode => $filename) {
            /** @var \Payone_Core_Model_Payment_Method_AmazonPay $paymentMethod */
            $paymentMethod = $paymentHelper->getMethodInstance($methodCode);

            try {
                /** @var \Payone_Core_Model_Config_Payment_Method $paymentConfig */
                $paymentConfig = $paymentMethod->getConfigForQuote($session->getQuote());
                if (!empty($paymentConfig)) {
                    $activeMethods[] = $this->getSkinUrl($filename);
                }
            } catch (\Payone_Core_Exception_PaymentMethodConfigNotFound $e) {
                continue;
            }
        }
        return $activeMethods;
    }
}
