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
            $oSession->unsPayoneIsRedirectedToPayPal();
            
            $this->checkoutCancel(true);
        } catch (Exception $e) {
            $this->handleException($e);
        }

        // Redirect customer to cart
        $this->_redirect('checkout/cart');
    }

    /**
     * @return mixed
     */
    public function successAction()
    {
        try {
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->unsPayoneIsRedirectedToPayPal();
            
            $success = $this->checkoutSucccess();

            if ($success === true) {
                // Payment is okay. Redirect to standard Magento success page:
                $this->_redirect(
                    'checkout/onepage/success', array(
                    '_nosid' => true,
                    '_secure' => Mage::app()->getStore()->isCurrentlySecure())
                );
                return;
            }
        } catch (Exception $e) {
            $this->handleException($e);
        }

        $this->_redirect('checkout/cart');
    }

    /**
     * An error occured during the payment process.
     * Cancel order and redirect user to the shopping cart.
     */
    public function errorAction()
    {
        try {
            $oSession = Mage::getSingleton('checkout/session');
            $oSession->unsPayoneIsRedirectedToPayPal();
            
            $this->checkoutCancel(true);
        } catch (Exception $e) {
            $this->handleException($e);
        }

        // Redirect customer to cart
        $this->_redirect('checkout/cart');
    }

    /**
     * @return bool
     */
    protected function checkoutSucccess()
    {
        // Get singleton of Checkout Session Model
        $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();

        // Load actors:
        $order = $this->getOrderByCheckoutSession($checkoutSession);
        $quote = $this->getQuoteByCheckoutSession($checkoutSession);
        $helper = $this->helper();

        if ($order->getStatus() == Mage_Sales_Model_Order::STATE_CANCELED) {
            // Order was cancelled, reactivate quote, notify customer:
            $this->reactivateQuote($quote);

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
        $order = $this->getOrderByCheckoutSession($checkoutSession);

        // Cancel order and add history comment:
        if ($order->canCancel()) {
            $order->cancel();
            $statusMessage = $this->helper()->__('The Payone transaction has been canceled.');
            $order->addStatusHistoryComment($statusMessage, Mage_Sales_Model_Order::STATE_CANCELED);
            $order->save();
        }

        // Reactivate quote
        if ($reactivateQuote === true) {
            // Load quote
            $quote = $this->getQuoteByCheckoutSession($checkoutSession);
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
            /* @note: Reset reserved_order_id, Magento up to and including version 1.7 has a bug in Mage_Sales_Model_Resource_Quote::isOrderIncrementIdUsed()
             * They cast the orderIncrementId to (int), which breaks the checkout/cart for all non-numerical incrementIds
             * (Causes Integrity Constraint Violation, because orderIncrementId already exists */
            $quote->setData('reserved_order_id', '');

            $quote->setIsActive(true);
            $quote->save();
        }
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
                    ->setHeader('Content-Type', 'application/pdf')
                    ->setHeader('Content-Disposition', 'attachment; filename="'.$sFilename.'"')
                    ->setBody($oContent);
                return;
            }
        }

        Mage::getSingleton('customer/session')->addError($this->helper()->__("Error trying to download the pdf"));
        $this->_redirect('');
    }
    
}