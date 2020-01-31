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
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Helper_Ratepay extends Payone_Core_Helper_Abstract
{
    const VALIDATION_STEP_POSTALCODE = 'zip';
    const VALIDATION_STEP_CURRENCY = 'currency';
    const VALIDATION_STEP_CUSTOMER_AGE = 'customerAge';
    const VALIDATION_STEP_PHONE_NUMBER = 'phone';
    const VALIDATION_STEP_BASKET_SIZE = 'basketSize';
    const VALIDATION_STEP_SHIPPING_ADDRESS = 'shippingAddress';
    const VALIDATION_STEP_SHIPPING_METHOD = 'shippingMethod';

    const MINIMUM_CUSTOMER_AGE = 18;

    protected $configCache = null;

    /**
     * @param string $step
     * @param array $methodsList
     * @return array
     */
    public function filterByValidation($step, $methodsList)
    {
        $methodName = 'validate' . ucfirst($step);
        if (!method_exists($this, $methodName)) {
            return $methodsList; //FIXME Decide if error when trying to check rule that doesn't exist
        }

        return $this->$methodName($methodsList);
    }

    /**
     * @param array $methodsList
     * @return array
     */
    protected function validateZip($methodsList)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->getQuote();

       $zipFormats = array(
           'all' => '/^.*$/',
           'de' => '/^[\d]{5}$/',
           'at' => '/^[\d\w]{4}$/',
           'ch' => '/^[\d\w]{4}$/',
           'nl' => '/^[\d\w]{6}$/',
       );

        $billZip = $quote->getBillingAddress()->getPostcode();
        $billCountry = strtolower($quote->getBillingAddress()->getCountry());
        $billZipFormat = isset($zipFormats[$billCountry]) ? $zipFormats[$billCountry] : $zipFormats['all'];

        $shipZip = $quote->getShippingAddress()->getPostcode();
        $shipCountry = strtolower($quote->getShippingAddress()->getCountry());
        $shipZipFormat = isset($zipFormats[$shipCountry]) ? $zipFormats[$shipCountry] : $zipFormats['all'];

        if (preg_match($billZipFormat, $billZip) !== 1 || preg_match($shipZipFormat, $shipZip) !== 1) {
            return array();
        }

        return $methodsList;
    }

    /**
     * @param array $methodsList
     * @return array
     */
    protected function validateCurrency($methodsList)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->getQuote();

        $allowedCurrencies = array(
            'eur' => array('de', 'at', 'nl'),
            'chf' => array('ch'),
        );

        $billCountry = strtolower($quote->getBillingAddress()->getCountry());
        $currency = strtolower($quote->getQuoteCurrencyCode());

        if (isset($allowedCurrencies[$currency]) && !in_array($billCountry, $allowedCurrencies[$currency])) {
            return array();
        }

        return $methodsList;
    }

    /**
     * @param array $methodsList
     * @return array
     */
    protected function validateCustomerAge($methodsList)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->getQuote();

        $today = (new DateTimeImmutable());
        $customerDob = new DateTimeImmutable($quote->getCustomerDob());
        if (!empty($quote->getCustomerDob()) && $today->diff($customerDob)->y < self::MINIMUM_CUSTOMER_AGE) {
            return array();
        }

        return $methodsList;
    }

    /**
     * @param array $methodsList
     * @return array
     */
    protected function validatePhone($methodsList)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->getQuote();

        $phoneFormat = '/^([ \/+\-()]*[\d]+[ \/+\-()]*){6,}$/';
        $billingPhone = $quote->getBillingAddress()->getTelephone();
        $shippingPhone = $quote->getShippingAddress()->getTelephone();

        if (preg_match($phoneFormat, $billingPhone) !== 1 || preg_match($phoneFormat, $shippingPhone) !== 1) {
            return array();
        }

        return $methodsList;
    }

    /**
     * TODO FCVB : Check what is exactly basket size (subtotal ?)
     *
     * @param array $methodsList
     * @return array
     */
    protected function validateBasketSize($methodsList)
    {
        $quote = $this->getQuote();
        $basketSize = $quote->getSubtotal();

        $configs = array_filter(
            $this->getRatepaySpecificConfig($quote),
            function($config, $methodCode) use ($methodsList, $basketSize) {
                return in_array($methodCode, $methodsList) && $basketSize >= $config['min_basket'] && $basketSize <= $config['max_basket'];
            },
            ARRAY_FILTER_USE_BOTH
        );

        return array_keys($configs);
    }

    /**
     * FIXME Question still open
     * TODO FCVB Ask for clarification : Currently different countries addresses are blocked earlier
     * TODO do we have to unlock that based on profile info, or we stick to same country ?
     *
     * @param array $methodsList
     * @return array
     */
    protected function validateShippingAddress($methodsList)
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->getQuote();
        $billAddress = $quote->getBillingAddress();
        $shipAddress = $quote->getShippingAddress();

        // Check if address are set to be the same
        if ($shipAddress->getSameAsBilling() == 1
            || $billAddress->getCustomerAddressId() == $shipAddress->getCustomerAddressId()
        ) {
            return $methodsList;
        }

        $configs = $this->getRatepaySpecificConfig($quote);
        $configs = array_filter(
            $configs,
            function($config, $methodCode) use ($methodsList) {
                return in_array($methodCode, $methodsList) && $config['different_addresses'] == 1;
            },
            ARRAY_FILTER_USE_BOTH
        );

        if (!empty($configs)) {
            return array_keys($configs);
        }

        if ($this->compareAddresses($billAddress, $shipAddress)) {
            return array();
        }

        return $methodsList;
    }

    /**
     * TODO FCVB : Question : What is Express Delivery
     *
     * @param array $methodsList
     * @return array
     */
    protected function validateShippingMethod($methodsList)
    {
        return $methodsList;
    }

    /**
     * @return array
     */
    public function getRatepayMethods()
    {
        return array(
            Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYINVOICING,
            Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAY,
            Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYDIRECTDEBIT
        );
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    protected function getQuote()
    {
        /** Mage_Sales_Model_Quote */
        return $this->getFactory()->getSingletonCheckoutSession()->getQuote();
    }

    /**
     * @param Mage_Sales_Model_Quote$quote
     * @return array
     */
    protected function getRatepaySpecificConfig($quote)
    {
        if (is_null($this->configCache)) {
            $methodCodes = array(
                Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYINVOICING => array(
                    'model' => Mage::getSingleton('payone_core/payment_method_ratepayinvoicing'),
                    'suffix' => 'invoice'
                ),
                Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAY =>array(
                    'model' => Mage::getSingleton('payone_core/payment_method_ratepay'),
                    'suffix' => 'installment'
                ),
                Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYDIRECTDEBIT => array(
                    'model' => Mage::getSingleton('payone_core/payment_method_ratepaydirectdebit'),
                    'suffix' => 'elv'
                ),
            );

            $finalConfigs = array();
            $currency = strtolower($quote->getQuoteCurrencyCode());
            $billCountry = strtolower($quote->getBillingAddress()->getCountryId());
            $shipCountry = strtolower($quote->getShippingAddress()->getCountryId());

            $cache = array();
            foreach ($methodCodes as $methodCode => $methodData) {
                $methodModel = $methodData['model'];
                $methodSuffix = $methodCodes[$methodCode]['suffix'];

                foreach ($methodModel->getConfigForQuote($quote)->getRatepayConfig() as $configDetails) {
                    $ratepayShopId = $configDetails['ratepay_shopid'];
                    if (!isset($cache[$ratepayShopId])) {
                        $cache[$ratepayShopId] = $methodModel->getRatePayConfigById($ratepayShopId);
                    }
                    $configDetails = $cache[$ratepayShopId];
                    $currOK = strtolower($configDetails['currency']) == $currency;
                    $billOK = strtolower($configDetails['country_code_billing']) == $billCountry;
                    $shipOK = strtolower($configDetails['country_code_delivery']) == $shipCountry;

                    if ($currOK && $billOK && $shipOK) {
                        $finalConfigs[$methodCode] = array(
                            'different_addresses' => $configDetails['delivery_address_' . $methodSuffix],
                            'min_basket' => $configDetails['tx_limit_' . $methodSuffix . '_min'],
                            'max_basket' => $configDetails['tx_limit_' . $methodSuffix . '_max'],
                        );
                        continue 2;
                    }

                }
            }

            $this->configCache = $finalConfigs;
        }

        return $this->configCache;
    }

    /**
     * Compares defined list of fields between the two addresses
     * returns false if any field value differs, true otherwise.
     *
     * @param Mage_Sales_Model_Quote_Address $billAddress
     * @param Mage_Sales_Model_Quote_Address $shipAddress
     * @return bool
     */
    protected function compareAddresses($billAddress, $shipAddress)
    {
        // TODO FCVB Filter out none-relevant
        $comparisonFields = array(
            'customer_id',
            'email',
            'prefix',
            'firstname',
            'middlename',
            'lastname',
            'suffix',
            'company',
            'street',
            'city',
            'region',
            'region_id',
            'postcode',
            'country_id',
            'telephone',
            'fax',
        );

        foreach ($comparisonFields as $field) {
            $bill = trim($billAddress->getData($field));
            $ship = trim($shipAddress->getData($field));

            if ($bill !== $ship) {
                return false;
            }
        }

        return true;
    }
}
