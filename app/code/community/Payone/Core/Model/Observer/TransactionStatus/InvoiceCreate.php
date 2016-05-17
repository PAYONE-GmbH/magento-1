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
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Observer_TransactionStatus_InvoiceCreate
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @var Payone_Core_Model_Service_Sales_InvoiceCreate
     */
    protected $serviceInvoiceCreate = null;

    /** @var $method Payone_Core_Model_Payment_Method_Abstract */
    private $method = null;

    /** @var $order Mage_Sales_Model_Order */
    private $order = null;

    /** @var $config Payone_Core_Model_Config */
    private $config = null;

    /** @var $payment Mage_Sales_Model_Order_Payment */
    private $payment = null;

    /** @var $transactionStatus Payone_Core_Model_Domain_Protocol_TransactionStatus */
    private $transactionStatus = null;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function onAppointed(Varien_Event_Observer $observer)
    {
        $this->initData($observer);

        $configMethod = $this->getConfigPaymentMethodById();
        // All Other PaymentMethods create Invoice if request-type is authorization
        if ($configMethod->isRequestAuthorization()) {
            if (!$this->method instanceof Payone_Core_Model_Payment_Method_AdvancePayment) {
                // Create Invoice
                $invoice = $this->getServiceInvoiceCreate()->createByOrder($this->order);

                $this->sendInvoiceEmail($invoice);
            }
            // Advance Payment: invoice is created on Transaction Paid
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function onPaid(Varien_Event_Observer $observer)
    {
        $this->initData($observer);

        $configMethod = $this->getConfigPaymentMethodById();
        // Advance Payment create Invoice if request-type is authorization
        if ($configMethod->isRequestAuthorization()) {
            $isAdvancePayment = $this->method instanceof Payone_Core_Model_Payment_Method_AdvancePayment;

            if ($isAdvancePayment) {
                $invoice = $this->getServiceInvoiceCreate()->createByOrder($this->order);
            }
            else {
                // Load Invoice which has been created in 'onAppointed'
                $invoice = $this->getInvoiceForOrder();
            }

            if ($invoice) {
                $invoice->pay();

                if ($isAdvancePayment) {
                    $this->sendInvoiceEmail($invoice);
                }

                // Save invoice and it´s order as a transaction:
                try {
                    $transaction = $this->getFactory()->getModelResourceTransaction();
                    $transaction->addObject($invoice);
                    $transaction->addObject($invoice->getOrder());
                    $transaction->save();
                }
                catch (Mage_Core_Exception $e) {
                    throw new Payone_Core_Exception_InvoiceSave($e->getMessage());
                }
            }
        }
        // All Other PaymentMethods already have an invoice
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function initData(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

        /** @var $transactionStatus Payone_Core_Model_Domain_Protocol_TransactionStatus */
        $this->transactionStatus = $event->getTransactionStatus();

        $order = $this->getOrderByTransactionStatus($this->transactionStatus);
        $payment = $order->getPayment();
        $this->method = $payment->getMethodInstance();
        /** @var $method Payone_Core_Model_Payment_Method_Abstract */
        $this->method = $payment->getMethodInstance();
        $this->order = $order;
        $this->config = $event->getConfig();
        $this->payment = $payment;
    }

    /**
     * @param Mage_Sales_Model_Order_Invoice $invoice
     */
    protected function sendInvoiceEmail(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $invoice->setEmailSent(true);
        $invoice->sendEmail();
        $invoice->save();
    }

    /**
     * @param Payone_Core_Model_Service_Sales_InvoiceCreate $service
     */
    public function setServiceInvoiceCreate(Payone_Core_Model_Service_Sales_InvoiceCreate $service)
    {
        $this->serviceInvoiceCreate = $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_InvoiceCreate
     */
    public function getServiceInvoiceCreate()
    {
        if ($this->serviceInvoiceCreate === null) {
            $this->serviceInvoiceCreate = $this->getFactory()->getServiceSalesInvoiceCreate();
        }
        return $this->serviceInvoiceCreate;
    }

    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     * @return Mage_Sales_Model_Order
     */
    protected function getOrderByTransactionStatus(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $order = $this->getFactory()->getModelSalesOrder();
        $order->load($transactionStatus->getOrderId());
        return $order;
    }

    /**
     * @return bool|Payone_Core_Model_Config_Payment_Method_Interface
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    protected function getConfigPaymentMethodById()
    {
        $id = $this->payment->getPayoneConfigPaymentMethodId();
        $configPaymentMethod = $this->config->getPayment()->getMethodById($id);

        if (empty($configPaymentMethod)) {
            $message = 'Payment method configuration with id "' . $id . '" not found.';
            throw new Payone_Core_Exception_PaymentMethodConfigNotFound($message);
        }

        return $configPaymentMethod;
    }

    /**
     * @return Mage_Sales_Model_Order_Invoice
     */
    protected function getInvoiceForOrder()
    {
        /** @var $invoiceCollection Mage_Sales_Model_Mysql4_Order_Invoice_Collection */
        $invoiceCollection = $this->order->getInvoiceCollection();
        $invoiceCollection->addFieldToFilter('payone_sequencenumber', $this->transactionStatus->getSequencenumber());
        $invoice = $invoiceCollection->getFirstItem();
        return $invoice;
    }

}