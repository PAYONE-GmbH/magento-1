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
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

require_once 'Mage' . DS . 'Checkout' . DS . 'controllers' . DS . 'CartController.php';

class Payone_Core_Checkout_CartController extends Mage_Checkout_CartController
{
    
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;
    
    /** @var Payone_Core_Helper_Data */
    protected $helper = null;
    
    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = Mage::getModel('payone_core/factory');
        }

        return $this->factory;
    }
    
    /**
     * @return Payone_Core_Helper_Data
     */
    protected function helper()
    {
        if ($this->helper === null) {
            $this->helper = $this->getFactory()->helper();
        }

        return $this->helper;
    }
    
    /**
     * @param Mage_Checkout_Model_Session $session
     * @return Mage_Sales_Model_Order
     */
    protected function getOrderByCheckoutSession(Mage_Checkout_Model_Session $session)
    {
        $orderId = $session->getLastOrderId();

        $order = $this->getFactory()->getModelSalesOrder();
        $order->load($orderId);

        return $order;
    }
    
    /**
     * @param Mage_Checkout_Model_Session $session
     * @return Mage_Sales_Model_Quote
     */
    protected function getQuoteByCheckoutSession(Mage_Checkout_Model_Session $session)
    {
        $quoteId = $session->getLastQuoteId();

        $quote = $this->getFactory()->getModelSalesQuote();
        $quote->load($quoteId);

        return $quote;
    }
    
    /**
     * @param Mage_Sales_Model_Quote $quote
     */
    protected function reactivateQuote(Mage_Sales_Model_Quote $quote)
    {
        if ($quote->getId()) {
            /**
             * Reset reserved_order_id - Magento up to and including version 1.7 has a
             * bug in Mage_Sales_Model_Resource_Quote::isOrderIncrementIdUsed() -
             * orderIncrementId is being casted to (int), which breaks the checkout/cart
             * for all non-numerical incrementIds (which also causes integrity constraint
             * violations, because the resulting orderIncrementIds "already exist")
             */
            $quote->setIsActive(1)
                ->setReservedOrderId(null)
                ->save();
            /** @var Mage_Checkout_Model_Session $oSession */
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->replaceQuote($quote)->unsetData('last_real_order_id');
        }
    }
}
