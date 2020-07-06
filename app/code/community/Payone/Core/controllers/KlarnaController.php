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
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_KlarnaController extends Mage_Core_Controller_Front_Action
{
    /** @var \Payone_Core_Model_Service_Klarna_Checkout */
    protected $checkout = null;

    /** @var \Mage_Sales_Model_Quote|null */
    protected $quote = null;

    /** @var \Payone_Core_Model_Config_Payment_Method|null */
    protected $config = null;

    public function startSessionAction()
    {
        $this->cleanKlarnaCheckoutSession();

        $methodCode = $this->getRequest()->getParam('method');
        $dob = $this->getRequest()->getParam('dob');

        $this->initCheckout($methodCode);
        $requestResult = $this->checkout->checkoutStartSession($dob);
        if ($requestResult instanceof Payone_Api_Response_Genericpayment_Approved) {
            $responseData = $requestResult->getPaydata()->toAssocArray();
            $responseData['status'] = $requestResult->getStatus();
            $responseData['workorderid'] = $requestResult->getWorkorderId();
            $this->getCheckoutSession()->setPayoneWorkorderId($requestResult->getWorkorderId());
        } else {
            if ($requestResult instanceof Payone_Api_Response_Error) {
                $responseData['status'] = $requestResult->getStatus();
                $responseData['message'] = $requestResult->getErrormessage();
                $responseData['customer_message'] = $requestResult->getCustomermessage();
            } else {
                $responseData['status'] = 'ERROR';
                $responseData['message'] = 'Error occured';
                $responseData['customer_message'] = 'Error occured';
            }
        }

        $this->getResponse()->setBody(json_encode($responseData));
    }

    public function successAction()
    {
        $checkoutSession = $this->getCheckoutSession();
        $encodedOrderId = base64_encode($checkoutSession->getLastRealOrder()->getEntityId());

        /** @var Payone_Core_Helper_Url $helper */
        $helper = Mage::helper('url');
        $successurl = $helper->getSuccessUrl() . "reference/{$encodedOrderId}/";

        $this->cleanKlarnaCheckoutSession();

        $this->_redirectUrl($successurl);
    }

    /**
     * @param string $methodCode
     * @return Payone_Core_AmazonPayController
     */
    private function initCheckout($methodCode = '')
    {
        $this->quote = $this->getCheckoutSession()->getQuote();
        if (empty($this->quote->getId())) {
            $this->quote = Mage::getModel('sales/quote');
            $this->quote->load($this->getRequest()->getParam('quoteId'));
            $this->getCheckoutSession()->setQuoteId($this->quote->getId());
        }

        if (is_null($this->config) || empty($this->config->getId())) {
            if (empty($methodCode)) {
                $methodCode = $this->quote->getPayment()->getMethod();
            }

            /** @var \Mage_Payment_Helper_Data $paymentHelper */
            $paymentHelper = Mage::helper('payment');
            /** @var \Payone_Core_Model_Payment_Method_KlarnaInvoicing $paymentMethod */
            $paymentMethod = $paymentHelper->getMethodInstance($methodCode);
            /** @var Payone_Core_Model_Config_Payment_Method_Interface $config */
            $this->config = $paymentMethod->getConfigForQuote($this->quote);
        }

        $this->checkout = Mage::getModel(
            'payone_core/service_klarna_checkout',
            [
                'quote'  => $this->quote,
                'config' => $this->config
            ]
        );
        return $this;
    }

    /**
     * MAGE-438: Clear klarna checkout session data
     */
    private function cleanKlarnaCheckoutSession()
    {
        $checkoutSession = $this->getCheckoutSession();
        $checkoutSession->unsetData('klarna_client_token');
        $checkoutSession->unsetData('klarna_session_id');
        $checkoutSession->unsetData('klarna_workorderid');
    }

    /**
     * @return \Mage_Checkout_Model_Session
     */
    private function getCheckoutSession()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');
        return $session;
    }
}