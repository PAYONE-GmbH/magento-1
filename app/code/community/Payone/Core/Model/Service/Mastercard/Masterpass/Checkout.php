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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Model_Service_Mastercard_Masterpass_Checkout
{
    const API_REQUEST_ACTION_KEY = 'action';
    const API_REQUEST_ORIGIN_URL_KEY = 'originURL';

    const PAYONE_MASTERPASS_CHECKOUT_WORKORDERID = 'payone_masterpass_checkout_workorderid';

    const PAYONE_MASTERPASS_GETCHECKOUT_ERROR_URL = 'payone_core/mastercardMasterpass/error';
    const PAYONE_MASTERPASS_GETCHECKOUT_CANCEL_URL = 'payone_core/mastercardMasterpass/cancel';
    const PAYONE_MASTERPASS_GETCHECKOUT_SUCCESS_URL = 'payone_core/mastercardMasterpass/success';

    const API_VERSION = 3.10;

    /** @var Mage_Sales_Model_Quote */
    protected $quote;

    /** @var Mage_Customer_Model_Session  */
    protected $customerSession;

    /** @var Mage_Checkout_Model_Session  */
    protected $checkoutSession;

    /** @var string */
    protected $workorderid = '';

    /** @var Payone_Core_Model_Factory */
    protected $factory;

    /**
     * @param array $params
     * @throws Exception
     */
    public function __construct($params = array())
    {
        $this->customerSession = Mage::getSingleton('customer/session');
        $this->checkoutSession = Mage::getSingleton('checkout/session');
    }

    /**
     * @param $quoteId
     * @return Payone_Core_Model_Service_Mastercard_Masterpass_Response_InitCheckoutErrorResponse|Payone_Core_Model_Service_Mastercard_Masterpass_Response_InitCheckoutOkResponse
     */
    public function setCheckout($quoteId)
    {
        $this->setQuoteById($quoteId);
        $configHelper = $this->getFactory()->helperConfig();
        $paymentMethodConfig = $configHelper->getConfigPaymentMethodByType(
            $this->getQuote()->getStoreId(),
            Payone_Core_Model_System_Config_PaymentMethodType::MASTERPASS
        );

        if (!$paymentMethodConfig) {
            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_InitCheckoutErrorResponse();
            $response->setCode(Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::INIT_CHECKOUT_ERROR_RESPONSE_CODE);
            $response->setData('message', 'No payment configuration found !');

            return $response;
        }

        $baseUrl = Mage::getBaseUrl();

        $apiRequest = $this->getFactory()->getRequestMasterpassSetCheckout($paymentMethodConfig->toArray());
        $apiRequest->setClearingtype(Payone_Enum_ClearingType::MASTERPASS);
        $apiRequest->setAmount($this->getQuote()->getGrandTotal());
        $apiRequest->setCurrency('EUR');
        $apiRequest->setWallettype(Payone_Api_Enum_WalletType::MASTERPASS);
        $apiRequest->setSuccessurl($baseUrl . self::PAYONE_MASTERPASS_GETCHECKOUT_SUCCESS_URL);
        $apiRequest->setErrorurl($baseUrl . self::PAYONE_MASTERPASS_GETCHECKOUT_ERROR_URL);
        $apiRequest->setBackurl($baseUrl . self::PAYONE_MASTERPASS_GETCHECKOUT_CANCEL_URL);
        $apiRequest->setApiVersion(self::API_VERSION);

        $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array(
                    'key' => self::API_REQUEST_ACTION_KEY,
                    'data' => Payone_Api_Enum_GenericpaymentAction::MASTERPASS_SET_CHECKOUT
                )
            )
        );
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array(
                    'key' => self::API_REQUEST_ORIGIN_URL_KEY,
                    'data' => $baseUrl . 'checkout/cart/'
                )
            )
        );
        $apiRequest->setPaydata($payData);

        $apiResponse = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($apiRequest);

        if ($apiResponse instanceof Payone_Api_Response_Genericpayment_Ok) {
            $payData = $apiResponse->getPaydataArray();

            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_InitCheckoutOkResponse();
            $response->setCode(Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::INIT_CHECKOUT_OK_RESPONSE_CODE);
            $response->setData('token', $payData['token']);
            $response->setData('merchantcheckoutid', $payData['merchantcheckoutid']);
            $response->setData('callbackurl', $payData['callbackurl']);
            $response->setData('allowedcardtypes', $payData['allowedcardtypes']);
            $response->setData('version', $payData['version']);
            $response->setData('workorderid', $apiResponse->getWorkorderid());
        }
        else {
            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_InitCheckoutErrorResponse();
            $response->setCode($apiResponse->getErrorcode());
            $response->setData('message', $apiResponse->getErrormessage());
        }

        return $response;
    }

    /**
     * @return Payone_Core_Model_Service_Mastercard_Masterpass_Response_FetchCheckoutErrorResponse|Payone_Core_Model_Service_Mastercard_Masterpass_Response_FetchCheckoutOkResponse
     */
    public function getCheckout()
    {
        $configHelper = $this->getFactory()->helperConfig();
        $paymentMethodConfig = $configHelper->getConfigPaymentMethodByType(
            $this->getQuote()->getStoreId(),
            Payone_Core_Model_System_Config_PaymentMethodType::MASTERPASS
        );

        if (!$paymentMethodConfig) {
            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_FetchCheckoutErrorResponse();
            $response->setCode(Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::FETCH_CHECKOUT_ERROR_RESPONSE_CODE);
            $response->setData('message', 'No payment configuration found !');

            return $response;
        }

        $apiRequest = $this->getFactory()->getRequestMasterpassGetCheckout($paymentMethodConfig->toArray());
        $apiRequest->setClearingtype(Payone_Enum_ClearingType::MASTERPASS);
        $apiRequest->setAmount($this->getQuote()->getGrandTotal());
        $apiRequest->setCurrency('EUR');
        $apiRequest->setWallettype(Payone_Api_Enum_WalletType::MASTERPASS);
        $apiRequest->setWorkorderId($this->workorderid);
        $apiRequest->setApiVersion(self::API_VERSION);

        $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array(
                    'key' => self::API_REQUEST_ACTION_KEY,
                    'data' => Payone_Api_Enum_GenericpaymentAction::MASTERPASS_GET_CHECKOUT
                )
            )
        );
        $apiRequest->setPaydata($payData);

        $apiResponse = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($apiRequest);

        if ($apiResponse instanceof Payone_Api_Response_Genericpayment_Ok) {
            $payData = $apiResponse->getPaydataArray();

            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_FetchCheckoutOkResponse();
            $response->setCode(Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::FETCH_CHECKOUT_OK_RESPONSE_CODE);
            foreach ($payData as $key => $value) {
                $response->setData($key, $value);
            }
            $response->setData('workorderid', $apiResponse->getWorkorderid());
        }
        else {
            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_FetchCheckoutErrorResponse();
            $response->setCode($apiResponse->getErrorcode());
            $response->setData('message', $apiResponse->getErrormessage());
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getWorkorderid()
    {
        return $this->workorderid;
    }

    /**
     * @param string $workorderid
     */
    public function setWorkorderid($workorderid)
    {
        $this->workorderid = $workorderid;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    private function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = Mage::getModel('payone_core/factory');
        }

        return $this->factory;
    }

    /**
     * Return checkout quote object
     *
     * @return Mage_Sales_Model_Quote
     */
    private function getQuote()
    {
        if (!$this->quote) {
            $this->quote = $this->getCheckoutSession()->getQuote();
        }
        return $this->quote;
    }

    /**
     * Set the quote using parameter Id
     */
    private function setQuoteById($quoteId)
    {
        $this->getCheckoutSession()->setQuoteId($quoteId);
    }

    /**
     * Return checkout session object
     *
     * @return Mage_Checkout_Model_Session
     */
    private function getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * @param Payone_Core_Model_Service_Mastercard_Masterpass_Request_PrepareReviewOrderRequest $request
     */
    public function prepareOrderReview($request)
    {
        /** @var Payone_Core_Helper_Config $configHelper */
        $configHelper = Mage::helper('payone_core/config');
        $paymentMethodCode = \Payone_Core_Model_System_Config_PaymentMethodCode::MASTERPASS;

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($request->getQuoteId());
        $paymentConfig = $configHelper->getConfigPaymentMethodForQuote(
            \Payone_Core_Model_System_Config_PaymentMethodType::MASTERPASS,
            $quote
        );


        $billing = $quote->getBillingAddress();
        $billing->setData('should_ignore_validation', true);
        $billing->setData('payment_method', $paymentMethodCode);
        $billing->setFirstname($request->getFirstname())
            ->setLastname($request->getLastname())
            ->setEmail($request->getEmail())
            ->setPostcode($request->getBillingPostcode())
            ->setCity($request->getBillingCity())
            ->setRegion($request->getBillingState())
            ->setCountryId($request->getBillingCountry())
            ->setStreetFull(
                $request->getBillingStreet() . ', '. $request->getBillingAddressAddition()
            );
        $billing->save();

        $shipping = $quote->getShippingAddress();
        $shipping->setSameAsBilling(false);
        $shipping->setData('should_ignore_validation', true);
        $shipping->setData('payment_method', $paymentMethodCode);
        $shipping->setCollectShippingRates(true);
        $shipping->setFirstname($request->getShippingName())
            ->setTelephone($request->getShippingPhone())
            ->setPostcode($request->getShippingPostcode())
            ->setCity($request->getShippingCity())
            ->setRegion($request->getShippingState())
            ->setCountryId($request->getShippingCountry())
            ->setStreetFull(
                $request->getShippingStreet() . ', ' . $request->getShippingAddressAddition()
            );
        $shipping->collectShippingRates();
        $shipping->save();

        $payment = $quote->getPayment();
        $payment->setCcType($request->getCardType());
        $payment->setCcNumberEnc($request->getCardNumber());
        $payment->setCcExpMonth(substr($request->getCardExpiry(), 2, 2));
        $payment->setCcExpYear(substr($request->getCardExpiry(), 0, 2));
        $payment->setMethod($paymentMethodCode);
        $payment->setData('payone_config_payment_method_id', $paymentConfig->getId());
        $payment->setAdditionalInformation(self::PAYONE_MASTERPASS_CHECKOUT_WORKORDERID, $this->getWorkorderid());
        $payment->save();

        $dob = DateTimeImmutable::createFromFormat('dmY', $request->getBirthdate());
        if (!empty($dob)) {
            $quote->setCustomerDob($dob->format('d-m-Y'));
        }
        $quote->setTotalsCollectedFlag(false);

        $coupon = $this->checkoutSession->getData('cart_coupon_code');
        if (!empty($coupon)) {
            $this->quote->setCouponCode($coupon);
        }

        $quote->collectTotals();
        $shippingRates = $shipping->getGroupedAllShippingRates();
        $quote->save();
        if (empty($shippingRates) || !$this->isCountryAllowed($shipping->getCountry())) {
            Mage::throwException(
                Mage::helper('payone_core')->__('Shipping to the selected address is not available.')
            );
        }

        $quote->save();
    }

    /**
     * @param $country
     * @return bool
     */
    private function isCountryAllowed($country)
    {
        return in_array(strtoupper($country), explode(',', Mage::getStoreConfig('general/country/allow')));
    }

    /**
     * @param Payone_Core_Model_Service_Mastercard_Masterpass_Request_ChooseShippingMethodRequest $request
     * @return Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface
     */
    public function chooseShippingMethod($request)
    {
        try{
            $methodCode = $request->getMethodCode();

            /** @var Mage_Sales_Model_Quote $quote */
            $quote = Mage::getModel('sales/quote')->load($request->getQuoteId());
            $quote->getShippingAddress()
                ->setShippingMethod($methodCode)
                ->collectShippingRates()
                ->collectTotals()
                ->save();
            $quote->collectTotals()
                ->save();

            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_ChooseShippingMethodOkResponse();
            $response->setCode(Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::CHOOSE_SHIPPING_METHOD_OK_RESPONSE_CODE);
        }
        catch (\Exception $ex) {
            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_ChooseShippingMethodErrorResponse();
            $response->setCode($ex->getCode());
            $response->setData('message', $ex->getMessage());
        }

        return $response;
    }

    /**
     * @param Payone_Core_Model_Service_Mastercard_Masterpass_Request_PlaceOrderRequest $request
     * @return Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface
     */
    public function placeOrder($request)
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
            $quote->collectTotals();
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

            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_PlaceOrderOkResponse();
        }
        catch (\Exception $ex) {
            $response = new Payone_Core_Model_Service_Mastercard_Masterpass_Response_PlaceOrderErrorResponse();
            $response->setCode($ex->getCode());
            $response->setData('message', $ex->getMessage());
        }

        return $response;
    }
}