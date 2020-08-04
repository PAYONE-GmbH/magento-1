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
    const DEFAULT_MAX_EXECUTION_TIME = 30;
    const DEFAULT_MAX_RETRY_COUNT    = 3;

    /** @var int The maximum execution time of TX status processing. */
    protected $maxExecutionTime = self::DEFAULT_MAX_EXECUTION_TIME;

    /** @var int The max retry count for failed TX status. */
    protected $maxRetryCount = self::DEFAULT_MAX_RETRY_COUNT;

    /** @var string|null A valid email if process reporting should be enabled or null if disabled. */
    protected $processReportEmail = null;

    /** @var bool True if logging is set to verbose. */
    protected $loggingVerbose = false;

    /** @var Payone_Core_Model_Service_TransactionStatus_Process */
    protected $serviceProcess = null;

    /** @var int|null The processing start time. */
    protected $startTime = null;

    /** @var Payone_Core_Model_Domain_Protocol_TransactionStatus[] A list of processed TX status. */
    protected $processedTxStatus = array();

    /** @var int[] A list of from processing excluded TxIds. */
    protected $excludedTxIds = array();

    /**
     * @param int $maxExecutionTime Maximum execution time in seconds.
     */
    public function setMaxExecutionTime($maxExecutionTime)
    {
        $this->maxExecutionTime = (int) $maxExecutionTime;
    }

    /**
     * @param int $maxRetryCount Allowed maximum of retries for failed TX status.
     */
    public function setMaxRetryCount($maxRetryCount)
    {
        $this->maxRetryCount = (int) $maxRetryCount;
    }

    /**
     * @param string|null $processReportEmail A valid email if process reporting should be enabled or null to disable.
     */
    public function setProcessReportEmail($processReportEmail)
    {
        $this->processReportEmail = $processReportEmail;
    }

    /**
     * @param Payone_Core_Model_Service_TransactionStatus_Process $serviceProcess
     */
    public function setServiceProcess(Payone_Core_Model_Service_TransactionStatus_Process $serviceProcess)
    {
        $this->serviceProcess = $serviceProcess;
    }

    /**
     * @param $loggingVerbose
     */
    public function setLoggingVerbose($loggingVerbose)
    {
        $this->loggingVerbose = $loggingVerbose === true;
    }

    /**
     * @return bool
     */
    protected function hasExecutionTime()
    {
        // Check if we having remaining execution time,
        // use a safe gap of 2 seconds
        return is_int($this->startTime) && is_int($this->maxExecutionTime)
            ? (time() - $this->startTime - 2) < $this->maxExecutionTime
            : false;
    }

    /**
     * @param string $message
     * @param null $store
     * @param bool $verbose True if the log is a verbose information.
     */
    protected function logMessage($message, $store = null, $verbose = false)
    {
        // Enable logging if the current log is not verbose or if the
        // current logs is verbose and verbose logging is enabled.
        if (!$verbose || ($verbose && $this->loggingVerbose)) {
            if ($store) {
                Mage::helper('payone_core')->logCronjobMessage($message, $store);
            }
            else {
                Mage::helper('payone_core')->logCronjobMessage($message);
            }
        }

    }

    /**
     * Loads and returns next pending TX status or returns null
     * if no pending TX status exists.
     *
     * @return Payone_Core_Model_Domain_Protocol_TransactionStatus|null
     */
    protected function loadNextTxStatus()
    {
        /** @var Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection $collection */
        $collection = $this->getFactory()->getModelTransactionStatus()->getCollection();

        // Get only pending TX status which were not already processed or excluded.
        $collection->addFieldToFilter('processing_status', Payone_Core_Model_Domain_Protocol_TransactionStatus::STATUS_PENDING);

        if (!empty($this->processedTxStatus)) {
            $collection->addFieldToFilter('id', array('nin' => array_keys($this->processedTxStatus)));
        }

        if (!empty($this->excludedTxIds)) {
            $collection->addFieldToFilter('txid', array('nin' => $this->excludedTxIds));
        }

        // Make sure we get the most recent TX status to make sure newer status are processed
        // before any old dangling status.
        $collection->setOrder('txtime', Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection::SORT_ORDER_DESC);

        // Limit result to one entity
        $collection->getSelect()->limit(1);

        $this->logMessage(sprintf('Fetching next TX status -- %s', $collection->getSelectSql(true)), null, true);

        /** @var Payone_Core_Model_Domain_Protocol_TransactionStatus $txStatus */
        $txStatus = $collection->getFirstItem();

        return $txStatus->hasData()
            ? $txStatus
            : null;
    }

    /**
     * Verifies the provided TX status.
     *
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $txStatus
     * @return bool
     */
    protected function verifyTxStatus(Payone_Core_Model_Domain_Protocol_TransactionStatus $txStatus)
    {
        $collection = $this->getFactory()->getModelTransactionStatus()->getCollection();

        // Get all prior TX status with same TxId
        $collection->clear();
        $collection->addFieldToFilter('id', array('neq' => (int) $txStatus->getId()));
        $collection->addFieldToFilter('txid', (int) $txStatus->getTxid());
        $collection->addFieldToFilter('txtime', array('lteq' => (int) $txStatus->getTxtime()));
        $collection->setOrder('txtime', Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection::SORT_ORDER_ASC);

        $this->logMessage(sprintf("Verify TX status %d (%d) prior TX status are all processed successfully.", $txStatus->getId(), $txStatus->getTxid()), null, true);
        $this->logMessage(sprintf("Fetch prior TX status -- %s", $collection->getSelectSql(true)), null, true);

        $collection->load();

        // Verify all prior TX status have a processing_status of completed
        /** @var Payone_Core_Model_Domain_Protocol_TransactionStatus $priorTxStatus */
        foreach ($collection as $priorTxStatus) {
            if ($priorTxStatus->getProcessingStatus() !== Payone_Core_Model_Domain_Protocol_TransactionStatus::STATUS_OK) {
                $this->logMessage(sprintf(
                    "Prior TX status %d (%d) is not completed, processing state is '%s', cannot process TX status %d (%d)",
                    $priorTxStatus->getId(),
                    $priorTxStatus->getTxid(),
                    $priorTxStatus->getProcessingStatus(),
                    $txStatus->getId(),
                    $txStatus->getTxid()
                ), null, true);

                return false;
            }
        }

        return true;
    }

    /**
     * Runs the TX status processing.
     */
    public function run()
    {
        // Track start time for calculating remaining time
        $this->startTime = time();

        $this->logMessage(sprintf('Starting TX status processing at timestamp %d', $this->startTime));
        $this->logMessage(sprintf(' -- with configured max execution time of %d seconds', $this->maxExecutionTime), null, true);
        $this->logMessage(sprintf(' -- with configured max retry count of %d', $this->maxRetryCount), null, true);

        // Process as long as we have remaining time
        while ($this->hasExecutionTime()) {
            $txStatus = $this->loadNextTxStatus();

            if (!$txStatus) {
                // At this point we are out of TX status to process and can exit early
                $this->logMessage('No more TX status to process.');
                break;
            }

            if (!$this->verifyTxStatus($txStatus)) {
                $this->excludedTxIds[] = (int) $txStatus->getTxid();
                continue;
            }

            $this->processedTxStatus[(int) $txStatus->getId()] = $txStatus;
            $this->execute($txStatus);
        }

        if (!$this->hasExecutionTime()) {
            $this->logMessage('Exhausted processing time.');
        }

        if ($this->processReportEmail) {
            // TODO: Move handling of failed transaction status to a separate cron.
            $this->handleFailed();
        }

        return count($this->processedTxStatus);
    }

    protected function execute(Payone_Core_Model_Domain_Protocol_TransactionStatus $txStatus)
    {
        $storeId = $txStatus->getStoreId();

        // Check if the Store ID exists, if not fetch the order from the reference
        if (is_null($storeId)) {
            $sIncrementId = $this->_getIncrementId($txStatus->getReference());

            $order = $this->getFactory()->getModelSalesOrder();
            $order->loadByIncrementId($sIncrementId);

            if ($sIncrementId != $txStatus->getReference()) {
                $txStatus->setReference($sIncrementId);
            }

            if ($order && $order->getId()) {
                $storeId = $order->getStoreId();
            }
        }

        $this->logMessage("Starting execution of TX status with ID {$txStatus->getId()} and action {$txStatus->getTxaction()}.", $storeId);

        $storeBefore = $this->getApp()->getStore();
        $areaBefore = $this->getDesign()->getArea();

        // Set Store
        $store = $this->getApp()->getStore($storeId);
        $this->getApp()->setCurrentStore($store);

        // Load Area to get Translation
        $this->getApp()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_TRANSLATE);
        $this->getApp()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_DESIGN);

        // Set status to running to kind of lock this TX status.
        // TODO: There is a problem if the processing fails and the TX status remains in running status for some reason.
        // The TX status will not be processed again because of the dangling running status.
        // https://github.com/PAYONE-GmbH/magento-1/issues/428
        $txStatus->setStatusRunning();
        $txStatus->save();

        $this->logMessage(sprintf(
            "TX status ID %d: Processing status set to %s.",
            $txStatus->getId(),
            Payone_Core_Model_Domain_Protocol_TransactionStatus::STATUS_RUNNING
        ), $storeId);

        try {
            // Execute processing of TX status and set status to complete
            // if executing succeeds without error.
            $this->serviceProcess->execute($txStatus);
            $txStatus->setStatusOk();

            $this->logMessage(sprintf(
                "TX status ID %d: Processing status set to %s.",
                $txStatus->getId(),
                Payone_Core_Model_Domain_Protocol_TransactionStatus::STATUS_OK
            ), $storeId);
        } catch (Exception $e) {
            // Exclude all TX status with the same txid from further processing
            $this->excludedTxIds[] = (int) $txStatus->getTxid();

            $this->logMessage(sprintf(
                "TX status ID %d: Failed processing with error %s.",
                $txStatus->getId(),
                $e->getMessage()
            ), $storeId);

            $processRetryCount = (int) $txStatus->getProcessRetryCount();

            if ($processRetryCount >= $this->maxRetryCount) {
                $txStatus->setStatusError();
                $txStatus->setProcessingError($e->getMessage());
                $txStatus->setProcessingErrorStacktrace($e->getTraceAsString());

                $this->logMessage(sprintf(
                    "TX status ID %d: Finally failed processing, processing status set to %s.",
                    $txStatus->getId(),
                    Payone_Core_Model_Domain_Protocol_TransactionStatus::STATUS_ERROR
                ), $storeId);
            }
            else {
                $txStatus->setStatusPending();
                $txStatus->setProcessRetryCount($processRetryCount + 1);

                $this->logMessage(sprintf(
                    "TX status ID %d: Retry processing next run, processing status set to %s.",
                    $txStatus->getId(),
                    Payone_Core_Model_Domain_Protocol_TransactionStatus::STATUS_PENDING
                ), $storeId);
            }
        } finally {
            // Save TX status entry at this point, no matter if it has succeeded or not
            $txStatus->setProcessedAt(date('Y-m-d H:i:s'));
            $txStatus->save();
        }

        // Reset Store
        $this->getApp()->setCurrentStore($storeBefore);

        // Reset Area to old Area
        $this->getDesign()->setArea($areaBefore);
        $this->getApp()->loadArea($areaBefore);

        $this->logMessage("Finished execution of TX status with ID {$txStatus->getId()} and action {$txStatus->getTxaction()}.", $storeId);
    }

    /**
     * Handles all transaction status that were failed this cron job run
     * and sends a reporting email to the configured recipient.
     */
    private function handleFailed()
    {
        $failedIds = array();
        $failedTxIds = array();

        foreach ($this->processedTxStatus as $id => $txStatus) {
            if ($txStatus->getProcessingStatus()) {
                $failedIds[] = $id;
                $failedTxIds[] = $txStatus->getTxid();
            }
        }

        if (!empty($failedIds)) {
            $failedIds = join(', ', $failedIds);
            $failedTxIds = join(', ', $failedTxIds);

            $this->getFactory()->helperEmail()->send(
                'general',
                $this->processReportEmail,
                false,
                'payone_transaction_status_error_report',
                [
                    'failedIds' => $failedIds,
                    'failedTxIds' => $failedTxIds,
                ]
            );
        }
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
}
