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
    const JS_DIR_PREFIX = 'payone/core/';

    private $sharedJsFiles = array(
        self::JS_DIR_PREFIX . 'client_api.js',
        self::JS_DIR_PREFIX . 'opcheckoutmod.js',
        self::JS_DIR_PREFIX . 'sepa_inputs.js',
        self::JS_DIR_PREFIX . 'sepa_validation.js',
        self::JS_DIR_PREFIX . 'shared.js',
    );

    private $scriptsUrls = array(
        Payone_Core_Model_System_Config_PaymentMethodType::CREDITCARD => 'creditcard.js',
        Payone_Core_Model_System_Config_PaymentMethodType::DEBITPAYMENT => 'debitpayment.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFER => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFEREPS => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERIDL => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERBCT => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERGIROPAY => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERP24 => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERPFC => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERPFF => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERSOFORT => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTION => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONDEBIT => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONINSTALLMENT => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONINVOICING => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::RATEPAY => 'ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodType::RATEPAYDIRECTDEBIT => 'ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodType::SAFEINVOICE => array('safe_invoice.js', 'klarna.js'),
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

        $loadedScripts = array_merge(array(), $this->sharedJsFiles);

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
                    $loadedScripts[] = $this->getJsUrl(self::JS_DIR_PREFIX . $url);
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
