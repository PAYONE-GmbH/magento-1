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


class Payone_Core_Block_Adminhtml_System_Config_Form_Field_RatePayStoreIds
    extends Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract
{

    protected $_oRatePay = null;
    
    public function __construct() {
        parent::__construct();
        $this->setTemplate('payone/core/system/config/form/field/ratepay_shopids.phtml');
    }

    protected function _prepareToRender()
    {
        $this->addColumn('ratepay_shopid', array(
            'label' => Mage::helper('payone_core')->__('Shop-ID'),
            'style' => 'width:60px;',
        ));
        $this->addColumn('ratepay_currency', array(
            'label' => Mage::helper('payone_core')->__('Currency'),
            'style' => 'width:60px;',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('payone_core')->__('Add Shop-ID');
        parent::_prepareToRender();
    }
    
    protected function _getRatePayObject() {
        if($this->_oRatePay === null) {
            $this->_oRatePay = Mage::getModel('payone_core/payment_method_ratepay');
        }
        return $this->_oRatePay;
    }
    
    protected function _requestRatePayConfigFromApi($sRatePayShopId, $sCurrency) {
        $sMethodId = $this->getRequest()->get('id');
        $oConfigHelper = $this->getFactory()->helperConfig();
        $oConfig = $oConfigHelper->getConfigPaymentMethodById($sMethodId);
        $oService = $this->getFactory()->getServicePaymentGenericpayment($oConfig);
        $oMapper = $oService->getMapper();
        $oRequest = $oMapper->addRatePayParameters($sRatePayShopId, $sCurrency);
        $oResponse = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($oRequest);

        if($oResponse instanceof Payone_Api_Response_Genericpayment_Ok) {
            $aPayData = $oResponse->getPaydataArray();
            $aPayData['shop_id'] = $sRatePayShopId;
            
            $oRatePay = $this->_getRatePayObject();
            $oRatePay->addRatePayConfig($aPayData);
            return $aPayData;
        }
        return false;
    }
    
    public function getRatePayShopConfig($sRatePayShopId, $sCurrency) {
        $sRatePayShopId = trim($sRatePayShopId);
        $oRatePay = $this->_getRatePayObject();
        $aRatePayConfig = $oRatePay->getRatePayConfigById($sRatePayShopId);
        if(!$aRatePayConfig) {
            $aRatePayConfig = $this->_requestRatePayConfigFromApi($sRatePayShopId, $sCurrency);
        }
        return $aRatePayConfig;
    }

}