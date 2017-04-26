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
class Payone_Core_Model_Service_TransactionStatus_Process extends Payone_Core_Model_Service_Abstract
{
    const EVENT_NAME_PREFIX = 'payone_core_transactionstatus_';
    const EVENT_NAME_ALL = 'all';

    const EVENT_PARAMETER_TRANSACTION = 'transaction';
    const EVENT_PARAMETER_TRANSACTIONSTATUS = 'transaction_status';
    const EVENT_PARAMETER_CONFIG = 'config';
    const EVENT_PARAMETER_ORDER = 'order';

    /**
     * @var Payone_Core_Model_Service_Transaction_Update
     */
    protected $serviceTransaction = null;

    /**
     * @var Payone_Core_Model_Service_Sales_OrderStatus
     */
    protected $serviceOrderStatus = null;

    /**
     * @var Payone_Core_Model_Service_Sales_OrderComment
     */
    protected $serviceOrderComment = null;

    /**
     * @var Payone_Core_Model_Service_TransactionStatus_StoreClearingParameters
     */
    protected $serviceStoreClearingParams = null;

    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     * @throws Payone_Core_Exception_OrderNotFound
     */
    public function execute(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $order = $this->getFactory()->getModelSalesOrder();
        $order->loadByIncrementId($transactionStatus->getReference());
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Got order id: {$order->getId()} store-id: {$order->getStoreId()}", $order->getStoreId());

        if (!$order->hasData()) {
            $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Error Order has no data", $order->getStoreId(), Zend_Log::ERR);
            throw new Payone_Core_Exception_OrderNotFound('Reference "'.$transactionStatus->getReference().'"."');
        }

        // Secondary validation: is Transaction Id correct?
        $payment = $order->getPayment();
        $lastTxId = $payment->getLastTransId();
        if($lastTxId != $transactionStatus->getTxid())
        {
            $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Error TransactionStatus mismatch: payment-lastTransId: {$lastTxId} - TS-txid: {$transactionStatus->getTxid()}", $order->getStoreId(), Zend_Log::ERR);
            return; // Don´t throw an exception, just abort processing.
        }
        $config = $this->helperConfig()->getConfigStore($order->getStoreId());

        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Update TransactionStatus", $order->getStoreId());
        $transactionStatus->setStoreId($order->getStoreId());
        $transactionStatus->setOrderId($order->getId());

        // Update Transaction
        $transaction = $this->getServiceTransaction()->updateByTransactionStatus($transactionStatus);

        // Update Order Status
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Update order status", $order->getStoreId());
        $this->getServiceOrderStatus()->setConfigStore($config);
        $this->getServiceOrderStatus()->updateByTransactionStatus($order, $transactionStatus);

        // Add Order Comment
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Add order comment", $order->getStoreId());
        $this->getServiceOrderComment()->addByTransactionStatus($order, $transactionStatus);

        // Store Clearing Parameters (needs to be done before the events get triggered)
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Store clearing parameters", $order->getStoreId());
        $this->getServiceStoreClearingParams()->execute($transactionStatus, $order);

        // Save before Event is triggerd
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Save before events", $order->getStoreId());
        $resouce = $this->getFactory()->getModelResourceTransaction();
        $resouce->addObject($order);
        $resouce->addObject($transactionStatus);
        $resouce->save();

        // Trigger Event
        $params = array(
            self::EVENT_PARAMETER_TRANSACTIONSTATUS => $transactionStatus,
            self::EVENT_PARAMETER_TRANSACTION => $transaction,
            self::EVENT_PARAMETER_CONFIG => $config,
            self::EVENT_PARAMETER_ORDER => $order,
        );

        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Trigger event ".self::EVENT_NAME_PREFIX.self::EVENT_NAME_ALL, $order->getStoreId());
        $this->dispatchEvent(self::EVENT_NAME_PREFIX . self::EVENT_NAME_ALL, $params);

        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Trigger event ".self::EVENT_NAME_PREFIX.$transactionStatus->getTxaction(), $order->getStoreId());
        $this->dispatchEvent(self::EVENT_NAME_PREFIX . $transactionStatus->getTxaction(), $params);

        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Process - Finished", $order->getStoreId());
    }

    /**
     * @param $name
     * @param array $data
     *
     * @return Mage_Core_Model_App
     */
    protected function dispatchEvent($name, array $data = array())
    {
        return Mage::dispatchEvent($name, $data);
    }

    /**
     * @param Payone_Core_Model_Service_Sales_OrderStatus $service
     */
    public function setServiceOrderStatus(Payone_Core_Model_Service_Sales_OrderStatus $service)
    {
        $this->serviceOrderStatus = $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_OrderStatus
     */
    public function getServiceOrderStatus()
    {
        return $this->serviceOrderStatus;
    }

    /**
     * @param Payone_Core_Model_Service_Sales_OrderComment $service
     */
    public function setServiceOrderComment(Payone_Core_Model_Service_Sales_OrderComment $service)
    {
        $this->serviceOrderComment = $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_OrderComment
     */
    public function getServiceOrderComment()
    {
        return $this->serviceOrderComment;
    }

    /**
     * @param Payone_Core_Model_Service_Transaction_Update $serviceTransaction
     */
    public function setServiceTransaction(Payone_Core_Model_Service_Transaction_Update $serviceTransaction)
    {
        $this->serviceTransaction = $serviceTransaction;
    }

    /**
     * @return Payone_Core_Model_Service_Transaction_Update
     */
    public function getServiceTransaction()
    {
        return $this->serviceTransaction;
    }

    /**
     * @param Payone_Core_Model_Service_TransactionStatus_StoreClearingParameters $serviceStoreClearingParams
     */
    public function setServiceStoreClearingParams(Payone_Core_Model_Service_TransactionStatus_StoreClearingParameters $serviceStoreClearingParams)
    {
        $this->serviceStoreClearingParams = $serviceStoreClearingParams;
    }

    /**
     * @return Payone_Core_Model_Service_TransactionStatus_StoreClearingParameters
     */
    public function getServiceStoreClearingParams()
    {
        return $this->serviceStoreClearingParams;
    }


}