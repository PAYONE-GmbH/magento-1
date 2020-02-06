<?php

class Payone_Core_Helper_AmazonPay extends Payone_Core_Helper_Abstract
{
    /**
     * MAGE-469 : request order cancellation on Amazon side
     *
     * @param string $workorderId
     * @param string $amazonReferenceId
     *
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     */
    public function cancelAmazonPayOrder($workorderId, $amazonReferenceId)
    {
        $params = array(
            'action' => \Payone_Api_Enum_GenericpaymentAction::AMAZONPAY_CANCELORDERREFERENCE,
            'amazonReferenceId'   => $amazonReferenceId
        );

        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $this->getFactory()->getSingletonCheckoutSession()->getQuote();
        /** @var \Mage_Payment_Helper_Data $paymentHelper */
        $paymentHelper = Mage::helper('payment');
        /** @var \Payone_Core_Model_Payment_Method_AmazonPay $paymentMethod */
        $paymentMethod = $paymentHelper->getMethodInstance(
            Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY
        );
        $config = $paymentMethod->getConfigForQuote($quote);

        $service = $this->getFactory()->getServicePaymentGenericpayment($config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestAmazonPayCancelOrderReference(
            $workorderId,
            $params,
            $quote->getQuoteCurrencyCode(),
            $quote->getGrandTotal()
        );

        if($config->getCurrencyConvert()) {
            $request->setCurrency($quote->getBaseCurrencyCode());
            $request->setAmount($quote->getBaseGrandTotal());
        }

        return $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);
    }
}
