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
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_Protocol_Api_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('protocol_api_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        /**
         * @var Payone_Core_Model_Domain_Resource_Protocol_Api_Collection $collection
         */
        $collection = Mage::getModel('payone_core/domain_protocol_api')->getCollection();

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
                'filter' => false,
                'index' => 'id',
            ));

        $this->addColumn(
            'request',
            array(
                'header' => $this->helperPayone()->__('Request'),
                'index' => 'request',
                'type' => 'options',
                'options' => $this->getFactory()->getModelSystemConfigRequestType()->toSelectArray()
            ));

        $this->addColumn(
            'response',
            array(
                'header' => $this->helperPayone()->__('Response'),
                'index' => 'response',
                'type' => 'options',
                'options' => $this->getFactory()->getModelSystemConfigResponseType()->toSelectArray()
            ));

        $this->addColumn(
            'mode',
            array(
                'header' => $this->helperPayone()->__('Modus'),
                'index' => 'mode',
                'type' => 'options',
                'options' => $this->getFactory()->getModelSystemConfigMode()->toSelectArray()
            ));

        $this->addColumn(
            'mid',
            array(
                'header' => $this->helperPayone()->__('Merchant-ID'),
                'type' => 'text',
                'index' => 'mid',
            ));

        $this->addColumn(
            'portalid',
            array(
                'header' => $this->helperPayone()->__('Portal ID'),
                'type' => 'text',
                'index' => 'portalid',
            ));

        $this->addColumn(
            'created_at',
            array(
                'header' => $this->helperPayone()->__('Created at'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '80px',
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
            ));

        if (Mage::getSingleton('admin/session')->isAllowed('payone/protocol/api/view')) {
            $this->addColumn('action',
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
                ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('payone/protocol/api/export')) {
            $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
            $this->addExportType('*/*/exportCsvRaw', Mage::helper('sales')->__('CSV (including raw)'));
            $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));
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
    public function getFactory(){
        return $this->helperPayone()->getFactory();
    }

}
