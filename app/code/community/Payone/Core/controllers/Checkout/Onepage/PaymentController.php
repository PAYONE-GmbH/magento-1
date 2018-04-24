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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Checkout_Onepage_PaymentController extends Payone_Core_Controller_Abstract
{
    /**
     * Payment has been canceled by user.
     *
     * Cancel order and redirect user to the shopping cart. Reactivate quote.
     */
    public function backAction()
    {
        try {
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->unsPayoneExternalCheckoutActive();
            $this->checkoutCancel(true);
        } catch (Exception $e) {
            $this->handleException($e);
        }
        $this->forwardToCart();
    }

    /**
     * Payment was successful and order will be saved.
     */
    public function successAction()
    {
        try {
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->unsPayoneExternalCheckoutActive();
            $success = $this->checkoutSucccess();
            if ($success === true) {
                // Payment is okay. Redirect to standard Magento success page:
                $this->_redirect('checkout/onepage/success', [
                    '_nosid'  => true,
                    '_secure' => Mage::app()->getStore()->isCurrentlySecure(),
                ]);
                return;
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }
        $this->forwardToCart();
    }

    /**
     * An error occured during the payment process.
     * Cancel order and redirect user to the shopping cart.
     */
    public function errorAction()
    {
        try {
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->unsPayoneExternalCheckoutActive();
            $this->checkoutCancel(true);
        } catch (Exception $e) {
            $this->handleException($e);
        }
        $this->forwardToCart();
    }

    protected function forwardToCart()
    {
        $this->getRequest()
            ->setInternallyForwarded()
            ->setRouteName('checkout')
            ->setControllerName('cart')
            ->setActionName('index')
            ->setDispatched(false);
    }

    protected function _relabelTransaction($sOldIncrementId, $sNewIncrementId, $sNewOrderId)
    {
        $oResource = Mage::getSingleton('core/resource');
        $oWrite = $oResource->getConnection('core_write');
        $sQuery = "  UPDATE 
                         {$oResource->getTableName('payone_core/transaction')} 
                     SET 
                         order_id = ".$oWrite->quote($sNewOrderId).",
                         reference = ".$oWrite->quote($sNewIncrementId)." 
                     WHERE reference = ".$oWrite->quote($sOldIncrementId);
        $oWrite->query($sQuery);
    }

    protected function _relabelOrderPayment($sOldIncrementId, $sNewOrderId)
    {
        $oResource = Mage::getSingleton('core/resource');
        $oRead = $oResource->getConnection('core_read');
        $oWrite = $oResource->getConnection('core_write');

        $sSelect = "SELECT 
                        a.last_trans_id
                    FROM
                        {$oResource->getTableName('sales/order_payment')} AS a
                    INNER JOIN
                        {$oResource->getTableName('sales/order')} AS b ON a.parent_id = b.entity_id AND b.increment_id = ".$oRead->quote($sOldIncrementId);
        $sLastTransId = $oRead->fetchOne($sSelect);

        $sQuery = " UPDATE
                        {$oResource->getTableName('sales/order_payment')}
                    SET
                        last_trans_id = ".$oWrite->quote($sLastTransId)."
                    WHERE
                        parent_id = ".$oWrite->quote($sNewOrderId);
        $oWrite->query($sQuery);
    }

    /**
     * @return bool
     */
    protected function checkoutSucccess()
    {
        // Get singleton of Checkout Session Model
        $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();

        // Load actors:
        $order = $this->getOrderFromCheckoutSession($checkoutSession);
        $quote = $this->getQuoteFromOrder($order);
        $helper = $this->helper();

        if ($order->getStatus() == Mage_Sales_Model_Order::STATE_CANCELED) {
            // Order was cancelled, reactivate quote, notify customer:
            $this->reactivateQuote($quote);

            if (!empty($checkoutSession->getData('order_got_canceled'))) {
                $checkoutSession->setData('creating_substitute_order', true);

                $quote->collectTotals();
                $service = Mage::getModel('sales/service_quote', $quote);
                $service->submitAll();

                $oNewOrder = $service->getOrder();
                $oNewOrder->setPayoneCancelSubstituteIncrementId($checkoutSession->getData('order_got_canceled'));
                $oNewOrder->save();

                $checkoutSession->setLastOrderId($oNewOrder->getId());
                $checkoutSession->setLastRealOrderId($oNewOrder->getIncrementId());
                $checkoutSession->getQuote()->setIsActive(false)->save();

                $this->_relabelTransaction($checkoutSession->getData('order_got_canceled'), $oNewOrder->getIncrementId(), $oNewOrder->getId());
                $this->_relabelOrderPayment($checkoutSession->getData('order_got_canceled'), $oNewOrder->getId());

                return true;
            }


            $message = $helper->__('The order has been canceled.');
            $checkoutSession->addError($message);
            return false;
        }

        // Load transaction status via order id, check for APPOINTED:
        $txStatus = $this->getFactory()->getModelTransaction();
        $txStatus->load($order->getIncrementId(), 'reference');

        if (!$txStatus->hasData() or !$txStatus->getId()) {// or !$txStatus->isAppointed()
            // Wrong or no transaction for this order, failure.
            $message = $helper->__('Sorry, your payment has not been confirmed by the payment provider.');
            $checkoutSession->addError($message);
            return false;
        }

        // Success!
        $checkoutSession->setLastSuccessQuoteId($quote->getId());

        return true;
    }

    /**
     * @param bool $reactivateQuote
     */
    protected function checkoutCancel($reactivateQuote = false)
    {
        // Get singleton of Checkout Session Model
        $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();

        // Load order
        $order = $this->getOrderFromCheckoutSession($checkoutSession);

        // Cancel order and add history comment:
        if ($order->canCancel()) {
            $order->cancel();
            $statusMessage = $this->helper()->__('The Payone transaction has been canceled.');
            $order->addStatusHistoryComment($statusMessage, Mage_Sales_Model_Order::STATE_CANCELED);
            $order->save();
            $checkoutSession->setData('order_got_canceled', $order->getIncrementId());
        }

        // Reactivate quote
        if ($reactivateQuote === true) {
            // Load quote
            $quote = $this->getQuoteFromOrder($order);
            $this->reactivateQuote($quote);
        }

        // Add error message to Magento checkout:
        $errorMessage = $this->helper()->__('The order has been canceled.');
        $checkoutSession->addError($errorMessage);
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

    /**
     * @param Mage_Checkout_Model_Session $session
     * @return Mage_Sales_Model_Order
     */
    protected function getOrderFromCheckoutSession(Mage_Checkout_Model_Session $session)
    {
        $lastOrderId = $session->getData('last_order_id');
        $orderId = base64_decode($this->getRequest()->getParam('reference'));
        $orderId = in_array($orderId, $session->getData('payone_pending_orders') ?: []) ? $orderId : $lastOrderId;
        $order = $this->getFactory()->getModelSalesOrder();
        $order->load($orderId);

        return $order;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return Mage_Sales_Model_Quote
     */
    protected function getQuoteFromOrder(Mage_Sales_Model_Order $order)
    {
        $quoteId = $order->getQuoteId();
        $quote = $this->getFactory()->getModelSalesQuote();
        $quote->load($quoteId);

        return $quote;
    }

    /**
     * @param Exception $exception
     */
    protected function handleException(Exception $exception)
    {
        // Log exceptions, any messages relevant to customer have been set to the session by service
        Mage::logException($exception);
    }

    protected function _getPaymentConfig()
    {
        $sPaymentConfigId = $this->getRequest()->getParam('payment_config_id');

        $oConfig = $this->getFactory()->helperConfig()->getConfigPaymentMethodById($sPaymentConfigId);

        return $oConfig;
    }

    protected function _getInstallmentDraftUrl()
    {
        $iDuration = $this->getRequest()->getParam('duration');

        $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
        $aDraftLinks = $checkoutSession->getInstallmentDraftLinks();

        if(isset($aDraftLinks[$iDuration])) {
            return $aDraftLinks[$iDuration];
        }

        return false;
    }

    public function getInstallmentDraftAction()
    {
        $sUrl = $this->_getInstallmentDraftUrl();
        if ($sUrl) {
            $oConfig = $this->_getPaymentConfig();
            $sUser = $oConfig->getInstallmentDraftUser();
            $sPassword = $oConfig->getInstallmentDraftPassword();

            $sDownloadUrl = str_ireplace('https://', 'https://'.$sUser.':'.$sPassword.'@', $sUrl);

            $sFilename = $this->helper()->__('terms-of-payment').'.pdf';

            $oContent = file_get_contents($sDownloadUrl);
            if($oContent) {
                $this->getResponse()
                    ->clearHeaders()
                    ->setHeader('Content-Type', 'application/pdf', true)
                    ->setHeader('Content-Disposition', 'attachment; filename="'.$sFilename.'"')
                    ->setBody($oContent);
                return;
            }
        }

        Mage::getSingleton('customer/session')->addError($this->helper()->__("Error trying to download the pdf"));
        $this->_redirect('');
    }
}
