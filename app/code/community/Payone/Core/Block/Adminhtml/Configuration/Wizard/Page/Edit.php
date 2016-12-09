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
class Payone_Core_Block_Adminhtml_Configuration_Wizard_Page_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = '';
        $this->_controller = 'adminhtml_configuration_wizard_page';

        $this->_removeButton('delete');
        $this->_removeButton('reset');
        $this->_removeButton('back');
        $this->_removeButton('save');

        $this->_addButton(
            'cancel', array(
            'label' => Mage::helper('payone_core')->__('Cancel'),
            'onclick' => 'parent.window.wizardPopup.close()',
            'class' => 'default',
            )
        );

        $this->_addButton(
            'back', array(
            'label' => Mage::helper('payone_core')->__('Back'),
            'onclick' => 'setLocation(\'' . $this->getBackUrl() . '\')',
            'class' => 'default',
            )
        );

        $this->_addButton(
            'save', array(
            'label' => Mage::helper('payone_core')->__('Save & Continue'),
            'onclick' => 'editForm.submit();',
            'class' => 'default',
            )
        );

        $this->setData('edit_form_id', 'edit_form_wizard_page');
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        $headerText = $this->getConfigPage('header_text');
        $text = Mage::helper('payone_core')->__($headerText);
        return $text;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $url = $this->helperWizard()->getPreviousPageUrlAsString();
        return $this->getUrl($url, array('_current' => true));
    }

    public function getDeleteUrl()
    {
        return '';
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