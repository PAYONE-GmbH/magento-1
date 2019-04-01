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
 * @package         Payone_Core_Helper_SafeInvoice
 * @subpackage
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Helper_SafeInvoice extends Payone_Core_Helper_Abstract
{
    const SAFE_INVOICE_METHODE_CODE = Payone_Core_Model_System_Config_PaymentMethodCode::SAFEINVOICE;

    const KLARNA_SCRIPT_URL = 'https://cdn.klarna.com/public/kitt/core/v1.0/js/klarna.min.js';
    const KLARNA_TERMS_SCRIPT_URL = 'https://cdn.klarna.com/public/kitt/toc/v1.1/js/klarna.terms.min.js';

    /**
     * @return string
     */
    public function getKlarnaScriptLink()
    {
        return $this->getLink(self::KLARNA_SCRIPT_URL);
    }

    /**
     * @return string
     */
    public function getKlarnaTermsScriptLink()
    {
        return $this->getLink(self::KLARNA_TERMS_SCRIPT_URL);
    }

    /**
     * @return bool
     */
    public function isSafeInvoiceMethodAvailable()
    {
        /** @var Mage_Payment_Model_Config $paymentConfig */
        $paymentConfig = Mage::getSingleton('payment/config');
        $allActivePaymentMethods = $paymentConfig->getAllMethods();

        if (!isset($allActivePaymentMethods[self::SAFE_INVOICE_METHODE_CODE])) {
            return false;
        }

        /** @var Payone_Core_Model_Payment_Method_Payolution $method */
        $method = $allActivePaymentMethods[self::SAFE_INVOICE_METHODE_CODE];
        return $method->isAvailable();
    }

    /**
     * @param string $url
     * @return string
     */
    private function getLink($url)
    {
        return '<script type="text/javascript" src="' . $url . '"></script>';
    }
}
