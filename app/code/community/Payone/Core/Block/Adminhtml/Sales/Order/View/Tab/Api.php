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
class Payone_Core_Block_Adminhtml_Sales_Order_View_Tab_Api
    extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('payone_core_block_adminhtml_sales_order_view_tab_api');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setSkipGenerateContent(true);
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('payone_core')->__('Protocol - Api');
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return '<span class="payone-tab">' . Mage::helper('payone_core')->__('Protocol - Api') . '</span>';
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax payone-widget-tab';
    }

    /**
     * @return string
     */
    public function getAfter()
    {
        return 'payone_core_adminhtml_sales_order_view_tab_transactionStatus';
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/payonecore_protocol_api/view', array('id' => $row->getId()));
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('adminhtml/payonecore_sales_order/apiGrid', array('_current' => true));
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('adminhtml/payonecore_sales_order/apiGrid', array('_current' => true));
    }

    /**
     * @return Payone_Core_Block_Adminhtml_Sales_Order_View_Tab_Api
     */
    protected function _prepareCollection()
    {
        $collection = $this->getApiCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return mixed
     */
    public function getApiCollection()
    {
        if (!Mage::registry('payone_core_protocol_api_collection')) {
            $order = $this->getOrder();

            /** @var $apiCollection Payone_Core_Model_Domain_Resource_Protocol_Api_Collection */
            $apiCollection = $this->getPayoneFactory()->getModelApi()->getCollection();
            $apiCollection->getByOrder($order);
            Mage::register('payone_core_protocol_api_collection', $apiCollection);
        }

        return Mage::registry('payone_core_protocol_api_collection');
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
                'filter' => false,
                'index' => 'id',
            )
        );

        $this->addColumn(
            'request',
            array(
                'header' => $this->helperPayoneCore()->__('Request'),
                'sortable' => false,
                'filter' => false,
                'index' => 'request',
                'type' => 'options',
                'options' => $this->getPayoneFactory()->getModelSystemConfigRequestType()->toSelectArray()
            )
        );

        $this->addColumn(
            'response',
            array(
                'header' => $this->helperPayoneCore()->__('Response'),
                'sortable' => false,
                'filter' => false,
                'index' => 'response',
                'type' => 'options',
                'options' => $this->getPayoneFactory()->getModelSystemConfigResponseType()->toSelectArray()
            )
        );

        $this->addColumn(
            'mode',
            array(
                'header' => $this->helperPayoneCore()->__('Mode'),
                'type' => 'text',
                'index' => 'mode',
            )
        );

        $this->addColumn(
            'mid',
            array(
                'header' => $this->helperPayoneCore()->__('Merchant-ID'),
                'type' => 'text',
                'index' => 'mid',
            )
        );

        $this->addColumn(
            'portalid',
            array(
                'header' => $this->helperPayoneCore()->__('Portal-ID'),
                'type' => 'text',
                'index' => 'portalid',
            )
        );

        $this->addColumn(
            'aid',
            array(
                'header' => $this->helperPayoneCore()->__('Sub-Account-ID'),
                'type' => 'text',
                'index' => 'aid',
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