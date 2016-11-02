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
 * @package         Payone_Core_controllers
 * @subpackage
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert M�ller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_IframeController extends Payone_Core_Controller_Abstract
{
    
    /**
     * Handles Iframe payments for Payone
     *
     * @return null
     */
    public function showAction() 
    {
        $this->loadLayout();
        $sPaymentId = $this->getPaymentType();
        switch ($sPaymentId) {
        /*
            case 'payone_wallet':
                $this->getLayout()->getBlock('head')->setTitle($this->__('Payment with Yapital'));
                break;
        */
            case 'payone_creditcard_iframe':
                $this->getLayout()->getBlock('head')->setTitle($this->__('Payment with creditcard'));
                break;
        }

        $this->renderLayout();
    }
    
    public function getPaymentType() 
    {
        $oSession = Mage::getSingleton('checkout/session');
        $sPaymentMethod = $oSession->getPayonePaymentType();
        return $sPaymentMethod;
    }

}