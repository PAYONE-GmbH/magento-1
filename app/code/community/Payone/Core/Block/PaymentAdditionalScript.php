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
        Payone_Core_Model_System_Config_PaymentMethodType::CREDITCARD => 'payone/core/creditcard.js',
        Payone_Core_Model_System_Config_PaymentMethodType::DEBITPAYMENT => 'payone/core/debitpayment.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFER => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFEREPS => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERIDL => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERBCT => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERGIROPAY => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERP24 => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERPFC => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERPFF => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERSOFORT => 'payone/core/onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTION => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONDEBIT => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONINSTALLMENT => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONINVOICING => 'payone/core/payolution.js',
        Payone_Core_Model_System_Config_PaymentMethodType::RATEPAY => 'payone/core/ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodType::RATEPAYDIRECTDEBIT => 'payone/core/ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodType::SAFEINVOICE => [
            'payone/core/safe_invoice.js',
            'payone/core/klarna.js',
        ],
    );

    /** @var Payone_Core_Model_Factory */
    private $factory;

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

        $storeId = $session->getQuote()->getStoreId();
        /** @var Payone_Core_Model_Config_Payment $paymentConfig */
        $paymentConfig = $this->getFactory()->helperConfig()->getConfigPayment($storeId);

        /** @var Payone_Core_Model_Config_Payment_Method $method */
        foreach ($paymentConfig->getAvailableMethods() as $method) {
            $addScript = (
                $method->getEnabled() &&
                isset($this->scriptsUrls[$method->getCode()])
            );

            if ($addScript) {
                $scriptUrl = $this->scriptsUrls[$method->getCode()];
                if (!is_array($scriptUrl)) {
                    $scriptUrl = array($scriptUrl);
                }

                foreach ($scriptUrl as $url) {
                    $loadedScripts[] = $this->getJsUrl($url);
                }
            }
        }

        return array_unique($loadedScripts);
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = Mage::getModel('payone_core/factory');
        }

        return $this->factory;
    }
}
