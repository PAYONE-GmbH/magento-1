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
class Payone_Core_Block_Adminhtml_System_Config_Form_Payment_Method
    extends Mage_Adminhtml_Block_System_Config_Form
{
    const SECTION_PAYONE_PAYMENT = 'payone_payment';
    const GROUP_TEMPLATE_PREFIX = 'template_';
    const GROUP_TEMPLATE_DEFAULT = 'default';

    /**
     * @var string
     */
    protected $_methodType = '';

    /**
     *
     * @return Mage_Adminhtml_Block_System_Config_Form
     */
    protected function _initObjects()
    {
        /** @var $_configRoot Mage_Core_Model_Config_Element */
        $this->_configRoot = Mage::getConfig()->getNode(null, $this->getScope(), $this->getScopeCode());

        $this->_configDataObject = Mage::getModel('adminhtml/config_data')
                ->setSection($this->getSectionCode())
                ->setWebsite($this->getWebsiteCode())
                ->setStore($this->getStoreCode());

        $this->_configData = $this->_configDataObject->load();

        /** @var $model Payone_Core_Model_Domain_Config_PaymentMethod */
        $model = Mage::registry('payone_core_config_payment_method');
        $parentModel = $model->getParentModel();

        if ($parentModel) {
            $grandParentModel = $parentModel->getParentModel();

            foreach ($parentModel->getData() as $key => $parentValue)
            {
                $path = self::SECTION_PAYONE_PAYMENT . '/' . self::GROUP_TEMPLATE_PREFIX . $this->getMethodType() . '/' . $key;
                $modelValue = $model->getData($key);

                if (isset($modelValue)) {
                    $value = $modelValue;
                }
                elseif (isset($parentValue)) {
                    $value = $parentValue;
                }
                elseif ($grandParentModel) {
                    $value = $grandParentModel->getData($key);
                }

                if(isset($value))
                {
                    if(is_array($value))
                        $value = serialize($value);
                    $this->_configRoot->setNode($path, $value, true);
                }
            }
        }

        $this->_configData = $model->initConfigObject($this->getMethodType(), $this->getScope());

        $this->_configFields = Mage::getSingleton('adminhtml/config');

        $this->_defaultFieldsetRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_fieldset');
        $this->_defaultFieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        return $this;
    }

    /**
     * @return Varien_Data_Form
     */
    protected function _initForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl(
                    '*/*/save',
                    array(
                        'id' => $this->getRequest()->getParam('id'),
                        '_current' => true
                    )
                ),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );
        return $form;
    }

    /**
     *
     * @return Mage_Adminhtml_Block_System_Config_Form
     */
    public function initForm()
    {
        $this->_initObjects();

        $form = $this->_initForm();

        /**
         * @var $sections Mage_Core_Model_Config_Element
         */
        $sections = $this->_configFields->getSection(
            self::SECTION_PAYONE_PAYMENT,
            $this->getWebsiteCode(),
            $this->getStoreCode()
        );
        $groups = $sections->groups;

        $groupName = self::GROUP_TEMPLATE_PREFIX . $this->getMethodType();
        $group = $groups->$groupName;
        
        $groupNameDefault = self::GROUP_TEMPLATE_PREFIX . self::GROUP_TEMPLATE_DEFAULT;
        $groupDefault = $groups->$groupNameDefault;

        /**
         * @var $fieldsetRenderer Mage_Adminhtml_Block_System_Config_Form_Fieldset
         */
        if ($group->frontend_model) {
            $fieldsetRenderer = Mage::getBlockSingleton((string)$group->frontend_model);
        }
        else {
            $fieldsetRenderer = $this->_defaultFieldsetRenderer;
        }

        $fieldsetConfig = array(
            'legend' => Mage::helper('payone_core')->__((string)$group->label),
            'expanded' => true,
        );
        if (!empty($group->comment)) {
            $fieldsetConfig['comment'] = Mage::helper('payone_core')->__((string)$group->comment);
        }

        if (!empty($group->expanded)) {
            $fieldsetConfig['expanded'] = (bool)$group->expanded;
        }

        $fieldset = $form->addFieldset($sections->getName() . '_' . $group->getName(), $fieldsetConfig);
        $fieldset->setRenderer($fieldsetRenderer);

        $fieldsetRenderer->setForm($this);
        $fieldsetRenderer->setConfigData($this->_configData);
        $fieldsetRenderer->setGroup($group);

        $this->_prepareFieldOriginalData($fieldset, $group);
        $this->_addElementTypes($fieldset);

        foreach ($groupDefault->fields as $elements) {
            foreach ($elements as $e) {
                // Check if the node already exists. If it does, do not append, default is lower in the hierarchy.
                $name= $e->getName();
                /** @var $e Mage_Core_Model_Config_Element */
                /** @var $configElement Mage_Core_Model_Config_Element */
                $configElement = $group->fields->$name;
                if(empty($configElement)
                    || !$configElement->hasChildren())
                        $group->fields->appendChild($e);
            }
        }

        $this->initFields($fieldset, $group, $sections);

        $form->setUseContainer(true);
        $this->setForm($form);
        return $this;
    }

    /**
     * @param string $methodType
     */
    public function setMethodType($methodType)
    {
        $this->_methodType = $methodType;
    }

    /**
     * @return string
     */
    public function getMethodType()
    {
        return $this->_methodType;
    }

}