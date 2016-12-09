<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 */

/**
 * Class Payone_Core_RatepayController
 */
class Payone_Core_RatepayController extends Mage_Core_Controller_Front_Action
{
    /**
     * Calculates the rates by from user defined rate
     * called from an ajax request with ratePay parameters (ratepay.js)
     * map RatePay API parameters and request the payone API
     *
     */
    public function rateAction()
    {
        $html = '';
        $paymentMethod = $this->getRequest()->getParam('paymentMethod');
        $calcValue = $this->getRequest()->getParam('calcValue');
        $ratePayShopId = $this->getRequest()->getParam('ratePayshopId');
        $amount = $this->getRequest()->getParam('amount');
        $ratePayCurrency = $this->getRequest()->getParam('ratePayCurrency');
        $this->loadLayout();

        try {
            if (preg_match('/^[0-9]+(\.[0-9][0-9][0-9])?(,[0-9]{1,2})?$/', $calcValue)) {
                $calcValue = str_replace(".", "", $calcValue);
                $calcValue = str_replace(",", ".", $calcValue);

                $client = Mage::getSingleton('payone_core/mapper_apiRequest_payment_genericpayment');
                $ratePayConfigModel = Mage::getSingleton('payone_core/payment_method_ratepay');
                $getConfig = $ratePayConfigModel->getAllConfigsByQuote($this->getQuote());
                $result = $client->ratePayCalculationRequest($amount, $ratePayShopId, $ratePayCurrency, $calcValue, null, $getConfig, 'calculation-by-rate');

                if ($result instanceof Payone_Api_Response_Genericpayment_Ok) {
                    $responseData = $result->getPayData()->toAssocArray();
                    $reviewBlock = $this->getLayout()->getBlock('payone_ratepay.checkout.installmentplan');
                    $html = $reviewBlock->showRateResultHtml($responseData);
                    //set payone Session Data
                    $this->setSessionData($responseData, $paymentMethod);
                } else {
                    $this->unsetSessionData($paymentMethod);
                    if($result instanceof Payone_Api_Response_Error) {
                        $html = "<div class='ratepay-result rateError'>" . $this->__($result->getCustomermessage()) . "</div>";
                    }
                }
            } else {
                $this->unsetSessionData($paymentMethod);
                $html = "<div class='ratepay-result rateError'>" . $this->__('lang_error') . ":<br/>" . $this->__('lang_wrong_value') . "</div>";
            }
        } catch (Exception $e) {
            $this->unsetSessionData($paymentMethod);
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Unable to initialize Rate Pay Installement.')
            );
            Mage::logException($e);
        }
        
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'text/html')
            ->setBody($html);
        return;
    }

    /**
     * Calculates the rates by from user defined runtime
     * called from an ajax request with ratePay parameters (ratepay.js)
     * map RatePay API parameters and request the payone API
     */
    public function runtimeAction()
    {
        $paymentMethod = $this->getRequest()->getParam('paymentMethod');
        $calcValue = $this->getRequest()->getParam('calcValue');
        $ratePayShopId = $this->getRequest()->getParam('ratePayshopId');
        $amount = $this->getRequest()->getParam('amount');
        $ratePayCurrency = $this->getRequest()->getParam('ratePayCurrency');
        $this->loadLayout();

        try {
                if (preg_match('/^[0-9]{1,5}$/', $calcValue)) {
                    $client = Mage::getSingleton('payone_core/mapper_apiRequest_payment_genericpayment');
                    $ratePayConfigModel = Mage::getSingleton('payone_core/payment_method_ratepay');
                    $getConfig = $ratePayConfigModel->getAllConfigsByQuote($this->getQuote());
                    $result = $client->ratePayCalculationRequest($amount, $ratePayShopId, $ratePayCurrency, null, $calcValue, $getConfig, 'calculation-by-time');

                    if ($result instanceof Payone_Api_Response_Genericpayment_Ok) {
                        $responseData = $result->getPayData()->toAssocArray();
                        $reviewBlock = $this->getLayout()->getBlock('payone_ratepay.checkout.installmentplan');
                        $html = $reviewBlock->showRateResultHtml($responseData);
                        //set payone Session Data
                        $this->setSessionData($responseData, $paymentMethod);
                    } else {
                        $this->unsetSessionData($paymentMethod);
                        $html = "<div class='rateError'>" . $this->__('lang_error') . ":<br/>" . $this->__('lang_request_error_else') . "</div>";
                    }
                } else {
                    $this->unsetSessionData($paymentMethod);
                    $html = "<div class='rateError'>" . $this->__('lang_error') . ":<br/>" . $this->__('lang_wrong_value') . "</div>";
                }
        } catch (Exception $e) {
            $this->unsetSessionData($paymentMethod);
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Unable to initialize Rate Pay Installement.')
            );
            Mage::logException($e);
        }
        
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'text/html')
            ->setBody($html);
        return;
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

    /**
     * Set the calculated rates into the session
     *
     * @param array $result
     */
    private function setSessionData($result, $paymentMethod)
    {
        foreach ($result as $key => $value) {
            $setSessionFunction = "set".$paymentMethod . ucfirst($key);
            Mage::getSingleton('payone_core/session')->$setSessionFunction($value);
        }
    }

    /**
     * Unsets the calculated rates from the session
     */
    private function unsetSessionData($paymentMethod)
    {
        foreach (Mage::getSingleton('payone_core/session')->getData() as $key => $value) {
            if (!is_array($value)) {
                $sessionNameBeginning = substr($key, 0, strlen($paymentMethod));
                if ($sessionNameBeginning == $paymentMethod && $key[strlen($paymentMethod)] == "_") {
                    $unsetFunction = "uns" . $key;
                    Mage::getSingleton('payone_core/session')->$unsetFunction();
                }
            }
        }
    }

    /**
     * Retrieve quote
     *
     * @return Mage_Sales_Model_Quote
     */
    private function getQuote()
    {
        return Mage::getSingleton('checkout/session')->getQuote();
    }
}