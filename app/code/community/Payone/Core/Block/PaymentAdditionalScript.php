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
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Block_PaymentAdditionalScript extends Mage_Core_Block_Template
{
    private $scriptsUrls = array(
        Payone_Core_Model_System_Config_PaymentMethodCode::CREDITCARD => 'payone/core/creditcard.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::DEBITPAYMENT => 'payone/core/debitpayment.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFER => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFEREPS => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERIDL => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERBCT => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERGIROPAY => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERP24 => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERPFC => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERPFF => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERSOFORT => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::PAYOLUTION => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::PAYOLUTIONDEBIT => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::PAYOLUTIONINSTALLMENT => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::PAYOLUTIONINVOICING => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAY => 'payone/core/ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYDIRECTDEBIT => 'payone/core/ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodCode::SAFEINVOICE => 'payone/core/safe_invoice.js',
    );

    /**
     * @return array
     */
    public function getActiveMethodScripts()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');

        $loadedScripts = array(
            $this->getJsUrl('payone/core/sepa_input.js'),
            $this->getJsUrl('payone/core/sepa_validation.js')
        );

        /** @var Payone_Core_Model_Config_Payment $paymentConfig */
        $paymentConfig = Mage::getSingleton('payment/config');

        /** @var Mage_Payment_Model_Method_Abstract $method */
        foreach ($paymentConfig->getAllMethods() as $method) {
            if ($method->isAvailable($session->getQuote())) {
                if (isset($this->scriptsUrls[$method->getCode()])) {
                    $loadedScripts[] = $this->getJsUrl($this->scriptsUrls[$method->getCode()]);
                }
            }
        }

        return array_unique($loadedScripts);
    }
}
