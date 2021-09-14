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
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_ApplepayController extends Payone_Core_Controller_Abstract
{
    /** @var array */
    protected $cardTypesMapping = array(
        'visa' => Payone_Api_Enum_CreditcardType::VISA,
        'mastercard' => Payone_Api_Enum_CreditcardType::MASTERCARD,
        'amex' => Payone_Api_Enum_CreditcardType::AMEX,
        'discover' => Payone_Api_Enum_CreditcardType::DINERS
    );

    public function createApplePaySessionAction()
    {
        $certDir = Mage::getBaseDir('var') . '/cert/';
        $shopFQDN = $_SERVER['SERVER_NAME'];
        $validationUrl = $this->getRequest()->get('validationUrl');

        try {
            $quoteId = $this->getRequest()->get('quoteId');
            $quote = Mage::getModel('sales/quote')->load($quoteId);

            /** @var \Mage_Payment_Helper_Data $paymentHelper */
            $paymentHelper = Mage::helper('payment');
            /** @var \Payone_Core_Model_Payment_Method_ApplePay $paymentMethod */
            $paymentMethod = $paymentHelper->getMethodInstance(Payone_Core_Model_System_Config_PaymentMethodCode::APPLEPAY);
            /** @var Payone_Core_Model_Config_Payment_Method $paymentConfig */
            $paymentConfig = $paymentMethod->getConfigForQuote($quote);

            $merchantId = $paymentConfig->getAplMerchantId();
            $certificateFileName = $paymentConfig->getAplMerchantIdentificationCertificate();
            $keyFileName = $paymentConfig->getAplCertificatePrivateKey();
            $keyPassword = $paymentConfig->getAplCertificateKeyPassword();

            $payload = [
                'merchantIdentifier' => $merchantId,
                'displayName' => 'PAYONE Apple Pay',
                'initiative' => 'web',
                'initiativeContext' => $shopFQDN
            ];

            $httpClient = new  Mage_HTTP_Client_Curl();
            $httpClient->setOptions([
                CURLOPT_SSLCERT => $certDir . $certificateFileName,
                CURLOPT_SSLKEY => $certDir . $keyFileName,
                CURLOPT_SSLKEYPASSWD => $keyPassword,
                CURLOPT_POSTFIELDS => json_encode($payload)
            ]);
            $httpClient->post($validationUrl, []);

            $status = $httpClient->getStatus();
            $response = $httpClient->getBody();

            if ($status !== 200) {
                Mage::log($this->__('APPLEPAY_CREATE_SESSION_ERROR') . ' : ' . $httpClient->getBody());

                $this->getResponse()
                    ->clearHeaders()
                    ->setHeader('HTTP/1.0', $status, true)
                    ->setHeader('Content-Type', 'application/json')
                    ->setBody(json_encode(
                            [
                                'status' => 'ERROR',
                                'message' => $this->__('APPLEPAY_CREATE_SESSION_ERROR'),
                                'errorDetails' =>$httpClient->getBody()
                            ]
                        )
                    );

                return $this->getResponse();
            }

            $merchantSession = json_decode($response, true);
            $this->getResponse()
                ->clearHeaders()
                ->setHeader('HTTP/1.0', 201, true)
                ->setHeader('Content-Type', 'application/json')
                ->setBody(json_encode(
                    [
                        'status' => 'SUCCESS',
                        'message' => '',
                        'merchantSession' => $merchantSession
                    ]
                ));

            return $this->getResponse();

        } catch (\Exception $e) {
            Mage::logException($e);

            $this->getResponse()
                ->clearHeaders()
                ->setHeader('HTTP/1.0', 500, true)
                ->setHeader('Content-Type', 'application/json')
                ->setBody(json_encode(
                        [
                            'status' => 'ERROR',
                            'message' => $this->__('APPLEPAY_CREATE_SESSION_ERROR'),
                            'errorDetails' => $e->getMessage()
                        ]
                    )
                );

            return $this->getResponse();
        }
    }

    public function createApplePayPaymentAction()
    {
        $quoteId = $this->getRequest()->get('quoteId');
        $quote = Mage::getModel('sales/quote')->load($quoteId);

        $data = json_decode($this->getRequest()->get('token'), true);
        $paymentData = $data['paymentData'];
        $methodData = $data['paymentMethod'];
        $creditCardType = '';
        if (isset($this->cardTypesMapping[strtolower($methodData['network'])])) {
            $creditCardType = $this->cardTypesMapping[strtolower($methodData['network'])];
        }

        $tokenData = [
            'paydata' => [
                'paymentdata_token_data' => isset($paymentData['data']) ? $paymentData['data'] : '',
                'paymentdata_token_ephemeral_publickey' => isset($paymentData['header']['ephemeralPublicKey']) ? $paymentData['header']['ephemeralPublicKey'] : '',
                'paymentdata_token_publickey_hash' => isset($paymentData['header']['publicKeyHash']) ? $paymentData['header']['publicKeyHash'] : '',
                'paymentdata_token_transaction_id' => isset($paymentData['header']['transactionId']) ? $paymentData['header']['transactionId'] : '',
                'paymentdata_token_signature' => isset($paymentData['signature']) ? $paymentData['signature'] : '',
                'paymentdata_token_version' => isset($paymentData['version']) ? $paymentData['version'] : ''
            ],
            'creditCardType' => $creditCardType
        ];

        $this->_getCheckoutSession()->setData('applePayTokenData', $tokenData);

        try {
            $quote->getBillingAddress()->setData('should_ignore_validation', true);
            $quote->getShippingAddress()->setData('should_ignore_validation', true);
            $quote->collectTotals()->save();

            /** @var \Mage_Sales_Model_Service_Quote $service */
            $service = Mage::getModel('sales/service_quote', $quote);
            $service->submitAll();
        } catch (\Exception $e) {
            Mage::logException($e);
            $this->getResponse()
                ->clearHeaders()
                ->setHeader('HTTP/1.0', 500, true)
                ->setHeader('Content-Type', 'application/json')
                ->setBody(json_encode([
                    'status' => 'ERROR',
                    'message' => $this->__('APPLEPAY_MAKE_PAYMENT_ERROR'),
                    'errorDetails' => $e->getMessage()
                ]));

            return $this->getResponse();
        }
        $this->_getCheckoutSession()->setData('last_quote_id', $quote->getId());
        $this->_getCheckoutSession()->setData('last_success_quote_id', $quote->getId());
        $this->_getCheckoutSession()->clearHelperData();
        $order = $service->getOrder();
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
            $this->_getCheckoutSession()->setData('last_order_id', $order->getId());
            $this->_getCheckoutSession()->setData('last_real_order_id', $order->getIncrementId());
            // as well a billing agreement can be created
            $agreement = $order->getPayment()->getBillingAgreement();
            if ($agreement) {
                $this->_getCheckoutSession()->setData('last_billing_agreement_id', $agreement->getId());
            }
        }
        Mage::dispatchEvent(
            'checkout_submit_all_after',
            ['order' => $order, 'quote' => $quote, 'recurring_profiles' => []]
        );

        $this->_getCheckoutSession()->unsetData('applePayTokenData');
        $this->_getCheckoutSession()->setQuoteId(null);
        $quote->setIsActive(false)->save();


        $urlParams = array(
            '_nosid'         => true,
            '_forced_secure' => true
        );
        $redirectUrl = Mage::getUrl('checkout/onepage/success', $urlParams);

        $this->getResponse()
            ->clearHeaders()
            ->setHeader('HTTP/1.0', 200, true)
            ->setHeader('Content-Type', 'application/json')
            ->setBody(json_encode([
                'status' => 'SUCCESS',
                'redirectUrl' => $redirectUrl
            ]));

        return $this->getResponse();
    }

    public function registerDeviceAction()
    {
        $allowedDevice = $this->getRequest()->get('allowed');
        $this->_getSession()->setData('applePayAllowedDevice', $allowedDevice);

        $this->getResponse()
            ->clearHeaders()
            ->setHeader('HTTP/1.0', 200, true)
            ->setHeader('Content-Type', 'application/json')
            ->setBody(json_encode([
                'message' => 'SUCCESS'
            ]));

        return $this->getResponse();
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
     * @return \Payone_Core_Model_Session
     */
    private function _getSession()
    {
        /** @var \Payone_Core_Model_Session $session */
        $session = Mage::getSingleton('payone_core/session');
        return $session;
    }
}
