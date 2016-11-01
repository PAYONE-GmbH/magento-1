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
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Block_Checkout_Onepage_Payolution_Installmentplan extends Mage_Checkout_Block_Onepage_Abstract
{

    protected $_aInstallmentData;
    protected $_sCode;
    protected $_sPaymentConfigId;
    
    public function setInstallmentData($aInstallmentData)
    {
        $this->_aInstallmentData = $aInstallmentData;
    }
    
    public function getInstallmentData()
    {
        return $this->_aInstallmentData;
    }
    
    public function setCode($sCode)
    {
         $this->_sCode = $sCode;
    }
    
    public function getCode()
    {
        return $this->_sCode;
    }
    
    public function setPaymentConfigId($sPaymentConfigId)
    {
         $this->_sPaymentConfigId = $sPaymentConfigId;
    }
    
    public function getPaymentConfigId()
    {
        return $this->_sPaymentConfigId;
    }
    
    public function formatPrice($dPrice)
    {
        return number_format($dPrice, 2, ',', '');
    }
    
    public function getSelectLinkText($aInstallment)
    {
        $sText  = $this->formatPrice($aInstallment['installment']['1']['amount']).' ';
        $sText .= $aInstallment['currency'].' ';
        $sText .= Mage::helper('payone_core')->__('per month').' - ';
        $sText .= $aInstallment['duration'].' ';
        $sText .= Mage::helper('payone_core')->__('installments');
        return $sText;
    }
    
    public function getPaymentInfoText($sKey, $aInstallment, $aPayment)
    {
        $sAmount = $this->formatPrice($aPayment['amount']);
        $sDate = date('d.m.Y', strtotime($aPayment['due']));
        
        $sText  = $sKey.'. '.Mage::helper('payone_core')->__('Installment').': ';
        $sText .= $sAmount.' '.$aInstallment['currency'].' ';
        $sText .= '('.Mage::helper('payone_core')->__('due').' '.$sDate.')';
        return $sText;
    }
    
    public function getDraftDownloadLink($iInstallments)
    {
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();

        $url = Mage::getUrl(
            'payone_core/checkout_onepage_payment/getInstallmentDraft',
            array(
                '_nosid' => true,
                '_secure' => $isSecure,
                'duration' => $iInstallments,
                'payment_config_id' => $this->getPaymentConfigId(),
            )
        );
        return $url;
    }

}
