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
class Payone_Core_Block_Adminhtml_Configuration_Wizard_Page_View
    extends Mage_Adminhtml_Block_Widget_View_Container
{
    /**
     *
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_configuration_wizard_page';

        parent::__construct();

        $this->_removeButton('edit');
        $this->_removeButton('back');

        $this->_addButton(
            'cancel', array(
            'label' => Mage::helper('adminhtml')->__('Cancel'),
            'onclick' => 'parent.window.wizardPopup.close()',
            'class' => 'default'
            )
        );

        $this->_addButton(
            'back', array(
            'label' => Mage::helper('adminhtml')->__('Back'),
            'onclick' => 'window.location.href=\'' . $this->getBackUrl() . '\'',
            'class' => 'default',
            )
        );

        $this->_addButton(
            'save', array(
            'label' => Mage::helper('adminhtml')->__('Continue'),
            'class' => 'default',
            'onclick' => 'window.location.href=\'' . $this->getNextUrl() . '\'',
            )
        );

        $this->setId('wizard_page_view');
    }

    public function getViewHtml()
    {
        $html = '';
        foreach ($this->getSortedChildren() as $childName) {
            $child = $this->getChild($childName);
            /** @var $child Mage_Adminhtml_Block_Abstract */
            $html .= $child->toHtml();
        }

        return $html;
    }

    protected function getBackUrl()
    {
        $url = $this->helperWizard()->getPreviousPageUrlAsString();
        return $this->getUrl($url, array('_current' => true));
    }

    protected function getNextUrl()
    {
        $url = $this->helperWizard()->getNextPageUrlAsString();
        return $this->getUrl($url, array('_current' => true));
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
    
    protected function _prepareLayout()
    {
        foreach ($this->_buttons as $level => $buttons) {
            foreach ($buttons as $id => $data) {
                $childId = $this->_prepareButtonBlockId($id);
                $this->_addButtonChildBlock($childId);
            }
        }

        $this->setChild('plane', $this->getLayout()->createBlock('payone_core/adminhtml_configuration_wizard_page_view_plane'));
        return $this;
    }
    
}