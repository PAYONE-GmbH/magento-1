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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2016 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Core_Model_Payment_Method_Ratepay extends Payone_Core_Model_Payment_Method_Abstract
{
    protected $_canUseForMultishipping = true;

    protected $methodType = Payone_Core_Model_System_Config_PaymentMethodType::RATEPAY;

    protected $_code = Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAY;

    protected $_formBlockType = 'payone_core/payment_method_form_ratepay';
    protected $_infoBlockType = 'payone_core/payment_method_info_ratepay';
    
    protected $_sTableName = 'payone_ratepay_config';
    
    protected $_mustTransimitInvoicingData = true;
    
    protected $_aRatePayShopConfig = null;
    
    protected $_aExistingColumns = array(
        'shop_id',
        'merchant_name',
        'merchant_status',
        'shop_name',
        'name',
        'currency',
        'type',
        'activation_status_elv',
        'activation_status_installment',
        'activation_status_invoice',
        'activation_status_prepayment',
        'amount_min_longrun',
        'b2b_pq_full',
        'b2b_pq_light',
        'b2b_elv',
        'b2b_installment',
        'b2b_invoice',
        'b2b_prepayment',
        'country_code_billing',
        'country_code_delivery',
        'delivery_address_pq_full',
        'delivery_address_pq_light',
        'delivery_address_elv',
        'delivery_address_installment',
        'delivery_address_invoice',
        'delivery_address_prepayment',
        'device_fingerprint_snippet_id',
        'eligibility_device_fingerprint',
        'eligibility_ratepay_elv',
        'eligibility_ratepay_installment',
        'eligibility_ratepay_invoice',
        'eligibility_ratepay_pq_full',
        'eligibility_ratepay_pq_light',
        'eligibility_ratepay_prepayment',
        'interest_rate_merchant_towards_bank',
        'interestrate_default',
        'interestrate_max',
        'interestrate_min',
        'min_difference_dueday',
        'month_allowed',
        'month_longrun',
        'month_number_max',
        'month_number_min',
        'payment_amount',
        'payment_firstday',
        'payment_lastrate',
        'rate_min_longrun',
        'rate_min_normal',
        'service_charge',
        'tx_limit_elv_max',
        'tx_limit_elv_min',
        'tx_limit_installment_max',
        'tx_limit_installment_min',
        'tx_limit_invoice_max',
        'tx_limit_invoice_min',
        'tx_limit_prepayment_max',
        'tx_limit_prepayment_min',
        'valid_payment_firstdays',
    );
    
    protected $_aBooleanConversionColumns = array(
        'b2b_pq_full',
        'b2b_pq_light',
        'b2b_elv',
        'b2b_installment',
        'b2b_invoice',
        'b2b_prepayment',
        'delivery_address_pq_full',
        'delivery_address_pq_light',
        'delivery_address_elv',
        'delivery_address_installment',
        'delivery_address_invoice',
        'delivery_address_prepayment',
        'eligibility_device_fingerprint',
        'eligibility_ratepay_elv',
        'eligibility_ratepay_installment',
        'eligibility_ratepay_invoice',
        'eligibility_ratepay_pq_full',
        'eligibility_ratepay_pq_light',
        'eligibility_ratepay_prepayment',
    );
    
    public function addRatePayConfig($aPayData) 
    {
        $oResource = Mage::getSingleton('core/resource');
        $oWrite = $oResource->getConnection('core_write');
        $sTable = $oResource->getTableName($this->_sTableName);

        $sQuery = " INSERT INTO {$sTable} (";
        
        $blFirst = true;
        foreach ($aPayData as $sKey => $sValue) {
            if(array_search($sKey, $this->_aExistingColumns) !== false) {
                if(!$blFirst) $sQuery .= ',';
                $sQuery .= $sKey;
                $blFirst = false;
            }
        }

        $sQuery .= ") VALUES (";
        
        $blFirst = true;
        foreach ($aPayData as $sKey => $sValue) {
            if(array_search($sKey, $this->_aExistingColumns) !== false) {
                if(!$blFirst) $sQuery .= ',';
                $sValue = $this->_getCorrectedValue($sKey, $sValue);
                $sQuery .= $oWrite->quote($sValue);
                $blFirst = false;
            }
        }
        
        $sQuery .= ")";

        $oWrite->query($sQuery);
    }
    
    protected function _getCorrectedValue($sKey, $sValue) 
    {
        if(array_search($sKey, $this->_aBooleanConversionColumns) !== false) {
            if(strtolower($sValue) == 'yes') {
                $sValue = 1;
            } elseif(strtolower($sValue) == 'no') {
                $sValue = 0;
            }
        }

        return $sValue;
    }

    /**
     * @api
     *
     * To be used in Form_Block, which has to display all ratePay types
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getAllConfigsByQuote(Mage_Sales_Model_Quote $quote)
    {
        if (empty($this->matchingConfigs)) {
            $configStore = $this->getConfigStore($quote->getStoreId());

            $this->matchingConfigs = $configStore->getPayment()->getMethodsForQuote($this->methodType, $quote);
        }

        return $this->matchingConfigs;
    }
    
    public function getRatePayConfigById($sRatePayShopId) 
    {
        $oResource = Mage::getSingleton('core/resource');
        $oRead = $oResource->getConnection('core_read');
        $sTable = $oResource->getTableName($this->_sTableName);

        $sQuery = "SELECT * FROM {$sTable} WHERE shop_id = {$oRead->quote($sRatePayShopId)} LIMIT 1";
        $aResult = $oRead->fetchAll($sQuery);
        if(is_array($aResult) && count($aResult) == 1) {
            return array_shift($aResult);
        }

        return false;
    }
    
    protected function _getQuote() 
    {
        /** @var $session Mage_Checkout_Model_Session */
        $oSession = Mage::getSingleton('checkout/session');
        if($this->getFactory()->getIsAdmin() === true) {
            $oSession = Mage::getSingleton('adminhtml/session_quote');
        }

        $oQuote = $oSession->getQuote();
        try {
            if (!$oQuote instanceof Mage_Sales_Model_Quote or !$oQuote->getId()) {
                $oQuote = $this->getInfoInstance()->getQuote();
            }            
        } catch (Exception $ex) {
            $oQuote = false;
        }

        return $oQuote;
    }
    
    protected function _getApplicableRatepayShopIds($oQuote)
    {
        $aShopIds = array();
        
        $configPayment = $this->getConfigForQuote($oQuote);
        $aRatepayConfig = $configPayment->getRatepayConfig();
        foreach ($aRatepayConfig as $aConfig) {
            if (isset($aConfig['ratepay_shopid'])) {
                $aShopIds[] = $aConfig['ratepay_shopid'];
            }
        }
        
        return $aShopIds;
    }
    
    public function getMatchingRatePayConfig() 
    {
        if($this->_aRatePayShopConfig === null) {
            $this->_aRatePayShopConfig = false;
            
            $oQuote = $this->_getQuote();
            if($oQuote) {
                $oResource = Mage::getSingleton('core/resource');
                $oRead = $oResource->getConnection('core_read');

                $sTable = $oResource->getTableName($this->_sTableName);
                $blAddressesAreEqual = $this->helper()->addressesAreEqual($oQuote->getBillingAddress(), $oQuote->getShippingAddress());

                $aRatepayShopIds = $this->_getApplicableRatepayShopIds($oQuote);

                $sQuery = " SELECT
                                shop_id
                            FROM
                                {$sTable}
                            WHERE 
                                shop_id IN ('".implode("','", $aRatepayShopIds)."') AND
                                {$oQuote->getGrandTotal()} BETWEEN tx_limit_invoice_min AND tx_limit_invoice_max AND
                                currency = {$oRead->quote($oQuote->getQuoteCurrencyCode())} AND
                                country_code_billing = {$oRead->quote($oQuote->getBillingAddress()->getCountryId())}";
                if($blAddressesAreEqual === false) {
                    $sQuery .= " AND delivery_address_invoice = 1 ";
                    $sQuery .= " AND country_code_delivery = {$oRead->quote($oQuote->getShippingAddress()->getCountryId())} ";
                }

                $sQuery .= " LIMIT 1";

                $sShopId = $oRead->fetchOne($sQuery);
                if($sShopId) {
                    $this->_aRatePayShopConfig = $this->getRatePayConfigById($sShopId);
                }
            }
        }

        return $this->_aRatePayShopConfig;
    }
    
    protected function _hasMatchingRatePayConfig() 
    {
        $aRatePayConfig = $this->getMatchingRatePayConfig();
        if($aRatePayConfig !== false) {
            return true;
        }

        return false;
    }
    
    public function isAvailable($quote = null) 
    {
        $blParentReturn = parent::isAvailable($quote);
        if($blParentReturn === true) {
            $blHasMatchingRatePayConfig = $this->_hasMatchingRatePayConfig();
            return $blHasMatchingRatePayConfig;
        }

        return $blParentReturn;
    }
    
    public function getApiResponseErrorMessage($response)
    {
        if((bool)$this->getConfig()->getShowCustomermessage() === true) {
            return $response->getCustomermessage();
        }

        return parent::getApiResponseErrorMessage($response);
    }

}