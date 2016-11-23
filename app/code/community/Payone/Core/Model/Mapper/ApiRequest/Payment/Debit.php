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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
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

        /** Set Invoiceing-Parameter only if enabled in Config */
        if ($this->mustTransmitInvoiceData()) {
            $invoicing = $this->mapInvoicingParameters();
            $request->setInvoicing($invoicing);
        }

        $paymentMethod = $this->getPaymentMethod();
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Ratepay) {
            $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'shop_id', 'data' => $paymentMethod->getInfoInstance()->getPayoneRatepayShopId())
                )
            );
            $request->setPaydata($payData);
            $request->setApiVersion('3.10');
        } elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_Payolution) {
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

    /**
     * @return Payone_Api_Request_Parameter_Invoicing_Transaction
     */
    protected function mapInvoicingParameters()
    {
        $order = $this->getOrder();
        $creditmemo = $this->getCreditmemo();

        $invoicing = new Payone_Api_Request_Parameter_Invoicing_Transaction();
        if (!empty($creditmemo) && $creditmemo->hasData()) {
            $creditmemoIncrementId = $creditmemo->getIncrementId();
            if ($creditmemoIncrementId === null) {
                $creditmemoIncrementId = $this->fetchNewIncrementId($creditmemo);
            }

            $appendix = $this->getInvoiceAppendixRefund($creditmemo);

            $invoicing->setInvoiceid($creditmemoIncrementId);
            $invoicing->setInvoiceappendix($appendix);

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

                $params['id'] = $itemData->getSku();
                $params['de'] = $itemData->getName();
                $params['no'] = $number;
                $params['pr'] = $itemData->getPriceInclTax();
                $params['it'] = Payone_Api_Enum_InvoicingItemType::GOODS;


                // We have to load the tax percentage from the order item
//                $params['va'] = number_format($orderItem->getTaxPercent(), 0, '.', '');
                $params['va'] = round($orderItem->getTaxPercent() * 100);   // transfer vat in basis point format [#MAGE-186]

                $item = new Payone_Api_Request_Parameter_Invoicing_Item();
                $item->init($params);
                $invoicing->addItem($item);
            }

            // Refund shipping
            if ($creditmemo->getShippingInclTax() > 0) {
                $invoicing->addItem($this->mapRefundShippingAsItemByCreditmemo($creditmemo));
            }

            // Adjustment Refund (positive adjustment)
            if ($creditmemo->getAdjustmentPositive() > 0) {
                $invoicing->addItem($this->mapAdjustmentPositiveAsItemByCreditmemo($creditmemo));
            }

            // Adjustment Fee (negative adjustment)
            if ($creditmemo->getAdjustmentNegative() > 0) {
                $invoicing->addItem($this->mapAdjustmentNegativeAsItemByCreditmemo($creditmemo));
            }

            // Add Discount as a position
            $discountAmount = $creditmemo->getDiscountAmount();
            if ($discountAmount) {
                $invoicing->addItem($this->mapDiscountAsItem($discountAmount));
            }
        }

        return $invoicing;
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
}