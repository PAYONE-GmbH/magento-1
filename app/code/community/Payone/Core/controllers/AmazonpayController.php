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
     * @var \Payone_Core_Model_Service_Amazon_Pay_Checkout|null
     */
    protected $_checkout = null;

    /**
     * @var \Payone_Core_Model_Config_Payment_Method|null
     */
    protected $_config = null;

    /**
     * @var \Mage_Sales_Model_Quote|null
     */
    protected $_quote = null;

    public function checkoutAction()
    {
        try {
            $this->_initCheckout();
            $this->loadLayout()->_initLayoutMessages('payone_core/session');
            $this->getLayout()->getBlock('amazon.pay.checkout')->setData([
                'config' => $this->_getConfig(),
                'quote' => $this->_getQuote(),
            ]);
            $this->renderLayout();
            return;
        } catch (Mage_Core_Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getCheckoutSession()->addError($this->__('Unable to initialize PAYONE Amazon Checkout.'));
            Mage::logException($e);
        }
        $this->_redirect('checkout/cart');
    }

    public function progressAction()
    {
        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'application/json');
        $params = $this->getRequest()->getParams();
        $checkoutSteps = [
            'confirmSelection',
            'chooseMethod',
            'placeOrder',
        ];
        try {
            if (!in_array($params['currentStep'], $checkoutSteps)) {
                Mage::throwException(
                    "Invalid value for parameter `currentStep`" .
                    " -> \"{$params['currentStep']}\"."
                );
            }
            $this->_initCheckout();
            $this->_initWorkOrder();
            $object = $this->_checkout;
            $method = $params['currentStep'];
            $params['controller'] = $this;
            $result = call_user_func([$object, $method], $params);
            $response->setBody(json_encode($result));
            return;
        } catch (\Mage_Core_Exception $e) {
            $response->setBody(json_encode(['errorMessage' => $e->getMessage(), 'successful' => false]));
            return;
        } catch (\Exception $e) {
            $errorMessage = $this->__('Unable to proceed with PAYONE Amazon Checkout.');
            $response->setBody(json_encode(['errorMessage' => $errorMessage, 'successful' => false]));
            Mage::logException($e);
            return;
        }
    }

    /**
     * @return \Payone_Core_AmazonPayController
     * @throws \Mage_Core_Exception
     */
    private function _initCheckout()
    {
        $this->_quote = $this->_getCheckoutSession()->getQuote();
        if (!$this->_quote->hasItems() || $this->_quote->getData('has_error')) {
            Mage::throwException($this->__('Your basket is empty or has become invalid.'));
        }
        /** @var \Mage_Payment_Helper_Data $paymentHelper */
        $paymentHelper = Mage::helper('payment');
        /** @var \Payone_Core_Model_Payment_Method_AmazonPay $paymentMethod */
        $paymentMethod = $paymentHelper->getMethodInstance(
            Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY
        );
        $this->_config = $paymentMethod->getConfigForQuote($this->_quote);
        $this->_checkout = Mage::getModel(
            'payone_core/service_amazon_pay_checkout',
            [
                'quote'  => $this->_quote,
                'config' => $this->_config,
            ]
        );
        return $this;
    }

    private function _initWorkOrder()
    {
        $workOrderId = $this->_getSession()->getData('WorkOrderId');
        $workOrderId = $this->_checkout->initWorkOrder($workOrderId);
        $this->_getSession()->setData('WorkOrderId', $workOrderId);
        return $workOrderId;
    }

    /**
     * @return \Mage_Checkout_Model_Session
     */
    private function _getCheckoutSession()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');
        return $session;
    }

    /**
     * @return \Payone_Core_Model_Config_Payment_Method
     */
    private function _getConfig()
    {
        return $this->_config;
    }

    /**
     * @return \Mage_Sales_Model_Quote
     */
    private function _getQuote()
    {
        return $this->_quote;
    }

    /**
     * @return \Payone_Core_Model_Session
     */
    private function _getSession()
    {
        /** @var \Payone_Core_Model_Session $session */
        $session = Mage::getSingleton('payone_core/session');
        return $session;
    }

}
