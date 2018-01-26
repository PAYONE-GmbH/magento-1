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
class Payone_Core_Block_Adminhtml_System_Config_Logos_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'payone_core';
        $this->_controller = 'adminhtml_system_config_logos';

        $this->_updateButton('save', 'label', Mage::helper('payone_core')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('payone_core')->__('Delete'));
        $this->_addButton(
            'save_and_edit_button',
            array(
                'label'     => Mage::helper('payone_core')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save'
            ),
            100
        );

        $this->setData('edit_form_id', 'edit_form_logos');
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        $headerText = 'Add ';
        if ($this->getRequest()->getParam('id')) {
            $headerText = 'Edit ';
        }

        return $this->helperPayone()->__($headerText);
    }

    /**
     * @return Payone_Core_Helper_Data
     */
    public function helperPayone()
    {
        return $this->getFactory()->helper();
    }

    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }
}