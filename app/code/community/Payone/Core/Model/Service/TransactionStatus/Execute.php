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

        return $countExecuted;
    }

    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     */
    public function execute(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $storeId = $transactionStatus->getStoreId();

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

        try {
            $this->getServiceProcess()->execute($transactionStatus);
            $transactionStatus->setStatusOk();
        } catch (Exception $e) {
            $transactionStatus->setStatusError();
            $transactionStatus->setProcessingError($e->getMessage());
        }

        $transactionStatus->setProcessedAt(date('Y-m-d H:i:s'));
        $transactionStatus->save();

        // Reset Store
        $this->getApp()->setCurrentStore($storeBefore);

        // Reset Area to old Area
        $this->getDesign()->setArea($areaBefore);
        $this->getApp()->loadArea($areaBefore);
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
}