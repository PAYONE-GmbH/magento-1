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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_MastercardMasterpassController extends Payone_Core_Controller_Abstract
{
    const CART_URL = 'checkout/cart';
    const REVIEW_URL = 'payone_core/mastercardMasterpass/review';
    const SUCCESS_REDIRECT_URL = 'payone_core/mastercardMasterpass/orderSuccess';

    /** @var  Payone_Core_Model_Service_Mastercard_Masterpass_Checkout */
    protected $checkoutService;

    protected function _construct()
    {
        parent::_construct();
        $this->checkoutService = Mage::getModel('payone_core/service_mastercard_masterpass_checkout');
    }

    public function initCheckoutAction()
    {
        $apiResponse = $this->checkoutService->setCheckout($this->getRequest()->get('quoteId'));
        $customerId = $this->getRequest()->get('customerId');
        if (!empty($customerId)) {
            $this->getCustomerSession()->setCustomerId($customerId);
            $this->getSession()->setData('customerId', $this->getCustomerSession()->getCustomerId());
        }
        else {
            $this->getCustomerSession()->clear();
            $this->getSession()->unsetData('customerId');
        }

        if ($apiResponse->getCode() == Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::INIT_CHECKOUT_OK_RESPONSE_CODE) {
            $this->initWorkOrderId($apiResponse->getData('workorderid'));
        }

        echo (string) $apiResponse;
    }

    public function errorAction()
    {
        $customerId = $this->getSession()->getData('customerId');
        if ($customerId) {
            $customer = $this->getFactory()->getModelCustomer()->load($customerId);
            $this->getCustomerSession()
                ->setCustomerAsLoggedIn($customer)
                ->setCustomerGroupId($customer->getGroupId());
        }

        $this->getCheckoutSession()->addError($this->__('An error occurred during the Masterpass Checkout.'));
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        $this->renderLayout();
    }

    public function cancelAction()
    {
        $customerId = $this->getSession()->getData('customerId');
        if ($customerId) {
            $customer = $this->getFactory()->getModelCustomer()->load($customerId);
            $this->getCustomerSession()
                ->setCustomerAsLoggedIn($customer)
                ->setCustomerGroupId($customer->getGroupId());
        }

        $this->getCheckoutSession()->addSuccess($this->__('The Masterpass Checkout has been canceled.'));
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        $this->renderLayout();
    }

    public function successAction()
    {
        $this->checkoutService->setWorkorderid($this->initWorkOrderId());
        $apiResponse = $this->checkoutService->getCheckout();

        if ($apiResponse->getCode() !== Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::FETCH_CHECKOUT_OK_RESPONSE_CODE) {
            $this->getCheckoutSession()
                ->addError($apiResponse->getData('message'));
            $this->_redirect($this->getCartUrl());
            return;
        }
        else {
            $request = new Payone_Core_Model_Service_Mastercard_Masterpass_Request_PrepareReviewOrderRequest();
            $request->setData($apiResponse->getData());
            $request->setQuoteId($this->getCheckoutSession()->getQuoteId());
            $this->checkoutService->prepareOrderReview($request);

            $this->_redirect($this->getReviewUrl());
            return;
        }
    }

    public function reviewAction()
    {
        try {
            $quoteId = $this->getCheckoutSession()->getQuoteId();
            $customerId = $this->getSession()->getData('customerId');
            if ($customerId) {
                $customer = $this->getFactory()->getModelCustomer()->load($customerId);
                $this->getCustomerSession()
                    ->setCustomerAsLoggedIn($customer)
                    ->setCustomerGroupId($customer->getGroupId());
            }

            $this->loadLayout();
            $this->_initLayoutMessages('checkout/session');

            /** @var Payone_Core_Block_Mastercard_Masterpass_Review $reviewBlock */
            $reviewBlock = $this->getLayout()->getBlock('mastercard.masterpass.review');
            $reviewBlock->setQuoteId($quoteId);

            /** @var Payone_Core_Block_Mastercard_Masterpass_Review_Billing $billing */
            $billing = $reviewBlock->getLayout()
                ->createBlock('payone_core/mastercard_masterpass_review_billing')
                ->setBlockId('mastercard.masterpass.review.billing')
                ->setTemplate('payone/core/mastercard/masterpass/review/billing.phtml');
            $billing->setQuoteId($quoteId);
            $billing->init();

            /** @var Payone_Core_Block_Mastercard_Masterpass_Review_Shipping $shipping */
            $shipping = $reviewBlock->getLayout()
                ->createBlock('payone_core/mastercard_masterpass_review_shipping')
                ->setBlockId('mastercard.masterpass.review.shipping')
                ->setTemplate('payone/core/mastercard/masterpass/review/shipping.phtml');
            $shipping->setQuoteId($quoteId);
            $shipping->init();

            /** @var Mage_Checkout_Block_Onepage_Shipping_Method_Available $shippingMethods */
            $shippingMethods = $reviewBlock->getLayout()
                ->createBlock('checkout/onepage_shipping_method_available')
                ->setBlockId('mastercard.masterpass.review.shippingmethods')
                ->setTemplate('checkout/onepage/shipping_method/available.phtml');

            /** @var Payone_Core_Block_Mastercard_Masterpass_Review_PaymentMethod $paymentMethod */
            $paymentMethod = $reviewBlock->getLayout()
                ->createBlock('payone_core/mastercard_masterpass_review_paymentMethod')
                ->setBlockId('mastercard.masterpass.review.paymentmethod')
                ->setTemplate('payone/core/mastercard/masterpass/review/payment_method.phtml');
            $paymentMethod->setQuoteId($quoteId);
            $paymentMethod->init();

            /** @var Payone_Core_Block_Mastercard_Masterpass_Review_Items $itemsReview */
            $itemsReview = $reviewBlock->getLayout()
                ->createBlock('payone_core/mastercard_masterpass_review_items')
                ->setBlockId('mastercard.masterpass.review.items')
                ->setTemplate('payone/core/mastercard/masterpass/review/items.phtml');
            $itemsReview->setQuoteId($quoteId);
            $itemsReview->init();

            /** @var Mage_Checkout_Block_Agreements $checkoutAgreements */
            $checkoutAgreements = $reviewBlock->getLayout()
                ->createBlock('checkout/agreements')
                ->setBlockId('mastercard.masterpass.review.checkoutagreements')
                ->setTemplate('checkout/onepage/agreements.phtml');

            $reviewBlock->setBilling($billing);
            $reviewBlock->setShipping($shipping);
            $reviewBlock->setShippingMethods($shippingMethods);
            $reviewBlock->setPaymentMethod($paymentMethod);
            $reviewBlock->setItemsReview($itemsReview);
            $reviewBlock->setCheckoutAgreements($checkoutAgreements);

            $this->renderLayout();
            return;
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('checkout/session')->addError($e->getMessage());
        }
        catch (Exception $e) {
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Unable to initialize Checkout review.')
            );
            Mage::logException($e);
        }

        $this->_redirect($this->getCartUrl());
        return;
    }

    public function chooseShippingMethodAction()
    {
        $request = new Payone_Core_Model_Service_Mastercard_Masterpass_Request_ChooseShippingMethodRequest();
        $request->setMethodCode($this->getRequest()->get('code'));
        $request->setQuoteId($this->getCheckoutSession()->getQuoteId());
        $response = $this->checkoutService->chooseShippingMethod($request);

        echo (string) $response;
    }

    public function placeOrderAction()
    {
        try {
            $request = new Payone_Core_Model_Service_Mastercard_Masterpass_Request_PlaceOrderRequest();
            $request->setQuoteId($this->getCheckoutSession()->getQuoteId());
            foreach ($this->getRequest()->get('agreement') as $agreement) {
                $request->addAgreement($agreement);
            }

            /** @var Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface $response */
            $response = $this->checkoutService->placeOrder($request);

            if ($response instanceof Payone_Core_Model_Service_Mastercard_Masterpass_Response_PlaceOrderErrorResponse) {
                Mage::log($response->getData('message'));

                $redirectUrl = Mage::getUrl($this->getReviewUrl());
            }
            else {
                $redirectUrl = Mage::getUrl($this->getSuccessUrl());
            }

            $response->setData('redirectUrl', $redirectUrl);
        }
        catch (Exception $ex) {
            $response = json_encode(
                array(
                    'code' => Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::PLACE_ORDER_ERROR_RESPONSE_CODE,
                    'data' => array(
                        'message' => $ex->getMessage()
                    )
                )
            );
        }
        echo $response;
    }

    protected function orderSuccessAction()
    {
        $session = $this->getCheckoutSession();
        $lastOrderId = $session->getLastOrderId();
        $session->clear();
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($lastOrderId)));
        $this->renderLayout();
    }

    /**
     * Set and get $workOrderId to the session
     *
     * @param null $workOrderId
     * @return $this
     */
    private function initWorkOrderId($workOrderId = null)
    {
        if (null !== $workOrderId) {
            if (false === $workOrderId) {
                // security measure to avoid unsetting token twice
                if ($this->getSession()->getWorkOrderId()) {
                    $this->getSession()->unsWorkOrderId();
                }
            } else {
                $this->getSession()->setWorkOrderId($workOrderId);
            }

            return $this;
        } else {
            return $this->getSession()->getWorkOrderId();
        }
    }

    /**
     * Payone session instance getter
     *
     * @return Payone_Core_Model_Session
     */
    private function getSession()
    {
        return Mage::getSingleton('payone_core/session');
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    private function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * @return Mage_Checkout_Model_Session
     */
    private function getCheckoutSession()
    {
        return $this->getFactory()->getSingletonCheckoutSession();
    }

    /**
     * @return string
     */
    private function getCartUrl()
    {
        return self::CART_URL;
    }

    /**
     * @return string
     */
    private function getReviewUrl()
    {
        return self::REVIEW_URL;
    }

    /**
     * @return string
     */
    private function getSuccessUrl()
    {
        return self::SUCCESS_REDIRECT_URL;
    }
}