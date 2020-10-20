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
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */
class Payone_Core_Block_Payment_Method_Form_OnlineBankTransferTrustly
    extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    /**
     * @var bool
     */
    protected $hasTypes = true;
    /**
     * @var null
     */
    protected $config = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/onlinebanktransfertrustly.phtml');
    }

    public function getOnlineBankTransferType()
    {
        return Payone_Api_Enum_OnlinebanktransferType::TRUSTLY;
    }

    public function getBillingName()
    {
        $quote = $this->getQuote();
        $address = $quote->getBillingAddress();
        return $address->getFirstname() . ' ' . $address->getLastname();
    }

    /**
     * Returns quote country
     * @return string
     */
    public function getCountry()
    {
        $country = $this->getSavedCustomerData('payone_bank_country');
        if(empty($country)) {
            $quote = $this->getQuote();
            $country = $quote->getBillingAddress()->getCountry();
        }

        return $country;
    }

    /**
     * Return list of selected SEPA countries for debit payment
     *
     * @return array
     */
    public function getSelectedSepaCountries()
    {
        $paymentConfig = $this->getPaymentConfig();
        $selectedCountryCodes = $paymentConfig->getSepaCountry();

        if (!$selectedCountryCodes) {
            return array();
        }

        /** @var Mage_Directory_Model_Resource_Country_Collection $countryCollection */
        $countryCollection = Mage::getResourceModel('directory/country_collection');
        $allCountries = $countryCollection->loadData()->toOptionArray(false);

        $resultArr = array();
        foreach ($allCountries as $country) {
            if (in_array($country['value'], $selectedCountryCodes)) {
                $resultArr[$country['value']] = $country['label'];
            }
        }

        return $resultArr;
    }

    /**
     * Return if bic input has to be shown
     *
     * @return bool
     */
    public function getSepaRequestBic()
    {
        return $this->getMethod()->getConfig()->getSepaRequestBic();
    }
}
