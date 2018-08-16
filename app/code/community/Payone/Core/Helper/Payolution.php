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
 * @package         Payone_Core_Helper_Payolution
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

use Payone_Core_Model_System_Config_PaymentMethodCode as PaymentMethodCode;
use Payone_Core_Model_Payment_Method_Abstract as PayonePaymentMethod;

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper_Payolution
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Payolution extends Payone_Core_Helper_Abstract
{
    const ANALYSIS_SESSION_KEY_POSTFIX = 'analysis_session_id_key';
    const PAYOLUTION_FRAUD_PREVENTION_JS_FILE_PATH = 'payone/core/payolutionfraudprevention.js';

    private $payolutionMethodCodes = [
        PaymentMethodCode::PAYOLUTION,
        PaymentMethodCode::PAYOLUTIONDEBIT,
        PaymentMethodCode::PAYOLUTIONINSTALLMENT,
        PaymentMethodCode::PAYOLUTIONINVOICING,
    ];

    public function getAnalysisSessionId(PayonePaymentMethod $paymentMethod = null, $destroy = false)
    {
        if (is_null($paymentMethod)) {
            $paymentMethod = $this->getPaymentMethodFromQuoteInSession();
        }

        if (!$this->isPayolutionMethod($paymentMethod)) {
            return;
        }

        /** @var Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');
        $sessionKey = $this->getSessionKey($paymentMethod);

        if (!$analysisSessionId = $session->getData($sessionKey)) {
            $analysisSessionId = $this->generateAnalysisSessionId($paymentMethod);
            $session->setData($sessionKey, $analysisSessionId);
        }

        if ($destroy) {
            $session->unsetData($sessionKey);
        }

        return $analysisSessionId;
    }

    private function getPaymentMethodFromQuoteInSession()
    {
        /** @var Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');
        $quote = $session->getQuote();

        if (!$quote) {
            return;
        }

        $payment = $quote->getPayment();

        if (!$payment) {
            return;
        }

        return $payment->getMethodInstance();
    }

    private function generateAnalysisSessionId(PayonePaymentMethod $paymentMethod)
    {
        $prefix = md5($paymentMethod->getCode());

        return uniqid($prefix);
    }

    private function getSessionKey(PayonePaymentMethod $paymentMethod)
    {
        return $paymentMethod->getCode() . '_' . self::ANALYSIS_SESSION_KEY_POSTFIX;
    }

    /**
     * @param PayonePaymentMethod $paymentMethod
     *
     * @return bool
     */
    private function isPayolutionMethod($paymentMethod)
    {
        return in_array(
            $paymentMethod->getCode(),
            $this->payolutionMethodCodes
        );
    }

    public function getPayolutionJsScriptUrl()
    {
        return "https://h.online-metrix.net/fp/tags.js?org_id=363t8kgq&session_id={$this->getAnalysisSessionId()}";
    }

    public function getPayolutionIframeScriptUrl()
    {
        return "https://h.online-metrix.net/fp/tags?org_id=363t8kgq&session_id={$this->getAnalysisSessionId()}";
    }

    public function getPayolutionFraudPreventionJs()
    {
        return $this->isPayolutionMethodAvailable() ? self::PAYOLUTION_FRAUD_PREVENTION_JS_FILE_PATH : '';
    }

    private function isPayolutionMethodAvailable()
    {
        /** @var Mage_Payment_Model_Config $paymentConfig */
        $paymentConfig = Mage::getSingleton('payment/config');
        $allActivePaymentMethods = $paymentConfig->getAllMethods();

        foreach ($this->payolutionMethodCodes as $payolutionMethodCode) {
            if (!isset($allActivePaymentMethods[$payolutionMethodCode])) {
                continue;
            }
            /** @var Payone_Core_Model_Payment_Method_Payolution $payolutionMethod */
            $payolutionMethod = $allActivePaymentMethods[$payolutionMethodCode];
            if ($payolutionMethod->isAvailable()) {
                return true;
            }
        }

        return false;
    }
}
