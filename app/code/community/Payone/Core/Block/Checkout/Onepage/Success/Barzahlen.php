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
 * @link            http://www.fatchip.com
 */

class Payone_Core_Block_Checkout_Onepage_Success_Barzahlen
    extends Mage_Core_Block_Template
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * after block is rendered we should clear relevant checkout session fields to avoid
     * falsy interpretation on further checkout passes
     *
     * @param string $html
     * @return string
     */
    protected function _afterToHtml($html)
    {
        $this->getCheckoutSession()->unsPayoneBarzahlenHtml();
        return parent::_afterToHtml($html);
    }

    /**
     * @return Mage_Checkout_Model_Session
     */
    protected function getCheckoutSession()
    {
        return $this->getFactory()->getSingletonCheckoutSession();
    }

    /**
     * @return bool
     */
    public function isBarzahlenOrder()
    {
        $orderId = $this->getOrderId();
        if (!$orderId) {
            return false;
        }

        $order = $this->getFactory()->getModelSalesOrder();
        $order->load($orderId);

        if ($order->getPayment()->getMethod() != Payone_Core_Model_System_Config_PaymentMethodCode::BARZAHLEN) {
            return false;
        }

        if(!$this->getBarzahlenHtml()) {
            return false;
        }

        return true;
    }
    
    public function getBarzahlenHtml() 
    {
        return $this->getCheckoutSession()->getPayoneBarzahlenHtml();
    }

    /**
     * @return int
     */
    protected function getOrderId()
    {
        return $this->getCheckoutSession()->getLastOrderId();
    }

    /**
     * @return \Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }

}