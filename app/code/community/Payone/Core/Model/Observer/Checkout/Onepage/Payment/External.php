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
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Observer
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Observer_Checkout_Onepage_Payment_External extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @var Varien_Object
     */
    protected $settings = null;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function maintainCheckoutSession(Varien_Event_Observer $observer)
    {
        /** @var Mage_Core_Controller_Varien_Action $controller */
        $controller = $observer->getEvent()->getData('controller_action');
        $actionName = $controller->getFullActionName();
        if (false === stripos($actionName, 'payone_core_checkout_onepage_payment')) {
            /** @var Mage_Checkout_Model_Session $oSession */
            $oSession = Mage::getSingleton('checkout/session');
            if ($oSession->getPayoneExternalCheckoutActive() === true) {
                $oSession->unsPayoneExternalCheckoutActive();
                // Load last order by ID
                $orderId = $oSession->getLastOrderId();
                $oOrder = $this->getFactory()->getModelSalesOrder();
                $oOrder->load($orderId);
                if ($oOrder && $oOrder->getId()) {
                    // Cancel order and add history comment:
                    if ($oOrder->canCancel()) {
                        $oOrder->cancel();
                        $sMessage = $this->helper()->__('The Payone transaction has been canceled.');
                        $oOrder->addStatusHistoryComment($sMessage, Mage_Sales_Model_Order::STATE_CANCELED);
                        $oOrder->save();
                        $oSession->setData('order_got_canceled', $oOrder->getIncrementId());
                        // Add a notice to Magento checkout and
                        // prevent jumping forward in history:
                        $sNotice = $this->helper()->__('The order has been canceled.');
                        $oSession->setData('has_canceled_order_text', $sNotice);
                        $oSession->setData('has_canceled_order', true);
                        $oSession->addNotice($sNotice);
                        if (false === stripos($actionName, 'checkout_cart_index')) {
                            $controller->setRedirectWithCookieCheck('checkout/cart/index');
                        }
                    }
                    // Load quote
                    $quoteId = $oSession->getLastQuoteId();
                    $oQuote = $this->getFactory()->getModelSalesQuote();
                    $oQuote->load($quoteId);
                    if ($oQuote && $oQuote->getId()) {
                        $oQuote->setIsActive(1)
                            ->setReservedOrderId(null)
                            ->save();
                        /** @var Mage_Checkout_Model_Session $oSession */
                        $oSession = Mage::getSingleton('checkout/session');
                        $oSession->replaceQuote($oQuote)->unsetData('last_real_order_id');
                    }
                }
            }
        }
    }
}
