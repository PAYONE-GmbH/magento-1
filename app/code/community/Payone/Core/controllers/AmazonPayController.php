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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_AmazonPayController extends Payone_Core_Controller_Abstract
{
    /**
     * @var \Mage_Customer_Model_Session
     */
    protected $_customerSession = null;

    /**
     * @var \Payone_Core_Model_Config_Payment_Method
     */
    protected $_config = null;

    /**
     * @var \Mage_Sales_Model_Quote
     */
    protected $_quote = false;

    public function checkoutAction()
    {
        //$this->getResponse()->setHeader('Content-Type', 'application/json');
        //$this->getResponse()->setBody('{"result":"OK"}');

        try {
            $this->_initCheckout();

            $this->loadLayout();
            $this->_initLayoutMessages('payone_core/session');
            $reviewBlock = $this->getLayout()->getBlock('amazon.pay.checkout');
            $reviewBlock->setQuote($this->_getQuote());
            /*
            $reviewBlock->getChild('details')->setQuote($this->_getQuote());
            if ($reviewBlock->getChild('shipping_method')) {
                $reviewBlock->getChild('shipping_method')->setQuote($this->_getQuote());
            }
            */

            $this->renderLayout();
            return;
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('checkout/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Unable to initialize Payone Amazon Checkout foo.')
            );
            Mage::logException($e);
        }

        $this->_redirect('checkout/cart');
    }

    /**
     * Instantiate quote and checkout
     * @throws \Mage_Core_Exception
     */
    private function _initCheckout()
    {
        $quote = $this->_getQuote();
        if (!$quote->hasItems() || $quote->getData('has_error')) {
            $this->getResponse()->setHeader('HTTP/1.1', '403 Forbidden');
            Mage::throwException(Mage::helper('payone_core')->__('Unable to initialize Payone Amazon Checkout.'));
        }
        $this->_customerSession = Mage::getSingleton('customer/session');
        /** @var \Mage_Payment_Helper_Data $paymentHelper */
        $paymentHelper = Mage::helper('payment');
        /** @var \Payone_Core_Model_Payment_Method_AmazonPay $paymentMethod */
        $paymentMethod = $paymentHelper->getMethodInstance(
            Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY
        );
        $this->_config = $paymentMethod->getConfigForQuote($quote);
    }

    /**
     * Set and get $workOrderId to the session
     * @param null $workOrderId
     * @return $this
     * @throws \Mage_Core_Exception
     */
    private function _initWorkOrderId($workOrderId = null)
    {
        if (null !== $workOrderId) {
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
     * @return \Mage_Checkout_Model_Session
     */
    private function _getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Return checkout quote object
     *
     * @return \Mage_Sales_Model_Quote
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
     * @return \Payone_Core_Model_Session
     */
    private function _getSession()
    {
        /** @var \Payone_Core_Model_Session $session */
        $session = Mage::getSingleton('payone_core/session');
        return $session;
    }

}
