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
class Payone_Core_Block_Adminhtml_Transaction_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('transaction_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        /**
         * @var Payone_Core_Model_Domain_Resource_Transaction_Collection $collection
         */
        $collection = Mage::getModel('payone_core/domain_transaction')->getCollection();

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
                'header' => $this->helperPayone()->__('ID'),
                'width' => '5px',
                'type' => 'text',
                'index' => 'id',
                'filter' => false,
            )
        );

        $this->addColumn(
            'txid',
            array(
                'header' => $this->helperPayone()->__('Txid'),
                'type' => 'text',
                'index' => 'txid',
            )
        );

        $this->addColumn(
            'reference',
            array(
                'header' => $this->helperPayone()->__('Reference'),
                'type' => 'text',
                'index' => 'reference',
            )
        );

        $this->addColumn(
            'txaction',
            array(
                'header' => $this->helperPayone()->__('Last Txaction'),
                'index' => 'last_txaction',
                'type' => 'options',
                'options' => $this->getFactory()->getModelSystemConfigStatusTransaction()->toSelectArray()
            )
        );

        $this->addColumn(
            'txtime',
            array(
                'header' => $this->helperPayone()->__('Txtime'),
                'index' => 'txtime',
                'type' => 'datetime',
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_unixTimestamp',
            )
        );

        $this->addColumn(
            'clearingtype',
            array(
                'header' => $this->helperPayone()->__('Clearingtype'),
                'index' => 'clearingtype',
                'type' => 'options',
                'options' => $this->getFactory()->getModelSystemConfigClearingType()->toSelectArray()
            )
        );

        $this->addColumn(
            'mode',
            array(
                'header' => $this->helperPayone()->__('Mode'),
                'index' => 'mode',
                'type' => 'options',
                'options' => $this->getFactory()->getModelSystemConfigMode()->toSelectArray()
            )
        );

        $this->addColumn(
            'portalid',
            array(
                'header' => $this->helperPayone()->__('Portal-ID'),
                'index' => 'portalid',
                'type' => 'text',
            )
        );

        $this->addColumn(
            'receivable',
            array(
                'header' => $this->helperPayone()->__('Receivable'),
                'index' => 'receivable',
                'type' => 'text',
            )
        );

        $this->addColumn(
            'balance',
            array(
                'header' => $this->helperPayone()->__('Balance'),
                'index' => 'balance',
                'type' => 'text',
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => $this->helperPayone()->__('Created at'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '80px',
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
            )
        );

        $this->addColumn(
            'updated_at',
            array(
                'header' => $this->helperPayone()->__('Updated at'),
                'index' => 'updated_at',
                'type' => 'datetime',
                'width' => '80px',
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
            )
        );

        if (Mage::getSingleton('admin/session')->isAllowed('payone/transaction/view')) {
            $this->addColumn(
                'action',
                array(
                    'header' => $this->helperPayone()->__('Action'),
                    'width' => '50px',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => $this->helperPayone()->__('View'),
                            'url' => array('base' => '*/*/view'),
                            'field' => 'id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
                )
            );
        }

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getId()));
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayone()
    {
        return Mage::helper('payone_core');
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        return $this->helperPayone()->getFactory();
    }

}
