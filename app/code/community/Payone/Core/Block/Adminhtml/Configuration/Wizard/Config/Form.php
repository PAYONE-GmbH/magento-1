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
 * @subpackage      Adminhtml_Configuration
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Configuration
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_Configuration_Wizard_Config_Form
    extends Mage_Adminhtml_Block_System_Config_Form
{
    /**
     * @var string
     */
    protected $groupName = '';

    protected function _prepareLayout()
    {
        $return = parent::_prepareLayout();

        $this->initForm();

        return $return;
    }

    /**
     *
     * @return Mage_Adminhtml_Block_System_Config_Form
     */
    public function initForm()
    {
        $this->_initObjects();

        $form = $this->_initForm();

        $sections = $this->_configFields->getSection(
            $this->getSectionCode(),
            $this->getWebsiteCode(),
            $this->getStoreCode()
        );
        $groups = $sections->groups;

        $groupName = $this->getGroupName();
        $group = $groups->$groupName;

        /**
         * @var $fieldsetRenderer Mage_Adminhtml_Block_System_Config_Form_Fieldset
         */
        if ($group->frontend_model) {
            $fieldsetRenderer = Mage::getBlockSingleton((string)$group->frontend_model);
        }
        else {
            $fieldsetRenderer = Mage::getBlockSingleton('Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset');
        }

        $fieldsetConfig = array(
            'legend' => Mage::helper('payone_core')->__((string)$group->label),
        );
        if (!empty($group->comment)) {
            $fieldsetConfig['comment'] = Mage::helper('payone_core')->__((string)$group->comment);
        }

        /** @var $fieldset Varien_Data_Form_Element_Fieldset */
        $fieldset = $form->addFieldset($sections->getName() . '_' . $group->getName(), $fieldsetConfig);
        $fieldset->setRenderer($fieldsetRenderer);

        $fieldsetRenderer->setForm($this);
        $fieldsetRenderer->setConfigData($this->_configData);
        $fieldsetRenderer->setGroup($group);

        $this->_prepareFieldOriginalData($fieldset, $group);
        $this->_addElementTypes($fieldset);

        $this->initFields($fieldset, $group, $sections);

        $fieldset->addField(
            'page_code',
            'hidden',
            array(
                'name' => 'page_code',
                'value' => $this->getPageCode()
            )
        );

        $form->setUseContainer(true);

        $this->setForm($form);
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
                'action' => $this->getSaveUrl(),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );
        return $form;
    }

    protected function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('_current' => true));
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->getConfigPage('group_name');
    }

    /**
     * @return string
     */
    public function getPageCode()
    {
        return $this->getConfigPage('codes/page');
    }

    /**
     * @return string
     */
    public function getSectionCode()
    {
        return $this->getConfigPage('codes/section');
    }

    /**
     * @return string
     */
    public function getStoreCode()
    {
        return $this->getConfigPage('codes/store');
    }

    /**
     * @return string
     */
    public function getWebsiteCode()
    {
        return $this->getConfigPage('codes/website');
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function getConfigPage($path)
    {
        $config = $this->helperWizard()->getConfigPage();
        return $config->getData($path);
    }

    /**
     * @return Payone_Core_Helper_Wizard
     */
    public function helperWizard()
    {
        return Mage::helper('payone_core/wizard');
    }
}
