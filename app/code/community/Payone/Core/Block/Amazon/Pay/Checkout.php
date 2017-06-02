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
 * @subpackage      Amazon_Pay
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Amazon_Pay_Checkout extends Mage_Core_Block_Template
{
    /**
     * @return \Payone_Core_Model_Config_Payment_Method
     */
    protected function getConfiguration()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');
        /** @var \Mage_Sales_Model_Quote $quote */
        $quote = $session->getQuote();
        /** @var \Mage_Payment_Helper_Data $paymentHelper */
        $paymentHelper = Mage::helper('payment');
        /** @var \Payone_Core_Model_Payment_Method_AmazonPay $paymentMethod */
        $paymentMethod = $paymentHelper->getMethodInstance(
            Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY
        );
        /** @var \Payone_Core_Model_Config_Payment_Method $paymentConfig */
        $paymentConfig = $paymentMethod->getConfigForQuote($quote);

        return $paymentConfig;
    }
}
