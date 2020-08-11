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
 * Class Payone_Core_Block_Payment_Method_RatepayDeviceFingerprint
 */
class Payone_Core_Block_Payment_Method_RatepayDeviceFingerprint
    extends Mage_Core_Block_Template
{
    /** @var Payone_Core_Model_Factory */
    protected $factory;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/ratepay_device_fingerprint.phtml');
    }

    /**
     * @param $sFingerprint
     */
    protected function _setSessionFingerprint($sFingerprint)
    {
        $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
        $checkoutSession->setRatePayFingerprint($sFingerprint);
    }

    /**
     * @return string
     */
    public function getRatePayDeviceFingerprint()
    {
        $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
        $quote = $checkoutSession->getQuote();

        if(!$checkoutSession->getRatePayFingerprint()) {
            $sFingerprint  = $quote->getBillingAddress()->getFirstname();
            $sFingerprint .= $quote->getBillingAddress()->getLastname();
            $sFingerprint .= microtime();
            $sFingerprint = md5($sFingerprint);
            $this->_setSessionFingerprint($sFingerprint);
        }

        return $checkoutSession->getRatePayFingerprint();
    }

    /**
     * @return mixed
     */
    public function getRatePayDeviceFingerprintSnippetId()
    {
        $deviceFingerprintSnippetId = $this->getFactory()->helperConfig()->getStoreConfig('payone_general/payment_ratepay_checkout/device_fingerprint_snippet_id');

        return !empty($deviceFingerprintSnippetId) ? $deviceFingerprintSnippetId : 'ratepay';
    }

    /**
     * @return bool
     */
    public function isRatepayIsActivated()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');
        $storeId = $session->getQuote()->getStoreId();
        $paymentConfig = $this->getFactory()->helperConfig()->getConfigPayment($storeId);

        /** @var Payone_Core_Model_Config_Payment_Method $method */
        foreach ($paymentConfig->getAvailableMethods() as $method) {
            if ($method->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::RATEPAYINVOICING
                || $method->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::RATEPAY
                || $method->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::RATEPAYDIRECTDEBIT
            ) {
                if ($method->getEnabled()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }
}
