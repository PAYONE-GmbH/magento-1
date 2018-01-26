<?php

class Payone_Core_Block_Adminhtml_System_Config_Logos_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('payone_core_config_logo');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('payone_core')->__('General'),
            'class'     => 'fieldset',
        ));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('enabled', 'select', array(
            'name'      => 'enabled',
            'label'     => Mage::helper('checkout')->__('Active'),
            'title'     => Mage::helper('checkout')->__('Active'),
            'required'  => true,
            'options'	=> $this->_getYesNoArray(),
        ));
        $fieldset->addField('label', 'text', array(
            'name'      => 'label',
            'label'     => Mage::helper('checkout')->__('Label'),
            'title'     => Mage::helper('checkout')->__('Label'),
            'required'  => true,
        ));
        $fieldset->addField('size', 'select', array(
            'label'     => Mage::helper('checkout')->__('Size category'),
            'title'     => Mage::helper('checkout')->__('Size category'),
            'name'      => 'size',
            'required'  => true,
            'options'   => $this->_getSizeArray(),
        ));
        $fieldset->addField('type', 'select', array(
            'label'     => Mage::helper('checkout')->__('Source type'),
            'title'     => Mage::helper('checkout')->__('Source type'),
            'name'      => 'type',
            'required'  => true,
            'options'   => $this->_getTypesArray(),
        ));
        $fieldset->addField('path', 'text', array(
            'name'      => 'path',
            'label'     => Mage::helper('checkout')->__('Path'),
            'title'     => Mage::helper('checkout')->__('Path'),
        ));
        $fieldset->addField('fileinputname', 'image', array(
            'name'      => 'fileinputname',
            'label'     => Mage::helper('checkout')->__('Upload'),
            'title'     => Mage::helper('checkout')->__('Upload'),
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * only needed to implement required interface from Adminhtml_System_Config_Edit::initForm for frontend_model
     *
     * @return Payone_Core_Block_Adminhtml_System_Config_Logos_Edit_Form
     */
    public function initForm()
    {
        return $this;
    }

    protected function _getTypesArray()
    {
        /** @var Payone_Core_Model_System_Config_LogoType $types */
        $types = Mage::getSingleton('payone_core/system_config_logoType');
        return $types->toSelectArray();
    }

    protected function _getSizeArray()
    {
        /** @var Payone_Core_Model_System_Config_LogoSize $sizes */
        $sizes = Mage::getSingleton('payone_core/system_config_logoSize');
        return $sizes->toSelectArray();
    }

    protected function _getYesNoArray()
    {
        /** @var Mage_Adminhtml_Model_System_Config_Source_Yesno $options */
        $options = Mage::getSingleton('adminhtml/system_config_source_Yesno');
        return $options->toArray();
    }

}