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
 * @subpackage      Service_Paydirekt_Express
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */
class Payone_Core_Model_Service_Paydirekt_Express_Checkout
{
    const API_VERSION = '3.10';
    const CURRENCY = 'EUR';

    const CHECKOUT_TYPE_FOR_AUTH = 'directsale';
    const CHECKOUT_TYPE_FOR_PREAUTH = 'order';

    const DEFAULT_SHIPPING_SKU = 'Shipping';
    const DEFAULT_DISCOUNT_SKU = 'Discount';

    const PAYONE_PAYDIREKT_CHECKOUT_WORKORDERID = 'payone_paydirekt_checkout_workorderid';

    const PRIVACY_POLICY_COOKIE_RESTRICTION_MODE_URL = 'privacy-policy-cookie-restriction-mode/';

    /** @var Mage_Customer_Model_Session */
    protected $customerSession;

    /** @var Mage_Checkout_Model_Session */
    protected $checkoutSession;

    /** @var Payone_Core_Model_Factory */
    protected $factory;

    /** @var Mage_Sales_Model_Quote */
    protected $quote;

    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $configPayment;

    /** @var Payone_Core_Model_Service_Payment_Genericpayment */
    protected $service;

    /** @var Payone_Api_Service_Payment_Genericpayment */
    protected $serviceApi;

    /**
     * @param array $params
     * @throws Exception
     */
    public function __construct($params = array())
    {
        if (isset($params['quote']) && $params['quote'] instanceof Mage_Sales_Model_Quote) {
            $this->quote = $params['quote'];
            $this->quote->save();
        } else {
            throw new Exception('Quote instance is required.');
        }

        if (isset($params['config']) && $params['config'] instanceof Payone_Core_Model_Config_Payment_Method) {
            $this->configPayment = $params['config'];
        } else {
            throw new Exception('Config instance is required.');
        }

        $this->service = $this->getFactory()->getServicePaymentGenericpayment($this->configPayment);
        $this->serviceApi = $this->getFactory()->getServiceApiPaymentGenericpayment();

        $this->customerSession = Mage::getSingleton('customer/session');
        $this->checkoutSession = Mage::getSingleton('checkout/session');
    }

    /**
     * @param Payone_Core_Model_Service_Paydirekt_Express_Request_InitCheckoutRequest $request
     *
     * @return Payone_Core_Model_Service_Paydirekt_Express_Response_InitCheckoutErrorResponse|Payone_Core_Model_Service_Paydirekt_Express_Response_InitCheckoutOkResponse
     */
    public function initCheckout(Payone_Core_Model_Service_Paydirekt_Express_Request_InitCheckoutRequest $request)
    {
        $this->checkoutSession->unsLastQuoteId();
        $apiRequest = $this->_mapInitCheckoutParameters();

        $apiResponse = $this->serviceApi->request($apiRequest);

        if ($apiResponse->getStatus() == 'ERROR') {
            $response = new Payone_Core_Model_Service_Paydirekt_Express_Response_InitCheckoutErrorResponse();
            $response->setCode($apiResponse->getErrorcode());
            $response->setData('message', $apiResponse->getCustomermessage());
            $response->setData('errorMessage', $apiResponse->getErrormessage());

             return $response;
        }

        $response = new Payone_Core_Model_Service_Paydirekt_Express_Response_InitCheckoutOkResponse();
        $response->setRedirectUrl($apiResponse->getRedirecturl());
        $response->setWorkorderId($apiResponse->getWorkorderId());
        $data = $apiResponse->getRawResponse();
        if (isset($data['add_paydata[workorderid]'])) {
            $response->setData(
                'add_paydata[workorderid]',
                $data['add_paydata[workorderid]']
            );
        }

        return $response;
    }

    /**
     * @param Payone_Core_Model_Service_Paydirekt_Express_Request_GetStatusRequest $request
     * @return Payone_Core_Model_Service_Paydirekt_Express_Response_GetStatusErrorResponse|Payone_Core_Model_Service_Paydirekt_Express_Response_GetStatusOkResponse
     */
    public function getStatus(Payone_Core_Model_Service_Paydirekt_Express_Request_GetStatusRequest $request)
    {
        $apiRequest = $this->_mapGetStatusParameters($request->getWorkorderId());

        $apiResponse = $this->serviceApi->request($apiRequest);

        if ($apiResponse->getStatus() == 'ERROR') {
            $response = new Payone_Core_Model_Service_Paydirekt_Express_Response_GetStatusErrorResponse();
            $response->setCode($apiResponse->getErrorcode());
            $response->setData('message', $apiResponse->getCustomermessage());
            $response->setData('errorMessage', $apiResponse->getErrormessage());

            return $response;
        }

        $payData = $apiResponse->getPaydataArray();
        $response = new Payone_Core_Model_Service_Paydirekt_Express_Response_GetStatusOkResponse();
        $response->setWorkorderId($apiResponse->getWorkorderid());
        foreach ($payData as $key => $value) {
            $response->setData($key, $value);
        }

        return $response;
    }

    /**
     * @param string|null $workorderId
     * @return Payone_Api_Request_PaydirektExpressGetStatus|Payone_Api_Request_PaydirektExpressSetCheckout
     */
    protected function _mapCommonParameters($workorderId = null)
    {
        if(null === $workorderId) {
            /** @var Payone_Api_Request_PaydirektExpressSetCheckout $request */
            $request = $this->service->getMapper()
                ->getPaydirektExpressInitCheckoutRequest();
        } else {
            /** @var Payone_Api_Request_PaydirektExpressGetStatus $request */
            $request = $this->service->getMapper()
                ->getPaydirektExpressGetStatusRequest($this->quote, $workorderId);
            $request->setWorkorderId($workorderId);
        }
        $request->setAid($this->configPayment->getAid());
        $request->setAmount($this->quote->getGrandTotal());
        $request->setApiVersion(self::API_VERSION);
        $request->setClearingtype(Payone_Enum_ClearingType::WALLET);
        $request->setCurrency(self::CURRENCY);

        return $request;
    }

    /**
     * @return Payone_Api_Request_PaydirektExpressSetCheckout
     * @throws Exception
     */
    protected function _mapInitCheckoutParameters()
    {
        /** @var Payone_Api_Request_PaydirektExpressSetCheckout $request */
        $request = $this->_mapCommonParameters();

        // PAYDATA //
        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYDIREKT_ECS_SET_EXPRESSCHECKOUT)
            )
        );

        $authorization = $this->getHelperConfig()
            ->getConfigPaymentMethodByType(
                $this->quote->getStoreId(),
                Payone_Core_Model_System_Config_PaymentMethodType::WALLETPAYDIREKTEXPRESS
            )->getRequestType();

        if ($authorization === 'authorization') {
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'type', 'data' => self::CHECKOUT_TYPE_FOR_AUTH)
                )
            );
        } else {
            $paydata->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'type', 'data' => self::CHECKOUT_TYPE_FOR_PREAUTH)
                )
            );
        }

        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'web_url_shipping_terms', 'data' => Mage::getBaseUrl() . self::PRIVACY_POLICY_COOKIE_RESTRICTION_MODE_URL)
            )
        );

        $request->setPaydata($paydata);
        // END PAYDATA //

        $request->setWallet(
            new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet(
                array(
                    'wallettype' => Payone_Api_Enum_WalletType::PAYDIREKTEXPRESS,
                    'successurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/success'),
                    'backurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/cancel'),
                    'errorurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/error')
                )
            )
        );

        /** @var Payone_Core_Model_Carrier_PaydirektExpress $carrier */
        $carrier = Mage::getModel('payone_core/carrier_paydirektExpress');

        //Rate request doesn't matter here, but is mandatory parameter in parent method's signature
        /** @var Mage_Shipping_Model_Rate_Request $rateRequest */
        $rateRequest = Mage::getModel('shipping/rate_request');
        $shippingMethods = $carrier->collectRates($rateRequest)->getRatesByCarrier('paydirektexpress');

        if (empty($shippingMethods)) {
            throw new Exception(
                Mage::helper('payone_core')->__('No shipping detail could be found for this transaction.')
            );
        }

        /** @var Mage_Shipping_Model_Rate_Result_Method $shippingMethod */
        $shippingMethod = $shippingMethods[0];
        $shippingAddress = $this->quote->getShippingAddress()
            ->setCountryId('DE')
            ->setShippingMethod($shippingMethod->getCarrier() . '_' . $shippingMethod->getMethod())
            ->setShippingAmount($shippingMethod->getPrice())
            ->setBaseShippingAmount($shippingMethod->getPrice())
            ->setCollectShippingRates(1);
        $shippingAddress->collectTotals();
        $shippingAddress->save();
        $this->quote->setShippingAddress($shippingAddress);

        $this->quote->setTotalsCollectedFlag(false);
        $this->quote->collectTotals();

        $invoicing = new Payone_Api_Request_Parameter_Invoicing_Transaction();
        // Quote items:
        foreach ($this->quote->getItemsCollection() as $key => $itemData) {
            /** @var $itemData Mage_Sales_Model_Quote_Item */
            $number = $itemData->getQty();
            if ($number <= 0 || $itemData->getParentItemId()) {
                continue; // Do not map items with zero quantity
            }

            $params['it'] = Payone_Api_Enum_InvoicingItemType::GOODS;
            $params['id'] = $itemData->getSku();
            $params['pr'] = $this->getItemPrice($itemData);
            $params['no'] = $number;
            $params['de'] = $itemData->getName();
            $params['va'] = $itemData->getTaxPercent();

            $item = new Payone_Api_Request_Parameter_Invoicing_Item();
            $item->init($params);
            $invoicing->addItem($item);
        }

        $configMiscShipping = $this->getHelperConfig()->getConfigMisc($this->quote->getStoreId())->getShippingCosts();
        $sku = $configMiscShipping->getSku();
        if (!empty($sku)) {
            $sku = $this->getFactory()->helper()->__(self::DEFAULT_SHIPPING_SKU);
        }

        $params['it'] = Payone_Api_Enum_InvoicingItemType::SHIPMENT;
        $params['id'] = $sku;
        $params['pr'] = $shippingMethod['price'];
        $params['no'] = 1;
        $params['de'] = 'Shipping Costs';
        $params['va'] = 0;

        $item = new Payone_Api_Request_Parameter_Invoicing_Item();
        $item->init($params);
        $invoicing->addItem($item);

        // Discounts
        $discountAmount = $this->quote->getShippingAddress()->getDiscountAmount();
        if ($discountAmount != 0) {
            $configMiscDiscount = $this->getHelperConfig()->getConfigMisc()->getDiscount();
            $sku = $configMiscDiscount->getSku();
            $description = $configMiscDiscount->getDescription();
            if (empty($sku)) {
                $sku = $this->getFactory()->helper()->__(self::DEFAULT_DISCOUNT_SKU);
            }

            if (empty($description)) {
                $description = $this->getFactory()->helper()->__(self::DEFAULT_DISCOUNT_SKU);
            }

            $params['it'] = Payone_Api_Enum_InvoicingItemType::VOUCHER;
            $params['id'] = $sku;
            $params['de'] = $description;
            $params['no'] = 1;
            $params['pr'] = $discountAmount;
            $params['va'] = 0;

            $item = new Payone_Api_Request_Parameter_Invoicing_Item();
            $item->init($params);

            $invoicing->addItem($item);
        }

        $request->setInvoicing($invoicing);

        // Recollect totals, as amounts got updated (shipping)
        // Update request amount with new GrandTotal
        $this->quote->setTotalsCollectedFlag(false)
            ->collectTotals()
            ->save();
        $request->setAmount($this->quote->getGrandTotal());

        return $request;
    }

    /**
     * @param string $workorderId
     * @return Payone_Api_Request_PaydirektExpressGetStatus
     */
    protected function _mapGetStatusParameters($workorderId)
    {
        /** @var Payone_Api_Request_PaydirektExpressGetStatus $request */
        $request = $this->_mapCommonParameters($workorderId);

        // PAYDATA //
        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $paydata->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYDIREKT_ECS_GET_EXPRESSCHECKOUTDETAILS)
            )
        );
        $request->setPaydata($paydata);
        // END PAYDATA //

        $request->setWallettype(Payone_Api_Enum_WalletType::PAYDIREKTEXPRESS);

        return $request;
    }

    /**
     * @param Payone_Core_Model_Service_Paydirekt_Express_Request_PrepareReviewOrderRequest $request
     */
    public function prepareOrderReview(Payone_Core_Model_Service_Paydirekt_Express_Request_PrepareReviewOrderRequest $request)
    {
        /** @var Payone_Core_Helper_Config $configHelper */
        $configHelper = Mage::helper('payone_core/config');
        $paymentMethodCode = \Payone_Core_Model_System_Config_PaymentMethodCode::WALLETPAYDIREKTEXPRESS;

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($request->getQuoteId());
        $paymentConfig = $configHelper->getConfigPaymentMethodForQuote(
            \Payone_Core_Model_System_Config_PaymentMethodType::WALLETPAYDIREKTEXPRESS,
            $quote
        );

        $billing = $quote->getBillingAddress();
        $billing->setData('should_ignore_validation', true);
        $billing->setData('payment_method', $paymentMethodCode);
        $billing->setFirstname($request->getBillingFirstname())
            ->setLastname($request->getBillingLastname())
            ->setEmail($request->getBillingEmail())
            ->setPostcode($request->getBillingZip())
            ->setCity($request->getBillingCity())
            ->setCountryId($request->getBillingCountry())
            ->setStreetFull(
                $request->getBillingStreetname() . ' ' . $request->getBillingStreetnumber() . ', '
                . $request->getBillingAdditionaladdressinformation()
            );
        if (!empty($request->getBuyerEmail())) {
            $billing->setEmail($request->getBuyerEmail());
        }
        $billing->save();

        $shipping = $quote->getShippingAddress();
        $shipping->setSameAsBilling(false);
        $shipping->setData('should_ignore_validation', true);
        $shipping->setData('payment_method', $paymentMethodCode);
        $shipping->setCollectShippingRates(true);
        $shipping->setFirstname($request->getShippingFirstname())
            ->setLastname($request->getShippingLastname())
            ->setEmail($request->getShippingEmail())
            ->setPostcode($request->getShippingZip())
            ->setCity($request->getShippingCity())
            ->setCountryId($request->getShippingCountry())
            ->setStreetFull(
                $request->getShippingStreetname() . ' ' . $request->getShippingStreetnumber() . ', '
                . $request->getShippingAdditionaladdressinformation()
            );
        $shipping->collectShippingRates();
        $shipping->save();

        $payment = $quote->getPayment();
        $payment->setMethod($paymentMethodCode);
        $payment->setData('payone_config_payment_method_id', $paymentConfig->getId());
        $payment->setAdditionalInformation(self::PAYONE_PAYDIREKT_CHECKOUT_WORKORDERID, $request->getWorkorderid());
        $payment->save();

        $coupon = $this->checkoutSession->getData('cart_coupon_code');
        if (!empty($coupon)) {
            $this->quote->setCouponCode($coupon);
        }

        $quote->collectTotals();
        $quote->save();
    }

    /**
     * @param Payone_Core_Model_Service_Paydirekt_Express_Request_PlaceOrderRequest $request
     * @return Payone_Core_Model_Service_Paydirekt_Express_ResponseInterface
     */
    public function placeOrder(Payone_Core_Model_Service_Paydirekt_Express_Request_PlaceOrderRequest $request)
    {
        /** @var \Mage_Checkout_Helper_Data $checkoutHelper */
        $checkoutHelper = Mage::helper('checkout');
        $requiredAgreements = $checkoutHelper->getRequiredAgreementIds();
        if ($requiredAgreements) {
            $postedAgreements = $request->getAgreements();
            $diff = array_diff($requiredAgreements, $postedAgreements);
            if ($diff) {
                $agreementsErrorMessage = Mage::helper('payone_core')
                    ->__('Please agree to all the terms and conditions before placing the order.');
                Mage::throwException($agreementsErrorMessage);
            }
        }

        try{
            /** @var Mage_Sales_Model_Quote $quote */
            $quote = Mage::getModel('sales/quote')->load($request->getQuoteId());
            $quote->setTotalsCollectedFlag(true);
            $quote->getBillingAddress()
                ->setData('should_ignore_validation', true);
            $quote->getShippingAddress()
                ->setData('should_ignore_validation', true);

            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customer = Mage::getSingleton('customer/session')->getCustomer();
                $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
                $quote->setCustomerId($customer->getId())
                    ->setCustomerIsGuest(false)
                    ->setCustomerGroupId($customerGroupId);
            } else {
                $quote->setCustomerId(null)
                    ->setCustomerIsGuest(true)
                    ->setCustomerEmail($quote->getBillingAddress()->getEmail())
                    ->setCustomerGroupId(\Mage_Customer_Model_Group::NOT_LOGGED_IN_ID);
            }

            $quote->save();

            /** @var Mage_Sales_Model_Service_Quote $cartService */
            $cartService = Mage::getModel('sales/service_quote', $quote);
            $cartService->submitAll();

            $this->checkoutSession->setData('last_quote_id', $quote->getId());
            $this->checkoutSession->setData('last_success_quote_id', $quote->getId());
            $this->checkoutSession->clearHelperData();
            $order = $cartService->getOrder();

            if ($order) {
                Mage::dispatchEvent(
                    'checkout_type_onepage_save_order_after',
                    ['order' => $order, 'quote' => $quote]
                );
                if ($order->getCanSendNewEmailFlag()) {
                    try {
                        $order->queueNewOrderEmail();
                    } catch (Exception $e) {
                        Mage::logException($e);
                    }
                }
                // add order information to the session
                $this->checkoutSession->setData('last_order_id', $order->getId());
                $this->checkoutSession->setData('last_real_order_id', $order->getIncrementId());
                // as well a billing agreement can be created
                $agreement = $order->getPayment()->getBillingAgreement();
                if ($agreement) {
                    $this->checkoutSession->setData('last_billing_agreement_id', $agreement->getId());
                }

                // Set quote as inactive, as order was created successfully
                $quote->setIsActive(false)->save();
            }

            Mage::dispatchEvent(
                'checkout_submit_all_after',
                ['order' => $order, 'quote' => $quote, 'recurring_profiles' => []]
            );

            $response = new Payone_Core_Model_Service_Paydirekt_Express_Response_PlaceOrderOkResponse();
        }
        catch (\Exception $ex) {
            $response = new Payone_Core_Model_Service_Paydirekt_Express_Response_PlaceOrderErrorResponse();
            $response->setCode($ex->getCode());
            $response->setData('message', $ex->getMessage());
        }

        return $response;
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function getHelperConfig()
    {
        return $this->getFactory()->helperConfig();
    }

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
     * @param Mage_Sales_Model_Quote_Item $itemData
     * @return float
     */
    private function getItemPrice(Mage_Sales_Model_Quote_Item $itemData)
    {
        if ($this->configPayment->getCurrencyConvert()) {
            return $itemData->getBasePriceInclTax();
        }

        return $itemData->getPriceInclTax();
    }
}
