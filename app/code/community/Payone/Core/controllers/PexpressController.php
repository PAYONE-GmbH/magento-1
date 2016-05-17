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
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

class Payone_Core_PexpressController extends Payone_Core_Controller_Abstract
{
    /**
     * @var Payone_Core_Model_Service_Paypal_Express_Checkout
     */
    protected $_checkout = null;

    /**
     * @var Payone_Core_Model_Config_Payment_Method
     */
    protected $_config = null;

    /**
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote = false;

    /**
     * Instantiate config
     */
//    protected function _construct()
//    {
//        parent::_construct();
//        $methodInstance = Mage::helper('payment')->getMethodInstance(Payone_Core_Model_System_Config_PaymentMethodCode::WALLET);
//        $this->_config = $methodInstance->getConfigForQuote($this->_getQuote());
//    }

    public function startAction()
    {
        try {
            $this->_initCheckout();

            if ($this->_getQuote()->getIsMultiShipping()) {
                $this->_getQuote()->setIsMultiShipping(false);
                $this->_getQuote()->removeAllAddresses();
            }

            $this->_checkout->savePayment(array(
                'method' => Payone_Core_Model_System_Config_PaymentMethodCode::WALLET,
                'payone_wallet_type' => Payone_Api_Enum_WalletType::PAYPAL_EXPRESS,
                'payone_config_payment_method_id' => $this->_config->getId()
            ));

            $this->_checkout->start();

            if (($workOrderId = $this->_checkout->getWorkOrderId()) && ($url = $this->_checkout->getRedirectUrl())) {
                $this->_initWorkOrderId($workOrderId);
                $this->getResponse()->setRedirect($url);
                return;
            }

        } catch (Mage_Core_Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getCheckoutSession()->addError($this->__('Unable to start Express Checkout.'));
            Mage::logException($e);
        }

        $this->_redirect('checkout/cart');
    }

    public function returnAction()
    {
        try {
            $this->_initCheckout();
            $workOrderId = $this->_initWorkOrderId();

            $this->_checkout->returnFromPaypal($workOrderId);
            $this->_redirect('*/*/review', array(
                '_nosid' => true,
                '_secure' => Mage::app()->getStore()->isCurrentlySecure())
            );
            return;
        } catch (Mage_Core_Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getCheckoutSession()->addError($this->__('Unable to start Express Checkout.'));
            Mage::logException($e);
        }
        $this->_redirect('checkout/cart');
    }

    /**
     * Review order after returning from PayPal
     */
    public function reviewAction()
    {
        try {
            $this->_initCheckout();
            $this->_checkout->prepareOrderReview();

            $this->loadLayout();
            $this->_initLayoutMessages('payone_core/session');
            $reviewBlock = $this->getLayout()->getBlock('paypal.express.review');
            $reviewBlock->setQuote($this->_getQuote());
            $reviewBlock->getChild('details')->setQuote($this->_getQuote());
            if ($reviewBlock->getChild('shipping_method')) {
                $reviewBlock->getChild('shipping_method')->setQuote($this->_getQuote());
            }
            $this->renderLayout();
            return;
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('checkout/session')->addError($e->getMessage());
        }
        catch (Exception $e) {
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Unable to initialize Express Checkout review.')
            );
            Mage::logException($e);
        }
        $this->_redirect('checkout/cart');
    }

    /**
     * Submit the order
     */
    public function placeOrderAction()
    {
        try {
            $requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();
            if ($requiredAgreements) {
                $postedAgreements = array_keys($this->getRequest()->getPost('agreement', array()));
                if (array_diff($requiredAgreements, $postedAgreements)) {
                    Mage::throwException(Mage::helper('payone_core')->__('Please agree to all the terms and conditions before placing the order.'));
                }
            }

            $this->_initCheckout();
            $this->_checkout->place($this->_initWorkOrderId());

            // prepare session to success or cancellation page
            $session = $this->_getCheckoutSession();
            $session->clearHelperData();

            // "last successful quote"
            $quoteId = $this->_getQuote()->getId();
            $session->setLastQuoteId($quoteId)->setLastSuccessQuoteId($quoteId);

            // an order may be created
            $order = $this->_checkout->getOrder();
            if ($order) {
                $session->setLastOrderId($order->getId())
                    ->setLastRealOrderId($order->getIncrementId());
                // as well a billing agreement can be created
                $agreement = $this->_checkout->getBillingAgreement();
                if ($agreement) {
                    $session->setLastBillingAgreementId($agreement->getId());
                }
            }

            // recurring profiles may be created along with the order or without it
            $profiles = $this->_checkout->getRecurringPaymentProfiles();
            if ($profiles) {
                $ids = array();
                foreach($profiles as $profile) {
                    $ids[] = $profile->getId();
                }
                $session->setLastRecurringProfileIds($ids);
            }

            $this->_initWorkOrderId(false); // no need in WorkOrderId anymore
            $this->_redirect('checkout/onepage/success', array(
                '_nosid' => true,
                '_secure' => Mage::app()->getStore()->isCurrentlySecure())
            );
            return;
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()->addError($this->__('Unable to place the order.'));
            Mage::logException($e);
        }
        $this->_redirect('*/*/review', array(
            '_nosid' => true,
            '_secure' => Mage::app()->getStore()->isCurrentlySecure())
        );
    }

    /**
     * Instantiate quote and checkout
     * @throws Mage_Core_Exception
     */
    private function _initCheckout()
    {
        $quote = $this->_getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->getResponse()->setHeader('HTTP/1.1','403 Forbidden');
            Mage::throwException(Mage::helper('payone_core')->__('Unable to initialize Payone Express Checkout.'));
        }
        $methodInstance = Mage::helper('payment')->getMethodInstance(Payone_Core_Model_System_Config_PaymentMethodCode::WALLET);
        $this->_config = $methodInstance->getConfigForQuote($quote);

        $this->_checkout = Mage::getModel('payone_core/service_paypal_express_checkout', array(
            'quote'  => $quote,
            'config' => $this->_config,
        ));
    }

    /**
     * Set and get $workOrderId to the session
     *
     * @param int $workOrderId
     */
    private function _initWorkOrderId($workOrderId = null)
    {
        if(null !== $workOrderId) {
            if (false === $workOrderId) {
                // security measure for avoid unsetting token twice
                if (!$this->_getSession()->getWorkOrderId()) {
                    Mage::throwException($this->__('PayPal Express Checkout Token does not exist.'));
                }
                $this->_getSession()->unsWorkOrderId();
            } else {
                $this->_getSession()->setWorkOrderId($workOrderId);
            }
            return $this;
        } else {
            return $this->_getSession()->getWorkOrderId();
        }
    }

    /**
     * Return checkout session object
     *
     * @return Mage_Checkout_Model_Session
     */
    private function _getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Return checkout quote object
     *
     * @return Mage_Sale_Model_Quote
     */
    private function _getQuote()
    {
        if (!$this->_quote) {
            $this->_quote = $this->_getCheckoutSession()->getQuote();
        }
        return $this->_quote;
    }

    /**
     * Payone session instance getter
     *
     * @return Payone_Core_Model_Session
     */
    private function _getSession()
    {
        return Mage::getSingleton('payone_core/session');
    }

}