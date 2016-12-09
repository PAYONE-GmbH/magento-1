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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Service_Sales_InvoiceCreate extends Payone_Core_Model_Service_Abstract
{
    /**
     * @throws Payone_Core_Exception_OrderCannotInvoice|Payone_Core_Exception_OrderNotFound
     * @param Mage_Sales_Model_Order $order
     * @param array $itemsQty
     * @param string $invoiceIncrementId
     * @return Mage_Sales_Model_Order_Invoice
     */
    public function createByOrder(Mage_Sales_Model_Order $order, array $itemsQty = array(), $invoiceIncrementId = null)
    {
        $orderIncrementId = $order->getIncrementId();

        if (!$order->getId()) {
            throw new Payone_Core_Exception_OrderNotFound($orderIncrementId);
        }

        if (!$order->canInvoice()) {
            throw new Payone_Core_Exception_OrderCannotInvoice($orderIncrementId);
        }

        return $this->create($order, $itemsQty, $invoiceIncrementId);
    }

    /**
     * @throws Payone_Core_Exception_OrderCannotInvoice|Payone_Core_Exception_OrderNotFound
     * @param string $orderIncrementId
     * @param array $itemsQty
     * @param string $invoiceIncrementId
     * @return Mage_Sales_Model_Order_Invoice
     */
    public function createByOrderIncrementId($orderIncrementId, array $itemsQty = array(), $invoiceIncrementId = null)
    {
        /**
         * @var $order Mage_Sales_Model_Order
         */
        $order = $this->getFactory()->getModelSalesOrder();
        $order->loadByIncrementId($orderIncrementId);

        if (!$order->getId()) {
            throw new Payone_Core_Exception_OrderNotFound($orderIncrementId);
        }

        if (!$order->canInvoice()) {
            throw new Payone_Core_Exception_OrderCannotInvoice($orderIncrementId);
        }

        return $this->create($order, $itemsQty, $invoiceIncrementId);
    }

    /**
     * @throws Payone_Core_Exception_InvoicePreparationNoItems|Payone_Core_Exception_InvoiceSave
     * @param Mage_Sales_Model_Order $order
     * @param array $itemsQty
     * @param null $invoiceIncrementId
     * @return Mage_Sales_Model_Order_Invoice
     */
    protected function create(Mage_Sales_Model_Order $order, array $itemsQty = array(), $invoiceIncrementId = null)
    {
        /* @var $invoice Mage_Sales_Model_Order_Invoice */
        $invoice = $order->prepareInvoice($itemsQty);

        if ($invoiceIncrementId) {
            $invoice->setIncrementId($invoiceIncrementId);
        }

        $invoice->setTransactionId($order->getPayment()->getLastTransId());


        if (count($invoice->getAllItems()) <= 0) {
            throw new Payone_Core_Exception_InvoicePreparationNoItems($order->getIncrementId());
        }

        if ($invoice) {
            $invoice->register();
            $invoice->setEmailSent(false);
            $invoice->getOrder()->setIsInProcess(true);
            try {
                $transactionSave = $this->getFactory()->getModelResourceTransaction();
                $transactionSave->addObject($invoice);
                $transactionSave->addObject($invoice->getOrder());
                $transactionSave->save();
            } catch (Mage_Core_Exception $e) {
                throw new Payone_Core_Exception_InvoiceSave($e->getMessage());
            }

            return $invoice;
        }

        return null;
    }

}