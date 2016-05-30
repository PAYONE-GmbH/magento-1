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
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_Core_Model_Config_Payment_Method_Interface
{
    /**
     * @return bool
     */
    public function isRequestAuthorization();

    /**
     * @return bool
     */
    public function isRequestPreauthorization();

    /**
     * @return bool
     */
    public function isInvoiceTransmitEnabled();

    /**
     * @return bool
     */
    public function isBankAccountCheckEnabled();

    /**
     * @return bool
     */
    public function isSepaMandateEnabled();

    /**
     * @return bool
     */
    public function isSepaDeShowBankDataEnabled();

    /**
     * @param string $country
     * @return bool
     */
    public function canUseForCountry($country);

    /**
     * @abstract
     * @param Mage_Sales_Model_Quote $quote
     * @return array
     */
    public function getFeeConfigForQuote(Mage_Sales_Model_Quote $quote);

    /**
     * @param int $aid
     */
    public function setAid($aid);

    /**
     * @return int
     */
    public function getAid();

    /**
     * @param int $allowspecific
     */
    public function setAllowspecific($allowspecific);

    /**
     * @return int
     */
    public function getAllowspecific();

    /**
     * @param int $check_bankaccount
     */
    public function setCheckBankAccount($check_bankaccount);

    /**
     * @return int
     */
    public function getCheckBankAccount();

    /**
     * @abstract
     * @param $bankaccountcheckType
     * @return void
     */
    public function setBankAccountCheckType($bankaccountcheckType);

    /**
     * @return int
     */
    public function getBankAccountCheckType();

    /**
     * @abstract
     * @param string $message_response_blocked
     * @return string
     */
    public function setMessageResponseBlocked($message_response_blocked);

    /**
     * @abstract
     * @return string
     */
    public function getMessageResponseBlocked();

    /**
     * @param array $sepaCountry
     */
    public function setSepaCountry($sepaCountry);

    /**
     * @return array
     */
    public function getSepaCountry();

    /**
     * @param int $sepaDeShowBankData
     */
    public function setSepaDeShowBankData($sepaDeShowBankData);

    /**
     * @return int
     */
    public function getSepaDeShowBankData();

    /**
     * @param int $sepaMandateEnabled
     */
    public function setSepaMandateEnabled($sepaMandateEnabled);

    /**
     * @return int
     */
    public function getSepaMandateEnabled();

    /**
     * @param int $sepaMandateDownloadEnabled
     */
    public function setSepaMandateDownloadEnabled($sepaMandateDownloadEnabled);

    /**
     * @return int
     */
    public function getSepaMandateDownloadEnabled();

    /**
     * @param int $customerFormDataSave
     */
    public function setCustomerFormDataSave($customerFormDataSave);

    /**
     * @return int
     */
    public function getCustomerFormDataSave();

    /**
     * @param string $check_cvc
     */
    public function setCheckCvc($check_cvc);

    /**
     * @return string
     */
    public function getCheckCvc();

    /**
     * @param string $code
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getCode();

    /**
     * @param int $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return int
     */
    public function getEnabled();

    /**
     * @param array $fee_config
     */
    public function setFeeConfig($fee_config);

    /**
     * @return array
     */
    public function getFeeConfig();

    /**
     * @abstract
     * @param $mode
     * @return mixed
     */
    public function setMode($mode);

    /**
     * @abstract
     * @return string
     */
    public function getMode();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $invoice_transmit
     */
    public function setInvoiceTransmit($invoice_transmit);

    /**
     * @return int
     */
    public function getInvoiceTransmit();

    /**
     * @param int $is_deleted
     */
    public function setIsDeleted($is_deleted);

    /**
     * @return int
     */
    public function getIsDeleted();

    /**
     * @param string $key
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param array $klarna_config
     */
    public function setKlarnaConfig($klarna_config);

    /**
     * @return array
     */
    public function getKlarnaConfig();

    /**
     * @param string $klarna_campaign_code
     */
    public function setKlarnaCampaignCode($klarna_campaign_code);

    /**
     * @return string
     */
    public function getKlarnaCampaignCode();

    /**
     * @param int $paypal_express_checkout_visible_on_cart)
     */
//    public function setPaypalExpressCheckoutVisibleOnCart($paypal_express_checkout_visible_on_cart);

    /**
     * @return int
     */
//    public function getPaypalExpressCheckoutVisibleOnCart();

    /**
     * @param int $paypal_express_address
     */
//    public function setPaypalExpressAddress($paypal_express_address);

    /**
     * @return int
     */
//    public function getPaypalExpressAddress();

    /**
     * @param string $paypal_express_image
     */
    public function setPaypalExpressImage($paypal_express_image);

    /**
     * @return string
     */
    public function getPaypalExpressImage();

    /**
     * @param int $mid
     */
    public function setMid($mid);

    /**
     * @return int
     */
    public function getMid();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param int $portalid
     */
    public function setPortalid($portalid);

    /**
     * @return int
     */
    public function getPortalid();

    /**
     * @param string $request_type
     */
    public function setRequestType($request_type);

    /**
     * @return string
     */
    public function getRequestType();

    /**
     * @param string $scope
     */
    public function setScope($scope);

    /**
     * @return string
     */
    public function getScope();

    /**
     * @param int $scope_id
     */
    public function setScopeId($scope_id);

    /**
     * @return int
     */
    public function getScopeId();

    /**
     * @param int $sort_order
     */
    public function setSortOrder($sort_order);

    /**
     * @return int
     */
    public function getSortOrder();

    /**
     * @param array $specificcountry
     */
    public function setSpecificcountry($specificcountry);

    /**
     * @return array
     */
    public function getSpecificcountry();

    /**
     * @param array $allowedCountries
     */
    public function setAllowedCountries($allowedCountries);

    /**
     * @return array
     */
    public function getAllowedCountries();

    /**
     * @param array $types
     */
    public function setTypes($types);

    /**
     * @return array
     */
    public function getTypes();

    /**
     * @param int $use_global
     */
    public function setUseGlobal($use_global);

    /**
     * @return int
     */
    public function getUseGlobal();

    /**
     * @param string $minValidityPeriod
     */
    public function setMinValidityPeriod($minValidityPeriod);

    /**
     * @return string
     */
    public function getMinValidityPeriod();

    /**
     * @return float
     */
    public function getMaxOrderTotal();

    /**
     * @return float
     */
    public function getMinOrderTotal();

    public function getParent();

    public function setParent($parentId);

    public function hasParent();

    /**
     * @param array $ratepay_config
     */
    public function setRatepayConfig($ratepay_config);

    /**
     * @return array
     */
    public function getRatepayConfig();
    
    /**
     * @param bool $show_customermessage
     */
    public function setShowCustomermessage($show_customermessage);

    /**
     * @return bool
     */
    public function getShowCustomermessage();
    
    /**
     * @param string $company_name
     */
    public function setCompanyName($company_name);

    /**
     * @return string
     */
    public function getCompanyName();
    
    /**
     * @param bool $b2b_mode
     */
    public function setB2bMode($b2b_mode);

    /**
     * @return bool
     */
    public function getB2bMode();
    
}
