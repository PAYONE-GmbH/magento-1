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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Adminhtml_System_Config_Form_Button
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /** @var bool $_disabled */
    private $_disabled = false;

    /** @var string $_elementId */
    private $_elementId = '';

    /** @var string $_label */
    private $_label = '';

    /** @var string $_route */
    private $_route = '';

    /**
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/system/config/form/button.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElementId($element->getId());
        $this->setDisabled($element->getData('disabled') === true);
        $this->setLabel($element->getData('original_data')['button_label']);
        $this->setRoute($element->getData('original_data')['route']);
        return $this->_toHtml();
    }

    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxButtonUrl()
    {
        return $this->getUrl($this->getRoute(), ['_current' => true]);
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        /** @var Varien_Data_Form_Element_Button $button */
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData([
                'id'        => $this->getElementId(),
                'disabled'  => $this->isDisabled(),
                'label'     => Mage::helper('payone_core')->__($this->getLabel()),
                'onclick'   => "javascript:{$this->getElementId()}_click(); return false;"
            ]);

        return $button->toHtml();
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->_disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled($disabled)
    {
        $this->_disabled = $disabled;
    }

    /**
     * @return mixed
     */
    public function getElementId()
    {
        return $this->_elementId;
    }

    /**
     * @param mixed $elementId
     */
    public function setElementId($elementId)
    {
        $this->_elementId = $elementId;
    }

    /**
     * @return string
     */
    protected function getLabel()
    {
        return $this->_label;
    }

    /**
     * @param string $_label
     */
    protected function setLabel($_label)
    {
        $this->_label = $_label;
    }

    /**
     * @return string
     */
    protected function getRoute()
    {
        return $this->_route;
    }

    /**
     * @param string $_route
     */
    protected function setRoute($_route)
    {
        $this->_route = $_route;
    }

}
