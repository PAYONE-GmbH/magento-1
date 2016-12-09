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
 * @subpackage      Adminhtml
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_TransactionController extends Payone_Core_Controller_Adminhtml_Abstract
{
    protected $acl_resource = 'payone/transaction';
    
    /**
     * @return Payone_Core_Adminhtml_TransactionController
     */
    protected function _initAction()
    {
        $this->loadLayout();
        $this->setUsedModuleName('payone_core');
        $this->_setActiveMenu('payone');
        $this->_addBreadcrumb(
            $this->helper()->__('Payone'),
            $this->helper()->__('Payone')
        );

        $this->_title($this->helper()->__('Payone'));
        $this->_title($this->helper()->__('Transaction'));

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

    /**
     *
     */
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('payone_core/adminhtml_transaction_grid')->toHtml()
        );
    }

    /**
     *
     */
    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var $transactionModel Payone_Core_Model_Domain_Transaction */
        $transactionModel = Mage::getModel('payone_core/domain_transaction')->load($id);
        if ($transactionModel->getId() || $id == 0) {
            Mage::register('payone_core_transaction', $transactionModel);

            /** @var $transactionStatusCollection Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection */
            $transactionStatusCollection = Mage::getModel('payone_core/domain_protocol_transactionStatus')
                ->getCollection();
            $transactionStatusCollection->getByTransaction($transactionModel);

            Mage::register('payone_core_transactionstatus_collection', $transactionStatusCollection);

            $this->_initAction();

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_title(sprintf("#%s", $id));

            $this->renderLayout();
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->helper()->__('Transaction does not exist')
            );
            $this->_redirect('*/*/');
        }
    }

    /**
     *
     */
    public function transactionStatusGridAction()
    {
        $id = $this->getRequest()->getParam('id');
        $transactionModel = Mage::getModel('payone_core/domain_transaction')->load($id);

        /** @var $transactionStatusCollection Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection */
        $transactionStatusCollection = Mage::getModel('payone_core/domain_protocol_transactionStatus')
            ->getCollection();
        $transactionStatusCollection->getByTransaction($transactionModel);

        Mage::register('payone_core_transactionstatus_collection', $transactionStatusCollection);

        $this->getResponse()->setBody(
            Mage::getBlockSingleton('payone_core/adminhtml_transaction_view_tab_transactionStatus')
                ->toHtml()
        );
    }
}