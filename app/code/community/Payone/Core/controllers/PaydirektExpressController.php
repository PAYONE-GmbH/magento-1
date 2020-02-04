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
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_PaydirektExpressController extends Payone_Core_Controller_Abstract
{
    const CART_URL = 'checkout/cart';
    const REVIEW_URL = 'payone_core/paydirektExpress/review';
    const SUCCESS_REDIRECT_URL = 'payone_core/paydirektExpress/orderSuccess';

    /** @var Payone_Core_Model_Service_Paydirekt_Express_Checkout */
    protected $_checkoutService = null;
    /** @var Payone_Core_Model_Config_Payment_Method */
    protected $_config = null;
    /** @var Mage_Sales_Model_Quote */
    protected $_quote = false;

    /**
     * Start the checkout process on paydirekt side, through a request
     */
    public function initCheckoutAction()
    {
        try {
            $request = new Payone_Core_Model_Service_Paydirekt_Express_Request_InitCheckoutRequest(
                $this->_getCheckoutSession()->getQuoteId()
            );

            $response = $this->_getCheckoutService()->initCheckout($request);

            if ($response instanceof Payone_Core_Model_Service_Paydirekt_Express_Response_InitCheckoutOkResponse) {
                $this->_getSession()->setWorkOrderId($response->getWorkorderId());
                $this->_getCheckoutSession()->setPayoneExternalCheckoutActive(true);
                if ($response->getRedirectUrl()) {
                    $this->_redirectUrl($response->getRedirectUrl());
                    return;
                }
            } elseif ($response instanceof Payone_Core_Model_Service_Paydirekt_Express_Response_InitCheckoutErrorResponse) {
                $this->_getCheckoutSession()->addError($response->getData('message'));
            }
        } catch (Mage_Core_Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getCheckoutSession()->addError($this->__('Unable to start Paydirekt Express Checkout.'));
            Mage::logException($e);
        }

        $this->_redirect(self::CART_URL);
    }

    /**
     * Handles the return from the initialisation of the checkout
     */
    public function successAction()
    {
        try {
            $request = new Payone_Core_Model_Service_Paydirekt_Express_Request_GetStatusRequest(
                $this->_getCheckoutSession()->getQuoteId(),
                $this->_getSession()->getWorkOrderId()
            );

            $response = $this->_getCheckoutService()->getStatus($request);

            if ($response instanceof Payone_Core_Model_Service_Paydirekt_Express_Response_GetStatusOkResponse) {
                $request = new Payone_Core_Model_Service_Paydirekt_Express_Request_PrepareReviewOrderRequest();
                $request->setData($response->getData());
                $request->setQuoteId($this->_getCheckoutSession()->getQuoteId());
                $this->_getCheckoutService()->prepareOrderReview($request);

                $this->_redirect(self::REVIEW_URL);
                return;
            } elseif ($response instanceof Payone_Core_Model_Service_Paydirekt_Express_Response_GetStatusErrorResponse) {
                $this->_getCheckoutSession()->addError($response->getData('message'));
            }

        } catch (Mage_Core_Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getCheckoutSession()->addError($this->__('Unable to proceed with Paydirekt Express Checkout.'));
            Mage::logException($e);
        }

        $this->_redirect(self::CART_URL);
    }

    public function errorAction()
    {
        $this->_getCheckoutSession()->unsPayoneExternalCheckoutActive();
        $this->_getCheckoutSession()->addError($this->__('An error occured during the Paydirekt Express Checkout.'));
        $this->_redirect(self::CART_URL);
    }

    public function cancelAction()
    {
        $this->_getCheckoutSession()->unsPayoneExternalCheckoutActive();
        $this->_getCheckoutSession()->addSuccess($this->__('The Paydirekt Express Checkout has been canceled.'));
        $this->_redirect(self::CART_URL);
    }

    /**
     * Prepares and displays the review page to check the order before confirmation
     */
    public function reviewAction()
    {
        try {
            $quoteId = $this->_getCheckoutSession()->getQuoteId();
            $customerId = $this->_getSession()->getData('customerId');
            if ($customerId) {
                $customer = $this->getFactory()->getModelCustomer()->load($customerId);
                $this->_getCustomerSession()
                    ->setCustomerAsLoggedIn($customer)
                    ->setCustomerGroupId($customer->getGroupId());
            }

            $this->loadLayout();
            $this->_initLayoutMessages('checkout/session');

            /** @var Payone_Core_Block_Paydirekt_Express_Review $reviewBlock */
            $reviewBlock = $this->getLayout()->getBlock('paydirekt.express.review');
            $reviewBlock->setQuoteId($quoteId);

            /** @var Payone_Core_Block_Paydirekt_Express_Review_Billing $billing */
            $billing = $reviewBlock->getLayout()
                ->createBlock('payone_core/paydirekt_express_review_billing')
                ->setBlockId('paydirekt.express.review.billing')
                ->setTemplate('payone/core/paydirekt/express/review/billing.phtml');
            $billing->setQuoteId($quoteId);
            $billing->init();

            /** @var Payone_Core_Block_Paydirekt_Express_Review_Shipping $shipping */
            $shipping = $reviewBlock->getLayout()
                ->createBlock('payone_core/paydirekt_express_review_shipping')
                ->setBlockId('paydirekt.express.review.shipping')
                ->setTemplate('payone/core/paydirekt/express/review/shipping.phtml');
            $shipping->setQuoteId($quoteId);
            $shipping->init();

            /** @var Payone_Core_Block_Paydirekt_Express_Review_ShippingMethod $shippingMethod */
            $shippingMethod = $reviewBlock->getLayout()
                ->createBlock('payone_core/paydirekt_express_review_shippingMethod')
                ->setBlockId('paydirekt.express.review.shippingmethod')
                ->setTemplate('payone/core/paydirekt/express/review/shipping_method.phtml');
            $shippingMethod->setQuoteId($quoteId);
            $shippingMethod->init();

            /** @var Payone_Core_Block_Paydirekt_Express_Review_PaymentMethod $paymentMethod */
            $paymentMethod = $reviewBlock->getLayout()
                ->createBlock('payone_core/paydirekt_express_review_paymentMethod')
                ->setBlockId('paydirekt.express.review.paymentmethod')
                ->setTemplate('payone/core/paydirekt/express/review/payment_method.phtml');
            $paymentMethod->setQuoteId($quoteId);
            $paymentMethod->init();

            /** @var Payone_Core_Block_Paydirekt_Express_Review_Items $itemsReview */
            $itemsReview = $reviewBlock->getLayout()
                ->createBlock('payone_core/paydirekt_express_review_items')
                ->setBlockId('paydirekt.express.review.items')
                ->setTemplate('payone/core/paydirekt/express/review/items.phtml');
            $itemsReview->setQuoteId($quoteId);
            $itemsReview->init();

            /** @var Mage_Checkout_Block_Agreements $checkoutAgreements */
            $checkoutAgreements = $reviewBlock->getLayout()
                ->createBlock('checkout/agreements')
                ->setBlockId('paydirekt.express.review.checkoutagreements')
                ->setTemplate('checkout/onepage/agreements.phtml');

            $reviewBlock->setBilling($billing);
            $reviewBlock->setShipping($shipping);
            $reviewBlock->setShippingMethod($shippingMethod);
            $reviewBlock->setPaymentMethod($paymentMethod);
            $reviewBlock->setItemsReview($itemsReview);
            $reviewBlock->setCheckoutAgreements($checkoutAgreements);

            $this->renderLayout();
            return;
        } catch (Mage_Core_Exception $e) {
            $this->_getCheckoutSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getCheckoutSession()->addError($this->__('Unable to proceed with Paydirekt Express Checkout review.'));
            Mage::logException($e);
        }

        $this->_redirect(self::CART_URL);
        return;
    }

    /**
     * Submits the order
     */
    public function placeOrderAction()
    {
        try {
            $request = new Payone_Core_Model_Service_Paydirekt_Express_Request_PlaceOrderRequest();
            $request->setQuoteId($this->_getCheckoutSession()->getQuoteId());
            foreach ($this->getRequest()->get('agreement') as $agreement) {
                $request->addAgreement($agreement);
            }

            /** @var Payone_Core_Model_Service_Paydirekt_EXpress_ResponseInterface $response */
            $response = $this->_getCheckoutService()->placeOrder($request);

            if ($response instanceof Payone_Core_Model_Service_Paydirekt_Express_Response_PlaceOrderErrorResponse) {
                Mage::log($response->getData('message'));

                $redirectUrl = Mage::getUrl(self::REVIEW_URL);
            }
            else {
                $this->_getCheckoutSession()->unsPayoneExternalCheckoutActive();
                $redirectUrl = Mage::getUrl(self::SUCCESS_REDIRECT_URL);
            }

            $response->setData('redirectUrl', $redirectUrl);
        }
        catch (Exception $ex) {
            $response = json_encode(
                array(
                    'code' => Payone_Core_Model_Service_Paydirekt_Express_ResponseInterface::PLACE_ORDER_ERROR_RESPONSE_CODE,
                    'data' => array(
                        'message' => $ex->getMessage()
                    )
                )
            );
        }

        echo $response;
    }

    /**
     * Handles the successful placement of the order, redirecting to success page eventually
     */
    protected function orderSuccessAction()
    {
        $session = $this->_getCheckoutSession();
        $lastOrderId = $session->getLastOrderId();
        $session->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }

    /**
     * @return Payone_Core_Model_Service_Paydirekt_Express_Checkout
     */
    protected function _getCheckoutService()
    {
        if (!$this->_checkoutService) {
            if (!$this->_getQuote()->hasItems() || $this->_getQuote()->getHasError()) {
                $this->getResponse()->setHeader('HTTP/1.1', '403 Forbidden');
                Mage::throwException(Mage::helper('payone_core')->__('Unable to initialize Paydirekt Express Checkout.'));
            }

            $this->_checkoutService = Mage::getModel(
                'payone_core/service_paydirekt_express_checkout', array(
                    'quote'  => $this->_getQuote(),
                    'config' => $this->_getConfig(),
                )
            );
        }

        return $this->_checkoutService;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method
     */
    protected function _getConfig()
    {
        if (!$this->_config) {
            $quote = $this->_getQuote();
            $methodInstance = Mage::helper('payment')->getMethodInstance(Payone_Core_Model_System_Config_PaymentMethodCode::WALLETPAYDIREKTEXPRESS);
            $this->_config = $methodInstance->getConfigForQuote($quote);
        }

        return $this->_config;
    }

    /**
     * Return checkout quote object
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        if (!$this->_quote) {
            $this->_quote = $this->_getCheckoutSession()->getQuote();
        }

        return $this->_quote;
    }

    /**
     * Return checkout session object
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Payone session instance getter
     *
     * @return Payone_Core_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('payone_core/session');
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    protected function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }
}
