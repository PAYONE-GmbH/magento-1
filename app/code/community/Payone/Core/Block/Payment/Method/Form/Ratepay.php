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
        $customerDob = $this->getQuote()->getCustomerDob();
        if (empty($customerDob)) {
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
        return $aConfig['device_fingerprint_snippet_id'];
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
    
}