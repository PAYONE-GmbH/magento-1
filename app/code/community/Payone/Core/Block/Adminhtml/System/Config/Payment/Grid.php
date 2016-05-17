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
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_System_Config_Payment_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_defaultLimit    = 200;
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('payone_core_system_config_payment_grid');
        $this->setSaveParametersInSession(true);
        // Enable Ajax
        $this->setUseAjax(true);

        $this->setPagerVisibility(false);
        // $this->setHeadersVisibility(false);
        $this->setMessageBlockVisibility(false);
        $this->setFilterVisibility(false);
    }

    /**
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var $collection Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection */
        $collection = $this->getConfigPaymentCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection
     */
    protected function getConfigPaymentCollection()
    {
        if (!Mage::registry('payone_core_adminhtml_system_config_payment_collection')) {
            $storeCode = $this->getRequest()->getParam('store');
            $websiteCode = $this->getRequest()->getParam('website');

            /** @var $store Mage_Core_Model_Website */
            $website = $this->getPayoneFactory()->getModelCoreWebsite();
            $website->load($websiteCode, 'code');

            /** @var $methodConfigCollection Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection */
            $methodConfigCollection = $this->getPayoneFactory()->getModelDomainConfigPaymentMethod()
                    ->getCollection();
            $methodConfigCollection->addSortOrder('id');


            if (empty($storeCode) && $website->hasData()) {

                $methodConfigCollection->getCollectionByScopeIdMerged($website->getId(), 'websites');

            }
            else {
                /** @var $store Mage_Core_Model_Store */
                $store = $this->getPayoneFactory()->getModelCoreStore();
                $store->load($storeCode, 'code');


                if (!$storeCode) {
                    $methodConfigCollection->getCollectionByScopeIdMerged();
                }
                else {
                    $methodConfigCollection->getCollectionByScopeIdMerged($store->getId(), 'stores');
                }
            }
            Mage::register('payone_core_adminhtml_system_config_payment_collection', $methodConfigCollection);
        }
        return Mage::registry('payone_core_adminhtml_system_config_payment_collection');
    }

    /**
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'sort_order',
            array(
                'header' => $this->helperPayoneCore()->__('Sort order'),
                'align' => 'center',
                'width' => '20px',
                'index' => 'sort_order',
                'filter' => false,
                'sortable' => false
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => $this->helperPayoneCore()->__('Name'),
                'align' => 'left',
                'index' => 'name',
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'type',
            array(
                'header' => $this->helperPayoneCore()->__('Type'),
                'align' => 'left',
                'index' => 'code',
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'scope',
            array(
                'header' => $this->helperPayoneCore()->__('Scope'),
                'align' => 'left',
                'index' => 'scope',
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'enabled',
            array(
                'header' => $this->helperPayoneCore()->__('Enabled'),
                'align' => 'left',
                'width' => '80px',
                'index' => 'enabled',
                'type' => 'options',
                'options' => $this->getPayoneFactory()->getModelSystemConfigStatus()->toSelectArray(),
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => $this->helperPayoneCore()->__('Created At'),
                'index' => 'created_at',
                'type' => 'datetime',
                'width' => '50px',
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'updated_at',
            array(
                'header' => $this->helperPayoneCore()->__('Updated At'),
                'index' => 'updated_at',
                'type' => 'datetime',
                'width' => '50px',
                'renderer' => 'payone_core/adminhtml_widget_grid_column_renderer_datetime',
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'id',
            array(
                'header' => $this->helperPayoneCore()->__('ID'),
                'align' => 'right',
                'width' => '5px',
                'index' => 'id',
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'action',

            array(
                'header' => $this->helperPayoneCore()->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'field' => 'id',
                        'caption' => $this->helperPayoneCore()->__('Edit'),
                        'url' => array(
                            'base' => '*/*/edit',
                            'params' => array(
                                '_current' => true,
                                'type' => ''),

                        ),
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array(
                '_current' => true,
                'id' => $row->getId(),
                'type' => $row->getCode()
            )
        );
    }

    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/grid',
            array(
                '_current' => true
            )
        );
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
    public function getPayoneFactory()
    {
        return $this->helperPayoneCore()->getFactory();
    }
}