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
 * @author          Robert M�ller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Block_Checkout_Onepage_Payment_Iframe extends Mage_Core_Block_Template {
    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/checkout/iframe.phtml');
    }

    public function getIframeUrl() {
        $oSession = Mage::getSingleton('checkout/session');
        $sIframeUrl = $oSession->getPayoneIframeUrl();
        return $sIframeUrl;
    }
    
    public function getPaymentType() {
        $oSession = Mage::getSingleton('checkout/session');
        $sPaymentMethod = $oSession->getPayonePaymentType();
        return $sPaymentMethod;
    }
    
    public function getIframeHeight() {
        $sPaymentId = $this->getPaymentType();
        switch ($sPaymentId) {
            case 'payone_wallet':
                $sHeight = 277;
                break;
            case 'payone_creditcard_iframe':
                $sHeight = 700;
                break;
        }
        return $sHeight;
    }
    
    public function getIframeWidth() {
        $sPaymentId = $this->getPaymentType();
        switch ($sPaymentId) {
            case 'payone_wallet':
                $sWidth = false;
                break;
            case 'payone_creditcard_iframe':
                $sWidth = 360;
                break;
        }
        return $sWidth;
    }
    
    public function getIframeStyle() {
        $sPaymentId = $this->getPaymentType();
        switch ($sPaymentId) {
            case 'payone_wallet':
                $sStyle = "margin: 20px auto;position: relative;width:100%;border:0;";
                break;
            case 'payone_creditcard_iframe':
                $sStyle = "border:0;margin-top:20px;";
                break;
        }
        return $sStyle;
    }
    
    public function getIframeHeader() {
        $sPaymentId = $this->getPaymentType();
        switch ($sPaymentId) {
        /*
            case 'payone_wallet':
                $sHeader = $this->__('Payment with Yapital');
                break;
        */
            case 'payone_creditcard_iframe':
                $sHeader = $this->__('Payment with creditcard');
                break;
        }
        return $sHeader;
    }
    
    public function getIframeText() {
        $sPaymentId = $this->getPaymentType();
        switch ($sPaymentId) {
        /*
            case 'payone_wallet':
                $sText = $this->__('There are 2 possibilities to pay with Yapital. Either you open the Yapital-app, select payment from the app-menu and scan the QR-code or you click the orange LOGIN button and log in on the next site, using your Yapital-login-data. After a successful transaction you will be redirected back to the shop.');
                $sText .= '<br><br>';
                $sText .= $this->__('Every transaction is handled in realtime with your Yapital-account. Your bankdata is not transmitted by Yapital, only the necessary transactiondata.');
                break;
        */
            case 'payone_creditcard_iframe':
                $sText = false;
                break;
        }
        return $sText;
    }
    
}