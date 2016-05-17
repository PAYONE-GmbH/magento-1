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
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Transaction
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Transaction
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_Transaction_View_Tab_TransactionStatus extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('payone_core_adminhtml_transaction_view_tab_transactionstatus');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setSkipGenerateContent(true);
    }

    /**
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('payone_core')->__('Transactionstatus');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax';
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('payone_core')->__('Transactionstatus');
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/transactionStatusGrid', array('_current' => true));
    }
    /**
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('*/*/transactionStatusGrid', array('_current' => true));
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/adminhtml_protocol_transactionStatus/view', array('id' => $row->getId()));
    }


    /**
     * @return Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection
     */
    public function getTransactionStatusCollection()
    {
        return Mage::registry('payone_core_transactionstatus_collection');
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->getTransactionStatusCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            array(
                'header' => $this->helperPayoneCore()->__('ID'),
                'width' => '5px',
                'type' => 'text',
                'index' => 'id',
                'filter' => false,
            ));

        $this->addColumn(
            'txid',
            array(
                'header' => $this->helperPayoneCore()->__('Txid'),
                'type' => 'text',
                'index' => 'txid',
            ));

        $this->addColumn(
            'reference',
            array(
                'header' => $this->helperPayoneCore()->__('Reference'),
                'type' => 'text',
                'sortable' => false,
                'filter' => false,
                'index' => 'reference',
            ));

        $this->addColumn(
            'txaction',
            array(
                'header' => $this->helperPayoneCore()->__('Txaction'),
                'index' => 'txaction',
                'type' => 'options',
                'options' => $this->getPayoneFactory()->getModelSystemConfigTransactionStatus()->toSelectArray()
            ));

        $this->addColumn(
            'txtime',
            array(
                'header' => $this->helperPayoneCore()->__('Txtime'),
                'index' => 'txtime',
                'type' => 'text',
                'sortable' => false,
                'filter' => false,
            ));

        $this->addColumn(
            'sequencenumber',
            array(
                'header' => $this->helperPayoneCore()->__('Sequencenumber'),
                'index' => 'sequencenumber',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            ));

        $this->addColumn(
            'clearingtype',
            array(
                'header' => $this->helperPayoneCore()->__('Clearingtype'),
                'index' => 'clearingtype',
                'sortable' => false,
                'filter' => false,
                'type' => 'options',
                'options' => $this->getPayoneFactory()->getModelSystemConfigClearingType()->toSelectArray()
            ));

        $this->addColumn(
            'mode',
            array(
                'header' => $this->helperPayoneCore()->__('Mode'),
                'index' => 'mode',
                'sortable' => false,
                'filter' => false,
                'type' => 'options',
                'options' => $this->getPayoneFactory()->getModelSystemConfigMode()->toSelectArray()
            ));

        $this->addColumn(
            'portalid',
            array(
                'header' => $this->helperPayoneCore()->__('Portal-ID'),
                'index' => 'portalid',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            ));

        $this->addColumn(
            'receivable',
            array(
                'header' => $this->helperPayoneCore()->__('Receivable'),
                'index' => 'receivable',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            ));

        $this->addColumn(
            'balance',
            array(
                'header' => $this->helperPayoneCore()->__('Balance'),
                'index' => 'balance',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            ));

        $this->addColumn(
            'created_at',
            array(
                'header' => $this->helperPayoneCore()->__('Created at'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '80px',
                'filter' => false,
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
            ));

        $this->addColumn(
            'updated_at',
            array(
                'header' => $this->helperPayoneCore()->__('Updated at'),
                'index' => 'updated_at',
                'type' => 'datetime',
                'width' => '80px',
                'filter' => false,
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
            ));

        return parent::_prepareColumns();
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayoneCore()
    {
        return Mage::helper('payone_core');
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getPayoneFactory(){
        return $this->helperPayoneCore()->getFactory();
    }

}