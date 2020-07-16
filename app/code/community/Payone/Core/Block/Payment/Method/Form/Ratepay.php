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
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/**
 * Class Payone_Core_Block_Payment_Method_Form_Ratepay
 */
class Payone_Core_Block_Payment_Method_Form_Ratepay extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    const RATE_PAYMENT_DEBIT_ONLY_DIRECTDEBIT = 0;
    const RATE_PAYMENT_DEBIT_ONLY_BANKTRANSFER = 1;
    const RATE_PAYMENT_DEBIT_BOTH = 2;

    /**
     * @var bool
     */
    protected $hasTypes = true;

    protected function _construct() 
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/ratepay.phtml');
    }

    /**
     * @return bool
     */
    public function isDobRequired()
    {
        // required for all countries
        // required only if customer didn't enter Dob in previous checkout step
        // and if process is not B2B
        $customerDob = $this->getQuote()->getCustomerDob();
        if (empty($customerDob) && !$this->isB2BMode()) {
            return true;
        }

        return false;
    }

    /**
     * Return Grand Total Amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->getQuote()->getGrandTotal();
    }


    /**
     * @return array
     */
    protected function getSystemConfigMethodTypes()
    {
        return $this->getFactory()->getModelSystemConfigRatePayType()->toSelectArray();
    }

    /**
     * @return bool
     */
    public function isTelephoneRequired()
    {
        // telephone is mandatory for any country in case of Klarna
        $telephone = $this->getQuote()->getBillingAddress()->getTelephone();
        if (empty($telephone)) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getRatePayCurrency() 
    {
        $oMethod = $this->getMethod();
        $aConfig = $oMethod->getMatchingRatePayConfig();
        return $aConfig['currency'];
    }

    /**
     * @return mixed
     */
    public function getMatchingRatePayShopId() 
    {
        $oMethod = $this->getMethod();
        $aConfig = $oMethod->getMatchingRatePayConfig();
        return $aConfig['shop_id'];
    }

    /**
     * @return mixed
     */
    public function getRatePayDeviceFingerprintSnippetId() 
    {
        $oMethod = $this->getMethod();
        $aConfig = $oMethod->getMatchingRatePayConfig();
        $ratepayConfig = $oMethod->getConfig()->getRatepayConfig();
        foreach ($ratepayConfig as $config) {
            if ($config['ratepay_shopid'] == $aConfig['shop_id']) {
                $snippetId = $config['ratepay_snippetid'];
                if (!empty($snippetId)) {
                    return $snippetId;
                }
            }
        }

        return !empty($aConfig['device_fingerprint_snippet_id']) ? $aConfig['device_fingerprint_snippet_id'] : 'ratepay';
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
        if(!$checkoutSession->getRatePayFingerprint()) {
            $sFingerprint  = $this->getQuote()->getBillingAddress()->getFirstname();
            $sFingerprint .= $this->getQuote()->getBillingAddress()->getLastname();
            $sFingerprint .= microtime();
            $sFingerprint = md5($sFingerprint);
            $this->_setSessionFingerprint($sFingerprint);
        } else {
            $sFingerprint = $checkoutSession->getRatePayFingerprint();
        }

        return $sFingerprint;
    }

    /**
     * Retrieve the payment config method id from Quote.
     * If it matches payment method, return it, otherwise 0
     * @return int|mixed
     */
    public function getPaymentMethodConfigId()
    {
        $preselectedConfigId = $this->getInfoData('payone_config_payment_method_id');

        $preselectPossible = false;
        if($this->getTypes()){
            foreach ($this->getTypes() as $type) {
                if ($type['config_id'] == $preselectedConfigId) {
                    $preselectPossible = true;
                }
            }
        }

        if ($preselectPossible) {
            return $preselectedConfigId;
        }
        else {
            return 0;
        }
    }

    /**
     * Checks if the quote was created as B2B
     * B2B = Company name is provided in the billing address
     *
     * @return bool
     */
    public function isB2BMode()
    {
        $sCompany = $this->getQuote()->getBillingAddress()->getCompany();

        return !empty($sCompany);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->getQuote()->getBillingAddress()->getCountry();
    }

    /**
     * @return string
     */
    public function getAllowedSEPACountries()
    {
        $sepaCountries = Mage::getModel('payone_core/system_config_sepaCountry');
        $array = $sepaCountries->toArray();

        return json_encode(array_keys($array));
    }

    /**
     * Returns the string containing the configured countries
     * allowed for ratepay installment direct debit
     * Returns 'all' if all countries are allowed
     *
     * @return string
     */
    public function getConfigDebitCountries()
    {
        /** @var Payone_Core_Model_Payment_Method_Ratepay $method */
        $method = Mage::getModel('payone_core/payment_method_ratepay');
        /** @var Payone_Core_Model_Config_Payment_Method $config */
        $config = $method->getConfigForQuote($this->getQuote());

        if ($config->getAllowspecific() == "0") {
            return 'all';
        }

        $ratepayDirectDebitAllowSpecific = $config->getRatepayDirectDebitSpecificCountry();

        return $ratepayDirectDebitAllowSpecific;
    }

    /**
     * @return string
     */
    public function getAccountOwner()
    {
        $billingContact = $this->getQuote()->getBillingAddress();
        if($this->isB2BMode()) {
            return $billingContact->getCompany();
        }

        return $billingContact->getFirstname() . ' ' . $billingContact->getLastname();
    }

    /**
     * return string
     */
    public function getRatepayDirectDebitAcceptanceText()
    {
        /** @var Payone_Core_Block_Payment_Method_RatepayDirectDebitSepaAcceptance $block */
        $block = Mage::app()->getLayout()->createBlock('payone_core/payment_method_ratepayDirectDebitSepaAcceptance');

        return $block->toHtml();
    }

    /**
     * @return bool
     */
    public function isAllowedDirectDebit()
    {
        $country = $this->getQuote()->getBillingAddress()->getCountry();
        // Switzerland does not allow RPS Lastschrift, no need for fields
        if ($country == 'CH') {
            return false;
        }

        $config = $this->getPaymentConfig();
        if ($config->getRatepayDirectdebitAllowspecific() == '0') {
            return true;
        }

        $allowedCountries = explode(',', $config->getRatepayDirectDebitSpecificCountry());
        if (in_array($country, $allowedCountries)) {
            return true;
        };

        return false;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface[]
     */
    public function getPaymentConfigs()
    {
        $configs = parent::getPaymentConfigs();

        foreach ($configs as $config) {
            if ($config->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::RATEPAY) {
                $config->setTypes(array(Payone_Api_Enum_RatepayType::RPS));
            }
        }

        return $configs;
    }

    /**
     * @return int
     */
    public function getRateDebitType()
    {
        $oMethod = $this->getMethod();
        $aConfig = $oMethod->getMatchingRatePayConfig();

        $validPaymentFirstDay = $aConfig['valid_payment_firstdays'];
        if($validPaymentFirstDay == 2) {
            return self::RATE_PAYMENT_DEBIT_ONLY_DIRECTDEBIT;
        } elseif($validPaymentFirstDay == 28) {
            return self::RATE_PAYMENT_DEBIT_ONLY_BANKTRANSFER;
        }

        return self::RATE_PAYMENT_DEBIT_BOTH;
    }
}