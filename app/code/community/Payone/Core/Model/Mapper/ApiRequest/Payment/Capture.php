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
class Payone_Core_Model_Mapper_ApiRequest_Payment_Capture
    extends Payone_Core_Model_Mapper_ApiRequest_Payment_Abstract
{
    const EVENT_TYPE = 'capture';

    /** @var Mage_Sales_Model_Order_Invoice */
    protected $invoice = null;


    /**
     * @return Payone_Api_Request_Capture
     */
    protected function getRequest()
    {
        return $this->getFactory()->getRequestPaymentCapture();
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Payone_Api_Request_Capture
     */
    public function mapFromPayment(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->init($payment);

        $request = $this->getRequest();

        $this->mapDefaultParameters($request);

        $this->mapDefaultCaptureParameters($request);

        $business = $this->mapBusinessParameters();
        $request->setBusiness($business);

        /** Set Invoiceing-Parameter only if enabled in Config */
        if ($this->mustTransmitInvoiceData()) {
            $invoicing = $this->mapInvoicingParameters();
            if (!empty($invoicing)) {
                $request->setInvoicing($invoicing);
            }
        }

        $paymentMethod = $this->getPaymentMethod();
        if($paymentMethod instanceof Payone_Core_Model_Payment_Method_Ratepay) {
            $info = $paymentMethod->getInfoInstance();

            $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
            $payData->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'shop_id', 'data' => $info->getPayoneRatepayShopId())
            ));
            $request->setPaydata($payData);
            $request->setApiVersion('3.10');
        }
        
        $this->dispatchEvent($this->getEventName(), array('request' => $request, 'invoice' => $this->getInvoice()));
        $this->dispatchEvent($this->getEventPrefix() . '_all', array('request' => $request));

        return $request;
    }

    /**
     * @param Payone_Api_Request_Capture $request
     */
    protected function mapDefaultCaptureParameters(Payone_Api_Request_Capture $request)
    {
        $order = $this->getOrder();
        $invoice = $this->getInvoice();

        $transaction = $this->getFactory()->getModelTransaction();
        $transaction = $transaction->loadByPayment($order->getPayment());

        $request->setTxid($order->getPayment()->getLastTransId());
        $request->setSequencenumber($transaction->getNextSequenceNumber());
        $request->setCurrency($order->getOrderCurrencyCode());
        if(!empty($invoice) && $invoice->hasData()) {
            $request->setAmount($invoice->getGrandTotal());
        } else {
            $request->setAmount($this->getAmount());
        }
        $request->setRequest(Payone_Api_Enum_RequestType::CAPTURE);
    }

    /**
     * @return Payone_Api_Request_Parameter_Capture_Business
     */
    protected function mapBusinessParameters()
    {
        $business = new Payone_Api_Request_Parameter_Capture_Business();
        $business->setBookingDate('');
        $business->setDocumentDate('');
        $business->setDueTime('');

        $paymentMethod = $this->getPaymentMethod();

        // settleaccount possibilities depend on payment method:
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_AdvancePayment
                or $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransfer
        ) {
            $payment = $paymentMethod->getInfoInstance();
            // Advancepayment and OnlineBankTransfer use NO/AUTO
            if ($this->isInvoiceLast() || $this->helperRegistry()->isPaymentCancelRegistered($payment)) {
                // Invoice completes the order
                $business->setSettleaccount(Payone_Api_Enum_Settleaccount::AUTO);
            } else {
                // partial payment
                $business->setSettleaccount(Payone_Api_Enum_Settleaccount::NO);
            }
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice
                && $paymentMethod->getInfoInstance()->getPayoneSafeInvoiceType() == Payone_Api_Enum_FinancingType::BSV) {
            // BillSAFE always settles account:
            $business->setSettleaccount(Payone_Api_Enum_Settleaccount::YES);
        } else {
            // all other can always use AUTO, regardless of complete or partial capture
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
        $invoice = $this->getInvoice();

        $invoicing = new Payone_Api_Request_Parameter_Capture_Invoicing_Transaction();
        if (!empty($invoice) && $invoice->hasData()) {

            $invoiceIncrementId = $invoice->getIncrementId();
            if ($invoiceIncrementId === null) {
                $invoiceIncrementId = $this->fetchNewIncrementId($invoice);
            }

            $appendix = $this->getInvoiceAppendix($invoice);

            $invoicing->setInvoiceid($invoiceIncrementId);
            $invoicing->setInvoiceappendix($appendix);

            // Regular order items:
            foreach ($invoice->getItemsCollection() as $itemData) {
                /** @var $itemData Mage_Sales_Model_Order_Invoice_Item */
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
                $params['pr'] = $itemData->getPriceInclTax();

                // We have to load the tax percentage from the order item
//                $params['va'] = number_format($orderItem->getTaxPercent(), 0, '.', '');
                $params['va'] = round( $orderItem->getTaxPercent() * 100 );   // transfer vat in basis point format [#MAGE-186]

                $item = new Payone_Api_Request_Parameter_Invoicing_Item();
                $item->init($params);
                $invoicing->addItem($item);
            }

            // Shipping / Fees:
            if ($invoice->getShippingInclTax() > 0) {
                $invoicing->addItem($this->mapShippingFeeAsItem());
            }

            // Discounts:
            $discountAmount = abs($invoice->getDiscountAmount()); // Discount Amount is positive on invoice.
            if ($discountAmount > 0) {
                $invoicing->addItem($this->mapDiscountAsItem(-1 * $discountAmount));
            }
        }

        // Capture mode:
        $payment = $this->getPaymentMethod()->getInfoInstance();
        if ($this->getPaymentMethod() instanceof Payone_Core_Model_Payment_Method_SafeInvoice
                or $this->helperRegistry()->isPaymentCancelRegistered($payment)
        ) {
            $invoicing->setCapturemode($this->mapCaptureMode());
        }


        return $invoicing;
    }

    /**
     * Check if this invoice will be the last one (not the case if any orderItems can still be invoiced)
     * @note Can´t use $invoice->isLast() here, as the items have already been processed, $orderItem->qty_invoiced is already incremented, which means isLast() returns wrong results.
     *
     * @return bool
     */
    protected function isInvoiceLast()
    {
        foreach ($this->getOrder()->getAllItems() as $orderItem) {
            /** @var $orderItem Mage_Sales_Model_Order_Item */
            if ($orderItem->isDummy()) {
                continue;
            }

            if ($orderItem->canInvoice()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    protected function mapCaptureMode()
    {
        $payment = $this->getPaymentMethod()->getInfoInstance();
        if ($this->isInvoiceLast() || $this->helperRegistry()->isPaymentCancelRegistered($payment)) {
            $captureMode = Payone_Api_Enum_CaptureMode::COMPLETED;
        }
        else {
            $captureMode = Payone_Api_Enum_CaptureMode::NOTCOMPLETED;
        }

        return $captureMode;
    }

    /**
     * @return Mage_Sales_Model_Order_Invoice|null
     */
    protected function getInvoice()
    {
        if ($this->invoice === null) {
            // we need to check registry because Magento won't give the invoice instance to PaymentMethodInstance
            $this->invoice = $this->helperRegistry()->registry('current_invoice');
        }
        return $this->invoice;
    }

    /**
     * @param Mage_Sales_Model_Order_Invoice $invoice
     */
    public function setInvoice(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return self::EVENT_TYPE;
    }
}
