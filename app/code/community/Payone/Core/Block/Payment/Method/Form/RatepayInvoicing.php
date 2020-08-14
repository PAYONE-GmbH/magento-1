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
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/**
 * Class Payone_Core_Block_Payment_Method_Form_RatepayInvoicing
 */
class Payone_Core_Block_Payment_Method_Form_RatepayInvoicing extends Payone_Core_Block_Payment_Method_Form_Abstract
{

    /**
     * @var bool
     */
    protected $hasTypes = true;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/ratepay_invoicing.phtml');
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
        return $this->getFactory()->getModelSystemConfigRatePayInvoicingType()->toSelectArray();
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
     * @return Payone_Core_Model_Config_Payment_Method_Interface[]
     */
    public function getPaymentConfigs()
    {
        $configs = parent::getPaymentConfigs();

        foreach ($configs as $config) {
            if ($config->getCode() == Payone_Core_Model_System_Config_PaymentMethodType::RATEPAYINVOICING) {
                $config->setTypes(array(Payone_Api_Enum_RatepayInvoicingType::RPV));
            }
        }

        return $configs;
    }
}