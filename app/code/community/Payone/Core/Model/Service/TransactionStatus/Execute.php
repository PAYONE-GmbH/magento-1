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
class Payone_Core_Model_Service_TransactionStatus_Execute extends Payone_Core_Model_Service_Abstract
{
    const MAX_EXECUTION_TIME = 30;
    /**
     * @var int
     */
    protected $maxExecutionTime;
    /**
     * @var Payone_Core_Model_Service_TransactionStatus_Process
     */
    protected $serviceProcess = null;

    /**
     * @var array
     * Array of failed stransactionsStatus
     */
    private $failed = array();

    /**
     * @return int
     */
    public function executePending()
    {
        /** @var $collection Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection */
        $collection = $this->getFactory()->getModelTransactionStatus()->getCollection();

        $startTime = time();
        $continue = true;
        $countExecuted = 0;
        $maxExecutionTime = $this->getMaxExecutionTime() ? $this->getMaxExecutionTime() : self::MAX_EXECUTION_TIME;
        while (((time() - $startTime) < $maxExecutionTime) && ($continue)) {
            // Get next pending TransactionStatus
            $transactionStatus = $collection->getNextPending();

            // Execute
            if ($transactionStatus) {
                $this->execute($transactionStatus);
                $countExecuted++;
            }
            else {
                $continue = false;
            }
        }
        Mage::helper('payone_core')->logCronjobMessage("Amount of failed: ".count($this->failed));

        $this->handleFailed();


        Mage::helper('payone_core')->logCronjobMessage("executePending: finished ".$countExecuted);
        return $countExecuted;
    }

    protected function _getIncrementId($sReference)
    {
        $oResource = Mage::getSingleton('core/resource');
        $oRead = $oResource->getConnection('core_read');

        $select = $oRead->select()
            ->from(array('tbl' => $oResource->getTableName('sales/order')), array('increment_id'))
            ->where('payone_cancel_substitute_increment_id = ?', $sReference)
            ->order('entity_id desc')
            ->limit(1);
        $sSubstitudeIncrementId = $oRead->fetchOne($select);
        if (!empty($sSubstitudeIncrementId)) {
            return $sSubstitudeIncrementId;
        }
        return $sReference;
    }

    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     */
    public function execute(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $storeId = $transactionStatus->getStoreId();

        /**
         * Check if the Store ID exists, if not fetch the order from the reference
         */
        if (is_null($storeId)) {
            $sIncrementId = $this->_getIncrementId($transactionStatus->getReference());

            $order = $this->getFactory()->getModelSalesOrder();
            $order->loadByIncrementId($sIncrementId);

            if ($sIncrementId != $transactionStatus->getReference()) {
                $transactionStatus->setReference($sIncrementId);
            }

            if ($order && $order->getId()) {
                $storeId = $order->getStoreId();
            }
        }

        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Execute TransactionStatus action: {$transactionStatus->getTxaction()} - store-id: {$storeId}", $storeId);

        $storeBefore = $this->getApp()->getStore();
        $areaBefore = $this->getDesign()->getArea();

        // Set Store
        $store = $this->getApp()->getStore($storeId);
        $this->getApp()->setCurrentStore($store);

        // Load Area to get Translation
        $this->getApp()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_TRANSLATE);
        $this->getApp()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_DESIGN);

        $transactionStatus->setStatusRunning();
        $transactionStatus->save();
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Set status to running", $storeId);
        try {
            $this->getServiceProcess()->execute($transactionStatus);
            $transactionStatus->setStatusOk();
            $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Finished service execution, set status to complete", $storeId);
        } catch (Exception $e) {
            $this->failed[$transactionStatus->getOrderId()] = $transactionStatus;
            $transactionStatus->setStatusError();
            $transactionStatus->setProcessingError($e->getMessage());
            $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Error during service execution, set status to error with message {$e->getMessage()}", $storeId);

        }
        $transactionStatus->setProcessedAt(date('Y-m-d H:i:s'));
        $transactionStatus->save();

        // Reset Store
        $this->getApp()->setCurrentStore($storeBefore);

        // Reset Area to old Area
        $this->getDesign()->setArea($areaBefore);
        $this->getApp()->loadArea($areaBefore);
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Finished", $storeId);
    }

    /**
     * @return Mage_Core_Model_App
     */
    protected function getApp()
    {
        return Mage::app();
    }

    /**
     * @return Mage_Core_Model_Design_Package
     */
    protected function getDesign()
    {
        return Mage::getDesign();
    }


    /**
     * @param Payone_Core_Model_Service_TransactionStatus_Process $serviceProcess
     */
    public function setServiceProcess(Payone_Core_Model_Service_TransactionStatus_Process $serviceProcess)
    {
        $this->serviceProcess = $serviceProcess;
    }

    /**
     * @return Payone_Core_Model_Service_TransactionStatus_Process
     */
    public function getServiceProcess()
    {
        return $this->serviceProcess;
    }

    /**
     * @param int $maxExecutionTime Define how long the service executes pending transactionStatus (in seconds)
     */
    public function setMaxExecutionTime($maxExecutionTime)
    {
        $this->maxExecutionTime = $maxExecutionTime;
    }

    /**
     * @return int Maximum execution time in seconds
     */
    public function getMaxExecutionTime()
    {
        return $this->maxExecutionTime;
    }

    /**
     *
     */
    private function handleFailed()
    {
        //Check if there are failed transactions
        if (count($this->failed) > 0) {
            Mage::helper('payone_core')->logCronjobMessage("Start failed handling");
            $retryCounter = Mage::helper('payone_core')->getTxRetries();

            //retry failed transactions
            while ($retryCounter > 0 && count($this->failed) > 0) {
                Mage::helper('payone_core')->logCronjobMessage("Retries left ".$retryCounter);
                Mage::helper('payone_core')->logCronjobMessage("Still failed: " . count($this->failed));
                foreach ($this->failed as $id => $transactionStatus) {

                    //remove current transaction from failed array
                    //if it will fail again, it will be automatically appended in execute()
                    unset($this->failed[$id]);
                    /**
                     * @var Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
                     */
                    $this->execute($transactionStatus);
                }
                $retryCounter--;
            }

            if (count($this->failed) > 0) {
                $list = array();
                //are all retries failed?
                foreach ($this->failed as $id => $transactionStatus) {
                    //add transaction to finally failed
                    Mage::helper('payone_core')->logCronjobMessage("Add to report list: Txid" . $transactionStatus->getTxid());
                    $list[] = $transactionStatus->getTxid();
                }

            }

            //are any failed transactions left? Than prepare report email
            if (count($this->failed) > 0) {
                $emailTo = Mage::helper('payone_core')->getTxReportEmail();
                Mage::helper('payone_core')->logCronjobMessage("Total transactions failed after retries: " . count($this->failed));
                Mage::helper('payone_core')->logCronjobMessage("Start prepare report email to: " . $emailTo );
                if (!filter_var($emailTo, FILTER_VALIDATE_EMAIL)) {
                    Mage::helper('payone_core')->logCronjobMessage("Abort sending E-mail. Invalid address.");
                    return;
                }
                //prepare Email
                $template = 'transaction_status_error_report';

                $params = array();
                $params['transactionsList'] = implode(',' , $list);
                Mage::helper('payone_core')->logCronjobMessage("E-mail body contains: " . $params['transactionsList'] );

                //send Email with Error information
                Mage::helper('payone_core')->logCronjobMessage("Start sending report email to: " . $emailTo );
                $this->getFactory()->helperEmail()->send('general', $emailTo, false, $template, $params);
                Mage::helper('payone_core')->logCronjobMessage("E-mail send to: " . $emailTo );
            }
        }
    }
}