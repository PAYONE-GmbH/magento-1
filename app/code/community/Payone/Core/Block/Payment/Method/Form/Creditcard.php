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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Payment_Method_Form_Creditcard
    extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    
    protected $_aHostedParams = null;
    protected $hasTypes = true;
    protected $_aTranslations = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/creditcard.phtml');
    }

    /**
     * Name from billing address in the format "Firstname Lastname".
     * @return string
     */
    public function getBillingName()
    {
        $billingName = $this->getSavedCustomerData('cc_owner');
        if(empty($billingName)) {
            $quote = $this->getQuote();
            $address = $quote->getBillingAddress();
            $billingName = $address->getFirstname() . ' ' . $address->getLastname();
        }

        return $billingName;
    }

    /**
     * @return Mage_Payment_Model_Config
     */
    protected function getMagentoPaymentConfig()
    {
        return $this->getFactory()->getSingletonPaymentConfig();
    }

    public function getCvcJson()
    {
        $return = array();
        foreach ($this->getTypes() as $key => $type) {
            $return[$key] = $type['check_cvc'];
        }

        return json_encode($return);
    }

    /**
 * @return array
 */
    protected function getSystemConfigMethodTypes()
    {
        return $this->getFactory()->getModelSystemConfigCreditCardType()->toSelectArray();
    }

    /**
     * @return array
     */
    protected function getSystemConfigHideCvc()
    {
        return $this->getFactory()->getModelSystemConfigHideCvc()->toSelectArray();
    }

    /**
     * @return null|string
     */
    public function getHideCvcTypes(){

        $hideTypes = $this->getPaymentConfig()->getHideCvc();
        if(!empty($hideTypes)){
            return Mage::helper('core')->jsonEncode(($hideTypes));
        }

        return null;
    }

    /**
     * @return string
     */
    public function getCreditCardType()
    {
        $creditCardType = $this->getSavedCustomerData('cc_type');
        if(empty($creditCardType)) {
            $creditCardType = $this->getInfoData('cc_type');
        }

        return $creditCardType;
    }

    /**
     * @return string
     */
    public function getPayoneConfigPaymentMethodId()
    {
        $payoneConfigPaymentMethodId = $this->getSavedCustomerData('payone_config_payment_method_id');
        return $payoneConfigPaymentMethodId;
    }

    /**
     * @return string
     */
    public function getCreditCardNumberEnc()
    {
        $creditCardNumberEnc = $this->getSavedCustomerData('cc_number_enc');
        return $creditCardNumberEnc;
    }

    /**
     * @return string
     */
    public function getCreditCardExpireYear()
    {
        $ccExpYear = $this->getSavedCustomerData('cc_exp_year');
        if(empty($ccExpYear)) {
            $ccExpYear = $this->getInfoData('cc_exp_year');
        }

        return $ccExpYear;
    }

    /**
     * @return string
     */
    public function getCreditCardExpireMonth()
    {
        $ccExpMonth = $this->getSavedCustomerData('cc_exp_month');
        if(empty($ccExpMonth)) {
            $ccExpMonth = $this->getInfoData('cc_exp_month');
        }

        return $ccExpMonth;
    }

    /**
     * @return string
     */
    public function getPayonePseudocardpan()
    {
        $payonePseudocardpan = $this->getSavedCustomerData('payone_pseudocardpan');
        return $payonePseudocardpan;
    }

    /**
     * @return string
     */
    public function getCreditCardCid()
    {
        return '';
    }
    
    public function getDisplayCvcConfig()
    {
        $sShowCvc = '';
        foreach ($this->getTypes() as $key => $type) {
            $sShowCvc = $type['check_cvc'];
        }

        return $sShowCvc;
    }
    
    public function showCvcInput()
    {
        $sDisplayCvc = $this->getDisplayCvcConfig();
        if($sDisplayCvc == 'always' || ($sDisplayCvc == 'only_first' && $this->getPayoneCreditCardCheckValidation() == '1')) {
            return true;
        }

        return false;
    }
    
    public function _getShopLanguage()
    {
        $sLangCode = Mage::app()->getLocale()->getLocaleCode();
        return substr($sLangCode, 0, 2);
    }
    
    public function getTranslations()
    {
        if($this->_aTranslations === null) {
            $this->_aTranslations = array();
            $sLang = $this->_getShopLanguage();
            $aTranslations = $this->getConfigGeneral()->getCcHostedTranslations()->getAllCcTranslations();
            if(isset($aTranslations[$sLang])) {
                $this->_aTranslations = $aTranslations[$sLang];
            }
        }

        return $this->_aTranslations;
    }
    
    public function getTranslationLanguage()
    {
        $aTranslations = $this->getTranslations();
        if(count($aTranslations) > 0) {
            return $this->_getShopLanguage();
        }

        return false;
    }
    
    /**
     * @return integer
     */
    public function getPayoneCreditCardCheckValidation()
    {
        $sEnc = $this->getCreditCardNumberEnc();
        $sType = $this->getCreditCardType();
        $sYear = $this->getCreditCardExpireYear();
        $sMonth = $this->getCreditCardExpireMonth();
        if(empty($sEnc) || empty($sType) || empty($sYear) || empty($sMonth)) {
            return 1;
        }

        return 0;
    }

    /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getCcMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months)) {
            $months[0] = $this->__('Month');
            $months = array_merge($months, $this->getMagentoPaymentConfig()->getMonths());
            $this->setData('cc_months', $months);
        }

        return $months;
    }

    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCcYears()
    {
        $years = $this->getData('cc_years');
        if (is_null($years)) {
            $years = $this->getMagentoPaymentConfig()->getYears();
            $years = array(0 => $this->__('Year')) + $years;
            $this->setData('cc_years', $years);
        }

        return $years;
    }

    /**
     * @return string
     */
    public function getClientApiConfigAsJson()
    {
        return json_encode($this->getClientApiConfig());
    }

    /**
     * @return array
     */
    public function getClientApiConfig()
    {
        $creditcardcheck = $this->getCreditcardcheckParams();
        $allowedValidity = $this->getAllowedValidityTimestamp();

        $params = array(
            'gateway' => $creditcardcheck,
            'validation' => array(
                'allowed_validity' => $allowedValidity
            ),
        );

        return $params;
    }

    /**
     * Returns the gateways, one for each payment configuration
     * @return array
     */
    public function getCreditcardcheckParams()
    {
        $paymentConfigs = $this->getPaymentConfigs();
        /** @var $helper Payone_Core_Helper_Data */
        $helper = $this->helper('payone_core');
        $factory = $this->getFactory();
        $helperUrl = $this->getFactory()->helperUrl();

        $serviceGenerateHash = $factory->getServiceClientApiGenerateHash();

        $language = $helper->getDefaultLanguage();

        $gateways = array();
        foreach ($paymentConfigs as $paymentConfig) {
            $request = $factory->getRequestClientApiCreditCardCheck();
            $params = array(
                'aid' => $paymentConfig->getAid(),
                'mid' => $paymentConfig->getMid(),
                'portalid' => $paymentConfig->getPortalid(),
                'mode' => $paymentConfig->getMode(),
                'encoding' => 'UTF-8',
                'language' => $language,
                'solution_version' => $helper->getPayoneVersion(),
                'solution_name' => 'fatchip',
                'integrator_version' => $helper->getMagentoVersion(),
                'integrator_name' => 'Magento',
                'storecarddata' => 'yes',
                'successurl' => $helperUrl->getSuccessUrl(),
                'errorurl' => $helperUrl->getErrorUrl()

            );
            $request->init($params);
            $request->setResponsetype('JSON');

            $hash = $serviceGenerateHash->generate($request, $paymentConfig->getKey());

            $request->setHash($hash);

            $params = $request->toArray();

            $gateways[$paymentConfig->getId()] = $params;
        }

        return $gateways;
    }

    /**
     * @return string
     */
    public function getAllowedValidityTimestamp()
    {
        $config = $this->getConfigGeneral();

        $days = $config->getPaymentCreditcard()->getMinValidityPeriod();
        if (empty($days)) {
            $days = 0;
        }

        $allowedDate = new DateTime(now());
        $allowedDate->modify('+ ' . $days . ' days');

        $timestamp = $allowedDate->format('U');

        return $timestamp;
    }

    /**
     * @override To prevent display of fee config on payment method, as there might be differen fees for each credit card type
     *
     * @return string
     */
    public function getMethodLabelAfterHtml()
    {
        return '';
    }
    
    public function getCCRequestType() 
    {
        return $this->getConfigGeneral()->getPaymentCreditcard()->getCcRequestType();
    }
    
    protected function _getHostedParams() 
    {
        if($this->_aHostedParams === null) {
            $aParams = array();
            
            $sTemplate = $this->getConfigGeneral()->getPaymentCreditcard()->getCcTemplate();
            if($sTemplate) {
                $aParams = unserialize($sTemplate);
            }

            $this->_aHostedParams = $aParams;
        }

        return $this->_aHostedParams;
    }
    
    public function getHostedParam($sParam) 
    {
        $aParams = $this->_getHostedParams();
        if(isset($aParams[$sParam])) {
            return $aParams[$sParam];
        }

        return '';
    }
    
    /**
     * @return string
     */
    public function getHostedClientApiConfigAsJson()
    {
        return json_encode($this->getHostedClientApiConfig());
    }

    /**
     * @return array
     */
    public function getHostedClientApiConfig()
    {
        $params = array(
            'gateway' => $this->getHostedCreditcardcheckParams(),
        );

        return $params;
    }

    /**
     * Returns the gateways, one for each payment configuration
     * @return array
     */
    public function getHostedCreditcardcheckParams()
    {
        $paymentConfigs = $this->getPaymentConfigs();
        /** @var $helper Payone_Core_Helper_Data */
        $helper = $this->helper('payone_core');
        $factory = $this->getFactory();
        $helperUrl = $this->getFactory()->helperUrl();

        $serviceGenerateHash = $factory->getServiceClientApiGenerateHash();

        $language = $helper->getDefaultLanguage();

        $gateways = array();
        foreach ($paymentConfigs as $paymentConfig) {
            $request = $factory->getRequestClientApiCreditCardCheck();
            $params = array(
                'aid' => $paymentConfig->getAid(),
                'mid' => $paymentConfig->getMid(),
                'portalid' => $paymentConfig->getPortalid(),
                'mode' => $paymentConfig->getMode(),
                'encoding' => 'UTF-8',
                'storecarddata' => 'yes',
            );
            $request->init($params);
            $request->setResponsetype('JSON');

            $hash = $serviceGenerateHash->generate($request, $paymentConfig->getKey());

            $request->setHash($hash);

            $params = $request->toArray();

            $gateways[$paymentConfig->getId()] = $params;
        }

        return $gateways;
    }
    
}