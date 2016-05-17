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
class Payone_Core_Model_Service_Sales_OrderComment extends Payone_Core_Model_Service_Abstract
{
    /**
     * @var Payone_Core_Model_Service_InitializeConfig
     */
    protected $serviceConfig = null;

    /**
     * @param Mage_Sales_Model_Order $order
     * @param Payone_Api_Response_Interface $response
     */
    public function addByApiResponse(
        Mage_Sales_Model_Order $order, Payone_Api_Response_Interface $response)
    {
        // Preauthorization
        if ($response instanceof Payone_Api_Response_Preauthorization_Approved) {
            $comment = 'PAYONE successfully processed the payment-request.';
        }
        // Authorization
        elseif ($response instanceof Payone_Api_Response_Authorization_Approved) {
            $comment = 'PAYONE successfully processed and confirmed the payment-request.';
        }
        // Redirects
        elseif ($response instanceof Payone_Api_Response_Preauthorization_Redirect
                or $response instanceof Payone_Api_Response_Authorization_Redirect
        ) {
            $comment = 'The payment-request has been forwarded.';
        }
        // Capture
        elseif ($response instanceof Payone_Api_Response_Capture_Approved) {
            $comment = 'PAYONE successfully processed the capture-request.';
        }
        // Debit
        elseif ($response instanceof Payone_Api_Response_Debit_Approved) {
            $comment = 'PAYONE successfully processed the debit-request.';
        }
        // Error
        elseif ($response instanceof Payone_Api_Response_Error) {
            $comment = 'The payment-request was incorrect. Please check the protocol.';
        }
        else {
            $comment = $response->getStatus();
        }

        $this->addCommentToOrder($order, $comment);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     */
    public function addByTransactionStatus(
        Mage_Sales_Model_Order $order, Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
    )
    {
        if ($transactionStatus->isAppointed()) {
            $comment = 'PAYONE accepted the payment-request.';
        }
        elseif ($transactionStatus->isCapture()) {
            $comment = 'PAYONE confirmed the collection.';
        }
        elseif ($transactionStatus->isPaid()) {
            $comment = 'PAYONE confirmed the payment receipt.';
        }
        elseif ($transactionStatus->isUnderpaid()) {
            $comment = 'PAYONE confirmed the payment receipt. There is an underpayment.';
        }
        elseif ($transactionStatus->isCancelation()) {
            $comment = 'The payment was rejected.';
        }
        elseif ($transactionStatus->isRefund()) {
            $comment = 'PAYONE confirmed the credit.';
        }
        elseif ($transactionStatus->isDebit()) {
            $comment = 'PAYONE confirmed the claim.';
        }
        elseif ($transactionStatus->isReminder()) {
            $comment = 'The dunning status was updated, status is %s';
        }
        elseif ($transactionStatus->isTransfer()) {
            $comment = 'Transactionstatus: transfer';
        }
        elseif ($transactionStatus->isVauthorization()) {
            $comment = 'Transactionstatus: vauthorization';
        }
        elseif ($transactionStatus->isVsettlement()) {
            $comment = 'Transactionstatus: vsettlement';
        }
        elseif ($transactionStatus->isInvoice()) {
            $comment = 'Transactionstatus: invoice';
        }
        else {
            $comment = $transactionStatus->getTxaction();
        }

        $this->addCommentToOrder($order, $comment);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param string $comment
     * @return Mage_Sales_Model_Order_Status_History
     */
    protected function addCommentToOrder(Mage_Sales_Model_Order $order, $comment)
    {
        $comment = $this->helper()->__($comment);

        return $order->addStatusHistoryComment($comment);
    }

}