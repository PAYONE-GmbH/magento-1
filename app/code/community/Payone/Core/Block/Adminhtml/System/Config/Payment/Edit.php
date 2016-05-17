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
class Payone_Core_Block_Adminhtml_System_Config_Payment_Edit
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
        $this->_blockGroup = '';
        $this->_controller = 'adminhtml_system_config_payment';

        $this->_updateButton('save', 'label', Mage::helper('payone_core')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('payone_core')->__('Delete'));

        $this->setData('edit_form_id', 'edit_form_payment_method');
    }

    protected function _prepareLayout()
    {
        $type = $this->getPaymentMethodType();

        $blockClass = 'payone_core/adminhtml_system_config_form_payment_method';

        /**
         * @var $block Payone_Core_Block_Adminhtml_System_Config_Form_Payment_Method
         */
        $block = $this->getLayout()->createBlock($blockClass);
        if ($block) {
            $block->setMethodType($type);
            $block->initForm();

            $this->setChild('form', $block);
        }
        $activeScope = Mage::registry('payone_core_config_active_scope');
        if ($activeScope != 'default') {
          $this->_removeButton('delete'); // Not allowed to delete configs from scopes "websites" or "stores".
        }


        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        $type = $this->getPaymentMethodType();
        $method = uc_words($type, ' ');
        $headerText = 'Add ';
        if ($this->getRequest()->getParam('id')) {
            $headerText = 'Edit ';
        }
        $headerText .= $method;
        return $this->helperPayone()->__($headerText);
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/', array('_current' => true));
    }

    public function getDeleteUrl()
    {
        $id = $this->getRequest()->getParam('id');
        return $this->getUrl('*/*/delete', array('_current' => true, 'id' => $id));
    }

    /**
     * @return string
     * @throws Payone_Core_Exception_PaymentTypeNotFound
     */
    protected function getPaymentMethodType()
    {
        $id = $this->getRequest()->getParam('id');

        $model = $this->getFactory()->getModelDomainConfigPaymentMethod();
        $model->load($id);

        $type = $model->getCode();

        if ($type == '') {
            $type = $this->getRequest()->getParam('type');
            if ($type == '') {
                throw new Payone_Core_Exception_PaymentTypeNotFound();
            }
        }
        return $type;
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

    /**
     * @return Payone_Core_Helper_Data
     */
    public function helperPayone()
    {
        return $this->getFactory()->helper();
    }
}