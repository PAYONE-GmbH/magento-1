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
 * @subpackage      Handler
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Handler_Cancellation extends Payone_Core_Model_Handler_Abstract
{
    /**
     * payoneExternalCheckoutActive should not be present at any time during the shopping-process
     * It is set before the client is redirected to payment-provider and is removed after he returned
     * So if the flag is set when he is somewhere else on the shop, he escaped the payment-process in an unknown way
     * Therefore the payment is cancelled
     *
     * @param Mage_Core_Controller_Front_Action|null $controller
     *
     * @return void
     * @throws Exception
     */
    public function handle(Mage_Core_Controller_Front_Action $controller = null)
    {
        /** @var Mage_Checkout_Model_Session $oSession */
        $oSession = Mage::getSingleton('checkout/session');
        if ($oSession->getPayoneExternalCheckoutActive() === true) {
            $oSession->unsPayoneExternalCheckoutActive();
            $oSession->unsPaydirektExpressCheckoutActive();

            // Load last order by ID
            $orderId = $oSession->getLastOrderId();
            $oOrder  = $this->getFactory()->getModelSalesOrder();
            $oOrder->load($orderId);
            if ($oOrder && $oOrder->getId()) {
                // Cancel order and add history comment:
                if ($oOrder->canCancel()) {
                    $txStatus = $this->getFactory()->getModelTransactionStatus();
                    $txStatus->load($oOrder->getIncrementId(), 'reference');

                    if ($txStatus->getId() && $txStatus->isAppointed()) {
                        // Returning here since we cannot cancel an order that has been appointed,
                        // but maybe the TxStatus has not been processed yet.
                        // So this is a double check to prevent failures later in the process during invoice generation.
                        // Cancelled orders cannot be invoiced by default
                        // The re-activation of the quote is also skipped, since the order most likely has finished
                        // and the customer might just be hitting the Browser back button
                        return;
                    }

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
                    if ($controller !== null && !$controller instanceof Mage_Checkout_CartController) {
                        $controller->setRedirectWithCookieCheck('checkout/cart/index');
                    }
                }
                // Load quote
                $quoteId = $oSession->getLastQuoteId();

                $oQuote = $oSession->getQuote();

                //load old quote and duplicate it - don't reuse the old one to prevent cart-manipulation
                $oOldQuote = $this->getFactory()->getModelSalesQuote();
                $oOldQuote->load($quoteId);
                if ($oOldQuote && $oOldQuote->getId()) {
                    $oQuote->merge($oOldQuote);
                    $oQuote->collectTotals();
                    $oQuote->save();
                }
            }
        }
    }
}
