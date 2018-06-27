<?php
/**
 * @category        Payone
 * @package         Payone_Core_Helper
 * @author          Andrzej Rosiek <service@e3n.de>
 * @copyright       Copyright (c) 2017 (https://e3n.de)
 * @license         http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

use Payone_Core_Model_System_Config_PaymentMethodCode as PaymentMethodCode;
use Payone_Core_Model_Payment_Method_Abstract as PayonePaymentMethod;

class Payone_Core_Helper_Payolution extends Payone_Core_Helper_Abstract
{
    const ANALYSIS_SESSION_KEY_POSTFIX = 'analysis_session_id_key';

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
            [
                PaymentMethodCode::PAYOLUTION,
                PaymentMethodCode::PAYOLUTIONDEBIT,
                PaymentMethodCode::PAYOLUTIONINSTALLMENT,
                PaymentMethodCode::PAYOLUTIONINVOICING,
            ]);
    }

    public function getPayolutionJsScriptUrl()
    {
        return "https://h.online-metrix.net/fp/tags.js?org_id=363t8kgq&session_id={$this->getAnalysisSessionId()}";
    }

    public function getPayolutionIframeScriptUrl()
    {
        return "https://h.online-metrix.net/fp/tags?org_id=363t8kgq&session_id={$this->getAnalysisSessionId()}";
    }
}
