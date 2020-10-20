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
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com, Copyright (c) 2017 <support@e3n.de> - www.e3n.de
 * @author          Matthias Walter <info@noovias.com>,  Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com, http://www.e3n.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Mapper_ApiRequest_Payment_Debit
    extends Payone_Core_Model_Mapper_ApiRequest_Payment_Abstract
{
    const EVENT_TYPE = 'debit';

    /** @var Mage_Sales_Model_Order_Creditmemo */
    protected $creditmemo = null;

    /** @var Payone_Api_Request_Parameter_Invoicing_Transaction */
    protected $invoicing;

    /**
     * @return Payone_Api_Request_Debit
     */
    protected function getRequest()
    {
        return $this->getFactory()->getRequestPaymentDebit();
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Payone_Api_Request_Debit
     */
    public function mapFromPayment(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->init($payment);

        $request = $this->getRequest();

        $this->mapDefaultParameters($request);

        $this->mapDefaultDebitParameters($request);

        $business = $this->mapBusinessParameters();
        $request->setBusiness($business);

        /** MAGE-410 add invoiceId if available, no matter which configuration state is set */
        $creditmemo = $this->getCreditmemo();
        if (!empty($creditmemo) && $creditmemo->hasData()) {
            $creditmemoIncrementId = $creditmemo->getIncrementId();
            if ($creditmemoIncrementId === null) {
                $creditmemoIncrementId = $this->fetchNewIncrementId($creditmemo);
            }
            $this->getInvoicing()->setInvoiceid($creditmemoIncrementId);
        }

        /** Set Invoicing-Parameter only if enabled in Config */
        if ($this->mustTransmitInvoiceData()) {
            $this->mapInvoicingParameters();

            if ($this->mustAdaptCalculation()) {
                /** @var Payone_Api_Request_Parameter_Invoicing_Item $item */
                foreach ($this->getInvoicing()->getItems() as $item) {
                    $item->setPr($item->getNo() * $item->getPr());
                    $item->setDe('Menge: ' . $item->getNo() . ' ' . $item->getDe());
                    $item->setNo(1);
                }
            }
        }

        if (!empty($this->invoicing)) {
            $request->setInvoicing($this->invoicing);
        }

        $paymentMethod = $this->getPaymentMethod();
        if (
            $paymentMethod instanceof Payone_Core_Model_Payment_Method_Ratepay ||
            $paymentMethod instanceof Payone_Core_Model_Payment_Method_RatepayInvoicing ||
            $paymentMethod instanceof Payone_Core_Model_Payment_Method_RatepayDirectDebit
        ) {
            $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'shop_id', 'data' => $paymentMethod->getInfoInstance()->getPayoneRatepayShopId())
                )
            );
            $request->setPaydata($payData);
            $request->setApiVersion('3.10');
        } elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionDebit ||
                 $paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInvoicing ||
                 $paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInstallment ||
                 $paymentMethod instanceof Payone_Core_Model_Payment_Method_Payolution)
        {
            $info = $paymentMethod->getInfoInstance();
            if($info->getPayoneIsb2b() == '1') {
                $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'b2b', 'data' => 'yes')
                    )
                );
                $request->setPaydata($payData);
            }
        } elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferTrustly) {
            $paymentRequest = new Payone_Api_Request_Parameter_Debit_PaymentMethod_BankAccount();

            $bankCountry = $payment->getData('payone_bank_country');
            $iban = $payment->getData('payone_sepa_iban');
            $bic = $payment->getData('payone_sepa_bic');
            $bankAccount = $payment->getData('payone_account_number');
            $bankCode = $payment->getData('payone_bank_code');

            if (!empty($bankCountry)) {
                $paymentRequest->setBankcountry($bankCountry);
            }
            if (!empty($iban)) {
                $paymentRequest->setIban($iban);
            }
            if (!empty($bic)) {
                $paymentRequest->setBic($bic);
            }
            if (!empty($bankAccount)) {
                $paymentRequest->setBankaccount($bankAccount);
            }
            if (!empty($bankCode)) {
                $paymentRequest->setBankcode($bankCode);
            }

            $request->setPayment($paymentRequest);
        }
        
        $this->dispatchEvent($this->getEventName(), array('request' => $request, 'creditmemo' => $this->getCreditmemo()));
        $this->dispatchEvent($this->getEventPrefix() . '_all', array('request' => $request));

        return $request;
    }

    /**
     * @param Payone_Api_Request_Debit $request
     */
    protected function mapDefaultDebitParameters(Payone_Api_Request_Debit $request)
    {
        $order = $this->getOrder();

        $transaction = $this->getFactory()->getModelTransaction();
        $transaction = $transaction->loadByPayment($order->getPayment());

        $request->setTxid($order->getPayment()->getLastTransId());
        $request->setSequencenumber($transaction->getNextSequenceNumber());
        $request->setCurrency($order->getOrderCurrencyCode());
        $request->setAmount($this->getAmount() * -1);
        $request->setRequest(Payone_Api_Enum_RequestType::DEBIT);
        $request->setUseCustomerdata('yes');

        $narrativeText = $this->getNarrativeText();
        $request->setNarrativeText($narrativeText);

        // MAGE-391 Fix MAGE-383
        if ($this->configPayment->getCurrencyConvert() && $order->getOrderCurrencyCode() != $order->getBaseCurrencyCode()) {
            $orderCurrency = $order->getOrderCurrency();
            $baseCurrency = $order->getBaseCurrency();

            if ($orderCurrency->getRate($baseCurrency) === false) {
                $amount = $request->getAmount() / $baseCurrency->getRate($orderCurrency);
            } else {
                $amount = $orderCurrency->convert($request->getAmount(), $baseCurrency);
            }

            $request->setCurrency($order->getBaseCurrencyCode());
            $request->setAmount($amount);
        }
    }

    /**
     * @return Payone_Api_Request_Parameter_Debit_Business
     */
    protected function mapBusinessParameters()
    {
        $business = new Payone_Api_Request_Parameter_Debit_Business();
        $business->setTransactiontype('');
        $business->setBookingDate('');
        $business->setDocumentDate('');

        $paymentMethod = $this->getPaymentMethod();

        // Some payment methods can not use settleaccount auto:
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice
                and $paymentMethod->getInfoInstance()->getPayoneSafeInvoiceType() == Payone_Api_Enum_FinancingType::BSV
        ) {
            // BillSAFE always settles account:
            $business->setSettleaccount(Payone_Api_Enum_Settleaccount::YES);
        }
        else {
            $business->setSettleaccount(Payone_Api_Enum_Settleaccount::AUTO);
        }

        return $business;
    }

    protected function mapInvoicingParameters()
    {
        $order = $this->getOrder();
        $creditmemo = $this->getCreditmemo();

        if (!empty($creditmemo) && $creditmemo->hasData()) {
            $appendix = $this->getInvoiceAppendixRefund($creditmemo);
            $this->getInvoicing()->setInvoiceappendix($appendix);

            // Regular order items:
            foreach ($creditmemo->getItemsCollection() as $itemData) {
                /** @var $itemData Mage_Sales_Model_Order_Creditmemo_Item */
                /** @var $orderItem Mage_Sales_Model_Order_Item */
                $orderItem = $order->getItemById($itemData->getOrderItemId());

                if ($orderItem->isDummy()) {
                    continue; // Do not map dummy items.
                }

                $number = number_format($itemData->getQty(), 0, '.', '');
                if ($number <= 0) {
                    continue; // Do not map items with zero quantity
                }

                $params['it'] = Payone_Api_Enum_InvoicingItemType::GOODS;
                $params['id'] = $itemData->getSku();
                $params['de'] = $itemData->getName();
                $params['no'] = $number;
                $params['pr'] = $this->getItemPrice($itemData);

                // We have to load the tax percentage from the order item
//                $params['va'] = number_format($orderItem->getTaxPercent(), 0, '.', '');
                $params['va'] = round($orderItem->getTaxPercent() * 100);   // transfer vat in basis point format [#MAGE-186]

                $item = new Payone_Api_Request_Parameter_Invoicing_Item();
                $item->init($params);
                $this->getInvoicing()->addItem($item);
            }

            // Refund shipping
            if ($creditmemo->getShippingInclTax() > 0) {
                // MAGE-451: skip if refund without basket (good will refund)
                if (!$this->isGoodwillRefund($creditmemo)) {
                    $this->getInvoicing()->addItem($this->mapRefundShippingAsItemByCreditmemo($creditmemo));
                }
            }

            // Adjustment Refund (positive adjustment)
            if ($creditmemo->getAdjustmentPositive() > 0) {
                // MAGE-451: skip if refund without basket (good will refund)
                if (!$this->isGoodwillRefund($creditmemo)) {
                    $this->getInvoicing()->addItem($this->mapAdjustmentPositiveAsItemByCreditmemo($creditmemo));
                }
            }

            // Adjustment Fee (negative adjustment)
            if ($creditmemo->getAdjustmentNegative() > 0) {
                $this->getInvoicing()->addItem($this->mapAdjustmentNegativeAsItemByCreditmemo($creditmemo));
            }

            // Add Discount as a position
            $discountAmount = $this->getCreditmemoDiscountAmount($creditmemo);
            if ($discountAmount) {
                $this->getInvoicing()->addItem($this->mapDiscountAsItem($discountAmount));
            }
        }
    }

    /**
     * @return Mage_Sales_Model_Order_Creditmemo
     */
    protected function getCreditmemo()
    {
        if ($this->creditmemo === null) {
            // we need to check registry because Magento won't give the creditmemo instance to PaymentMethodInstance
            $creditmemo = $this->helperRegistry()
                               ->registry('current_creditmemo');

            $this->creditmemo = $creditmemo;
        }

        return $this->creditmemo;
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     */
    public function setCreditmemo(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $this->creditmemo = $creditmemo;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return self::EVENT_TYPE;
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo_Item $itemData
     * @return float
     */
    private function getItemPrice(Mage_Sales_Model_Order_Creditmemo_Item $itemData)
    {
        if($this->configPayment->getCurrencyConvert()) {
            return $itemData->getBasePriceInclTax();
        }

        return $itemData->getPriceInclTax();
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return float
     */
    private function getCreditmemoDiscountAmount(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        if($this->configPayment->getCurrencyConvert()) {
            return $creditmemo->getBaseDiscountAmount();
        }

        return $creditmemo->getDiscountAmount();
    }

    /**
     * @return Payone_Api_Request_Parameter_Invoicing_Transaction
     */
    private function getInvoicing()
    {
        if (empty($this->invoicing)) {
            $this->invoicing = new Payone_Api_Request_Parameter_Invoicing_Transaction();
        }

        return $this->invoicing;
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return bool
     */
    protected function isGoodwillRefund(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        return $creditmemo->getTotalQty() == 0;
    }
}