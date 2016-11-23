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
class Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /** @var $factory Payone_Core_Model_Factory */
    protected $factory;
    const PAYONE_CORE_FIELD_MULTISELECT = 'multi';
    const PAYONE_CORE_FIELD_SELECT = 'select';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('payone/core/system/config/form/field/array.phtml');
    }

    /**
     * Enter description here...
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $hint = (array)$element->getFieldConfig()->hint;
        if (!count($hint)) {
            return parent::render($element);
        }

        // Generate Tooltip
        /** @var $tooltip Payone_Core_Block_Adminhtml_System_Config_Tooltip */
        $tooltip = $this->getLayout()->createBlock('payone_core/adminhtml_system_config_tooltip');
        $tooltip->initFormElement($element);
        $tooltipHtml = $tooltip->toHtml();

        $element->setHint('__tooltip_html__');

        $html = parent::render($element);

        $hintToReplace = '<div class="hint" ><div style="display: none;">__tooltip_html__</div></div>';
        $html = str_replace($hintToReplace, $tooltipHtml, $html);

        return $html;
    }

    /**
     * @param $columnName string
     * @param $selectType string
     * @param $values array
     * @return string
     * @throws Exception
     */
    public function prepareCellTemplate($columnName, $selectType, $values)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }

        $column = $this->_columns[$columnName];
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . '][]';

        // Write html for a multiselect input:
        if ($selectType === self::PAYONE_CORE_FIELD_MULTISELECT) {
            $definition = '" multiple="multiple" class="select multiselect"';
        }
        elseif ($selectType === self::PAYONE_CORE_FIELD_SELECT) {
            $definition = '" class="select"';
        }
        else {
            throw new Exception('Unknown Type for Select');
        }

        $html = '<select name="' . $inputName . $definition . (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . ' >';
        foreach ($values as $key => $option) {
            if (!is_array($option)) {
                $html .= $this->_optionToHtml(
                    array(
                        'value' => $key,
                        'label' => $option)
                );
            }
            elseif (is_array($option['value'])) {
                $html .= '<optgroup label="' . $option['label'] . '">';
                foreach ($option['value'] as $groupItem) {
                    $html .= $this->_optionToHtml($groupItem);
                }

                $html .= '</optgroup>';
            }
            else {
                $html .= $this->_optionToHtml($option);
            }
        }

        $html .= '</select>';

        return $html;
    }

    protected function _optionToHtml($option)
    {
        if (is_array($option['value'])) {
            $html = '<optgroup label="' . $option['label'] . '">';
            foreach ($option['value'] as $groupItem) {
                $html .= $this->_optionToHtml($groupItem);
            }

            $html .= '</optgroup>';
        }
        else {
            $html = '<option value="' .($option['value']) . '"';
//            $html .= isset($option['title']) ? 'title="' . ($option['title']) . '"' : '';
//            $html .= isset($option['style']) ? 'style="' . $option['style'] . '"' : '';
            $html .= '>' . ($option['label']) . '</option>';
        }

        return $html;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if (null === $this->factory) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }

    protected function _getFormattedLanguageTitle($sTitle)
    {
        $iPos = strpos($sTitle, "(");
        if($iPos) {
            $sTitle = substr($sTitle, 0, $iPos);
        }

        return trim($sTitle);
    }
    
    protected function _getFormattedLanguageOptions($aOptions)
    {
        $aReturn = array();
        $aAddedLanguages = array();
        foreach ($aOptions as $aOption) {
            $sLang = substr($aOption['value'], 0, 2);
            if(array_search($sLang, $aAddedLanguages) === false) {
                $aOption['value'] = $sLang;
                $aOption['label'] = $this->_getFormattedLanguageTitle($aOption['label']);
                $aReturn[] = $aOption;
                $aAddedLanguages[] = $sLang;
            }
        }

        return $aReturn;
    }
    
}
