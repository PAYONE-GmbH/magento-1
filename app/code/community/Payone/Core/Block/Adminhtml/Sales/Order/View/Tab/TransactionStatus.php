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
 * @subpackage      Adminhtml_Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_Sales_Order_View_Tab_TransactionStatus
    extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('payone_core_block_adminhtml_sales_order_view_tab_transactionstatus');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setSkipGenerateContent(true);
    }

    public function getTabTitle()
    {
        return $this->helperPayoneCore()->__('Protocol - TransactionStatus');
    }

    public function getTabLabel()
    {
        return '<span class="payone-tab">' .
                Mage::helper('payone_core')->__('Protocol - Transaction') .
                '</span>';
    }

    public function isHidden()
    {
        return false;
    }

    public function getTabClass()
    {
        return 'ajax payone-widget-tab';
    }

    public function getAfter()
    {
        return 'payone_core_adminhtml_sales_order_view_tab_transaction';
    }

    public function canShowTab()
    {
        return true;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/payonecore_protocol_transactionStatus/view', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('adminhtml/payonecore_sales_order/transactionStatusGrid', array('_current' => true));
    }

    public function getTabUrl()
    {
        return $this->getUrl('adminhtml/payonecore_sales_order/transactionStatusGrid', array('_current' => true));
    }

    protected function _prepareCollection()
    {
        $collection = $this->getTransactionStatusCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function getTransactionStatusCollection()
    {
        if (!Mage::registry('payone_core_protocol_transactionstatus_collection')) {
            $order = $this->getOrder();

            /** @var $transactionStatusCollection Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection */
            $transactionStatusCollection = $this->getPayoneFactory()->getModelTransactionStatus()->getCollection();
            $transactionStatusCollection->getByOrder($order);
            Mage::register('payone_core_protocol_transactionstatus_collection', $transactionStatusCollection);
        }

        return Mage::registry('payone_core_protocol_transactionstatus_collection');
    }

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
            )
        );

        $this->addColumn(
            'txid',
            array(
                'header' => $this->helperPayoneCore()->__('Txid'),
                'width' => '200px',
                'type' => 'text',
                'index' => 'txid',
            )
        );

        $this->addColumn(
            'reference',
            array(
                'header' => $this->helperPayoneCore()->__('Reference'),
                'width' => '5px',
                'type' => 'text',
                'sortable' => false,
                'filter' => false,
                'index' => 'reference',
            )
        );

        $this->addColumn(
            'txaction',
            array(
                'header' => $this->helperPayoneCore()->__('Txaction'),
                'index' => 'txaction',
                'type' => 'options',
                'options' => $this->getPayoneFactory()->getModelSystemConfigTransactionStatus()->toSelectArray()
            )
        );

        $this->addColumn(
            'txtime',
            array(
                'header' => $this->helperPayoneCore()->__('TxTime'),
                'index' => 'txtime',
                'type' => 'text',
                'sortable' => false,
                'filter' => false,
                'width' => '80px',
            )
        );

        $this->addColumn(
            'sequencenumber',
            array(
                'header' => $this->helperPayoneCore()->__('Sequencenumber'),
                'index' => 'sequencenumber',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
                'width' => '80px',
            )
        );

        $this->addColumn(
            'clearingtype',
            array(
                'header' => $this->helperPayoneCore()->__('Clearingtype'),
                'index' => 'clearingtype',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            )
        );

        $this->addColumn(
            'mode',
            array(
                'header' => $this->helperPayoneCore()->__('Mode'),
                'index' => 'mode',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            )
        );

        $this->addColumn(
            'portalid',
            array(
                'header' => $this->helperPayoneCore()->__('Portal-ID'),
                'index' => 'portalid',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            )
        );

        $this->addColumn(
            'receivable',
            array(
                'header' => $this->helperPayoneCore()->__('Receivable'),
                'index' => 'receivable',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            )
        );

        $this->addColumn(
            'balance',
            array(
                'header' => $this->helperPayoneCore()->__('Balance'),
                'index' => 'balance',
                'sortable' => false,
                'filter' => false,
                'type' => 'text',
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => $this->helperPayoneCore()->__('Created at'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '80px',
                'filter' => false,
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
            )
        );

        $this->addColumn(
            'updated_at',
            array(
                'header' => $this->helperPayoneCore()->__('Updated at'),
                'index' => 'updated_at',
                'type' => 'datetime',
                'width' => '80px',
                'filter' => false,
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getPayoneFactory()
    {
        return $this->helperPayoneCore()->getFactory();
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayoneCore()
    {
        return Mage::helper('payone_core');
    }
}