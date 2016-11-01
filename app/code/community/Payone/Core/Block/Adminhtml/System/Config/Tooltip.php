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
class Payone_Core_Block_Adminhtml_System_Config_Tooltip extends Mage_Adminhtml_Block_Template
{
    const DEFAULT_BLOCK_CLASS = 'adminhtml/template';
    /**
     * @var Varien_Data_Form_Element_Abstract
     */
    protected $element = null;
    /**
     * @var Mage_Core_Model_Config_Element
     */
    protected $fieldConfig = null;

    protected $label = null;
    protected $block = null;
    protected $blockTemplate = null;

    public function _construct()
    {
        $this->setData('template', 'payone/core/system/config/tooltip.phtml');
        parent::_construct();
    }

    public function getTitle()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getHintHtml()
    {
        $hintHtml = null;
        if ($this->block != '' or $this->blockTemplate != '') {
            $hintHtml = $this->renderBlock();
        }

        if ($hintHtml === null) {
            $hintHtml = $this->getHint();
        }

        return $hintHtml;
    }

    protected function renderBlock()
    {
        $hintHtml = '';
        $block = $this->block;
        $template = $this->blockTemplate;
        if ($block == '') {
            $block = self::DEFAULT_BLOCK_CLASS;
        }

        $hintBlock = $this->getLayout()->createBlock($block);
        if ($hintBlock != null) {
            $hintBlock->setParentBlock($this);
            if ($template != '') {
                $hintBlock->setTemplate($template);
            }

            if (is_callable(array($hintBlock, 'toHtml'))) {
                $hintHtml = $hintBlock->toHtml();
            }
        }

        return $hintHtml;
    }

    public function initFormElement(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        /** @var $fieldConfig Mage_Core_Model_Config_Element */
        $fieldConfig = $element->getFieldConfig();
        $this->setFieldConfig($fieldConfig);

        /** @var $hint string */
        $hint = $element->getHint();
        $this->setHint($hint);

        // Init
        $this->label = $element->getLabel();

        if ($fieldConfig != null) {
            $hintElement = $fieldConfig->hint;
            if ($hintElement instanceof Mage_Core_Model_Config_Element) {
                $this->initByHintElement($hintElement);
            }
        }
    }

    public function initByHintElement(Mage_Core_Model_Config_Element $hintElement)
    {
        /** @var $hintElement Mage_Core_Model_Config_Element */
        $this->block = (string)$hintElement->block;
        $this->blockTemplate = (string)$hintElement->template;
    }

    public function getChildHintHtml($template)
    {
        $result = null;
        $templateFile = Mage::getDesign()->getTemplateFilename($template);
        if(file_exists($templateFile))
        {
            $block = $this->getLayout()->createBlock(self::DEFAULT_BLOCK_CLASS);
            $block->setParentBlock($this);
            $block->setTemplate($template);
            $result = $block->toHtml();
        }

        return $result;
    }

    /**
     * @param string $hint
     */
    public function setHint($hint)
    {
        $this->setData('hint', $hint);
    }

    /**
     * @return string
     */
    public function getHint()
    {
        return $this->getData('hint');
    }

    /**
     * @param string $hint
     */
    public function setHintText($hint)
    {
        $this->setData('hint_text', $hint);
    }

    /**
     * @return string
     */
    public function getHintText()
    {
        return $this->getData('hint_text');
    }

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     */
    public function setElement(Varien_Data_Form_Element_Abstract $element)
    {
        $this->element = $element;
    }

    /**
     * @return Varien_Data_Form_Element_Abstract
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @param Mage_Core_Model_Config_Element $fieldConfig
     */
    public function setFieldConfig($fieldConfig)
    {
        $this->fieldConfig = $fieldConfig;
    }

    /**
     * @return Mage_Core_Model_Config_Element
     */
    public function getFieldConfig()
    {
        return $this->fieldConfig;
    }
}
