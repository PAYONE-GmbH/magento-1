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
     * @var Payone_Core_Model_Domain_Protocol_TransactionStatus[] Transaction status objects which were processed this cron.
     */
    private $processed = array();

    /**
     * @var Payone_Core_Model_Domain_Protocol_TransactionStatus[] Transaction status objects of which processing has failed.
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
            // Get next pending TransactionStatus and provide a list of
            // all transaction status that were processed this cron.
            $transactionStatus = $collection->getNextPending($this->getProcessedIds());

            // Execute
            if ($transactionStatus) {
                $this->execute($transactionStatus);
                $countExecuted++;
            }
            else {
                $continue = false;
            }
        }

        // TODO: Move handling of failed transaction status to a separate cron.
        $this->handleFailed();

        Mage::helper('payone_core')->logCronjobMessage("executePending: finished ".$countExecuted);
        return $countExecuted;
    }

    /**
     * @return int The max amount of allowed processing retries.
     */
    protected function getProcessMaxRetryCount()
    {
        return Mage::helper('payone_core')->getTransactionProcessingMaxRetryCount();
    }

    /**
     * @return string The report email which receives processing reports.
     */
    protected function getProcessReportEmail()
    {
        return Mage::helper('payone_core')->getTransactionProcessingReportEmail();
    }

    /**
     * @return int[] The IDs of all processed transaction status.
     */
    protected function getProcessedIds()
    {
        return array_map(function ($processed) {
            return (int) $processed->getId();
        }, $this->processed);
    }

    /**
     * @return int[] The IDs of all failed transaction status.
     */
    protected function getFailedIds()
    {
        return array_map(function ($failed) {
            return (int) $failed->getId();
        }, $this->failed);
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

        // Track current transaction status as processed (no matter what happens next).
        $this->processed[] = $transactionStatus;

        $transactionStatus->setStatusRunning();
        $transactionStatus->save();
        $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Set status to running", $storeId);
        try {
            $this->getServiceProcess()->execute($transactionStatus);
            $transactionStatus->setStatusOk();
            $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Finished service execution, set status to complete", $storeId);
        } catch (Exception $e) {
            $processRetryCount = (int) $transactionStatus->getProcessRetryCount();

            if ($processRetryCount >= $this->getProcessMaxRetryCount()) {
                $transactionStatus->setStatusError();
                $transactionStatus->setProcessingError($e->getMessage());
                $this->failed[] = $transactionStatus;
                $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Error during service execution, set status to error with message {$e->getMessage()}", $storeId);
            }
            else {
                $transactionStatus->setStatusPending();
                $transactionStatus->setProcessRetryCount($processRetryCount + 1);
                $this->helper()->logCronjobMessage("ID: {$transactionStatus->getId()} - Execute - Failed service execution with error '{$e->getMessage()}', retry execution next run", $storeId);
            }
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
     * Handles all transaction status that were failed this cron job run
     * and sends a reporting email to the configured recipient.
     */
    private function handleFailed()
    {
        $this->getFactory()->helperEmail()->send(
            'general',
            $this->getProcessReportEmail(),
            false,
            'transaction_status_error_report',
            array(
                'failedIds' => $this->getFailedIds()
            )
        );
    }
}
