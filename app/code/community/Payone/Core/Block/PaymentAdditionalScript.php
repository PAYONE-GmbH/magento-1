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

    // creditcard.js introduced into MAGE-444
    // CC javascript included because some function/parameter ($length)
    // is used even when creditcard method is inactive. It makes all JS checkout crashing
    // Has to be investigated then removed
    private $sharedJsFiles = array(
        'client_api.js',
        'sepa_input.js',
        'sepa_validation.js',
        'shared.js',
        'creditcard.js'
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
        Payone_Core_Model_System_Config_PaymentMethodType::ONLINEBANKTRANSFERTRUSTLY => 'onlinebanktransfer.js',
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTION => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONDEBIT => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONINSTALLMENT => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONINVOICING => array('payolution.js', 'payolutionfraudprevention.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::RATEPAY => 'ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodType::RATEPAYINVOICING => 'ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodType::RATEPAYDIRECTDEBIT => 'ratepay.js',
        Payone_Core_Model_System_Config_PaymentMethodType::SAFEINVOICE => array('safe_invoice.js', 'klarna.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINVOICING => array('klarna.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINSTALLMENT => array('klarna.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::KLARNADIRECTDEBIT => array('klarna.js'),
        Payone_Core_Model_System_Config_PaymentMethodType::APPLEPAY => array('applepay.js'),
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

        $loadedScripts = array_map(
            function($url) {
                return $this->getJsUrl(self::JS_DIR_PREFIX . $url);
            },
            $this->sharedJsFiles
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
                    $loadedScripts[] = $this->getJsUrl(self::JS_DIR_PREFIX . $url);
                }
                if ($method->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINVOICING
                || $method->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::KLARNAINSTALLMENT
                || $method->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::KLARNADIRECTDEBIT) {
                    $loadedScripts[] = "https://x.klarnacdn.net/kp/lib/v1/api.js";
                }

                if ($method->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::APPLEPAY) {
                    $loadedScripts[] = "https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js";
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
