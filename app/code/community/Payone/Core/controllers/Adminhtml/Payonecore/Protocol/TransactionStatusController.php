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
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_Protocol_TransactionStatusController
    extends Payone_Core_Controller_Adminhtml_Abstract
{
    protected $acl_resource = 'payone/protocol/transaction_status';
    
    /**
     * @return Payone_Core_Adminhtml_Protocol_TransactionStatusController
     */
    protected function _initAction()
    {
        $this->loadLayout();
        $this->setUsedModuleName('payone_core');
        $this->_setActiveMenu('payone');
        $this->_addBreadcrumb(
            Mage::helper('payone_core')->__('Payone'),
            Mage::helper('payone_core')->__('Payone')
        );

        $this->_title(Mage::helper('payone_core')->__('Payone'));
        $this->_title(Mage::helper('payone_core')->__('Protocol - TransactionStatus'));

        return $this;
    }

    /**
     *
     */
    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('payone_core/adminhtml_protocol_transactionStatus_grid')->toHtml()
        );
    }

    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var $modelTransactionStatus Payone_Core_Model_Domain_Protocol_TransactionStatus */
        $modelTransactionStatus = Mage::getModel('payone_core/domain_protocol_transactionStatus')->load($id);
        if ($modelTransactionStatus->getId() || $id == 0) {
            Mage::register('payone_core_protocol_transactionstatus', $modelTransactionStatus);

            /** @var $modelTransaction Payone_Core_Model_Domain_Transaction */
            $modelTransaction = Mage::getModel('payone_core/domain_transaction');
            $modelTransaction->loadByTransactionStatus($modelTransactionStatus);

            Mage::register('payone_core_protocol_transaction', $modelTransaction);

            $this->_initAction();

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_title(sprintf("#%s", $id));

            $this->renderLayout();
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('payone_core')->__('TransactionStatus does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'protocol_transaction_status.csv';

        /**
         * @var $grid Payone_Core_Block_Adminhtml_Protocol_TransactionStatus_Grid
         */
        $grid = $this->getLayout()->createBlock('payone_core/adminhtml_protocol_transactionStatus_grid');

        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     * Export order grid to XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'protocol_transaction_status.xls';

        /**
         * @var $grid Payone_Core_Block_Adminhtml_Protocol_TransactionStatus_Grid
         */
        $grid = $this->getLayout()->createBlock('payone_core/adminhtml_protocol_transactionStatus_grid');

        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile());
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvRawAction()
    {
        $fileName = 'protocol_transaction_status_raw.csv';

        /**
         * @var $collection Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection
         */
        $collection = Mage::getModel('payone_core/domain_protocol_transactionStatus')->getCollection();

        $serviceExport = $this->getFactory()->getServiceProtocolTransactionStatusExport();
        $csv = $serviceExport->exportCsv($collection);

        $this->_prepareDownloadResponse($fileName, $csv);
    }

}