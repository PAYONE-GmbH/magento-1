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
class Payone_Core_Block_Adminhtml_System_Config_Logos_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_defaultLimit    = 200;
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('payone_core_system_config_logos_grid');
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
        /** @var $collection Payone_Core_Model_Domain_Resource_Config_Logos_Collection */
        $collection = $this->getConfigLogosCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Payone_Core_Model_Domain_Resource_Config_Logos_Collection
     */
    protected function getConfigLogosCollection()
    {
        if (!Mage::registry('payone_core_adminhtml_system_config_logos_collection')) {
            $logosConfigCollection = $this->getPayoneFactory()->getModelDomainConfigLogos()->getCollection();
            Mage::register('payone_core_adminhtml_system_config_logos_collection', $logosConfigCollection);
        }

        return Mage::registry('payone_core_adminhtml_system_config_logos_collection');
    }

    /**
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'image',
            array(
                'header' => $this->helperPayoneCore()->__('Image'),
                'align' => 'left',
                'index' => 'image',
                'width' => '100px',
                'renderer' => 'Payone_Core_Block_Adminhtml_System_Config_ImageRenderer',
                'filter' => false,
                'sortable' => false,
            )
        );

        $this->addColumn(
            'label',
            array(
                'header' => $this->helperPayoneCore()->__('Label'),
                'align' => 'left',
                'index' => 'label',
                'filter' => false,
            )
        );

        $this->addColumn(
            'size',
            array(
                'header' => $this->helperPayoneCore()->__('Size'),
                'align' => 'left',
                'index' => 'size',
                'filter' => false,
            )
        );

        $this->addColumn(
            'type',
            array(
                'header' => $this->helperPayoneCore()->__('Type'),
                'align' => 'left',
                'index' => 'type',
                'filter' => false,
            )
        );

        $this->addColumn(
            'path',
            array(
                'header' => $this->helperPayoneCore()->__('Image path'),
                'align' => 'left',
                'index' => 'path',
                'filter' => false,
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
                'id' => $row->getId()
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