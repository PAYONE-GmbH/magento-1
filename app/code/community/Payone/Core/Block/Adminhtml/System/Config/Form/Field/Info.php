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
class Payone_Core_Block_Adminhtml_System_Config_Form_Field_Info
    extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Render element html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $hint = (array)$element->getFieldConfig()->hint;
        if (!count($hint)) {
            return $this->getHtmlTable($element);
        }

        // Generate Tooltip
        /** @var $tooltip Payone_Core_Block_Adminhtml_System_Config_Tooltip */
        $tooltip = $this->getLayout()->createBlock('payone_core/adminhtml_system_config_tooltip');
        $tooltip->initFormElement($element);
        $tooltip->setHintText($this->getHintText());
        $tooltipHtml = $tooltip->toHtml();

        $html = sprintf('<tr id="row_%s">', $element->getHtmlId());
        $html .= '<td class=""  colspan="3">';
        $html .= '<table class="payone-config-info"><tr><td>' . $tooltipHtml .' </td></tr></table>';
        $html .= '</td>';
        $html .= '</tr>';

        return $html;
    }

    protected function getHintText()
    {
        $text = $this->helper('payone_core')
                ->__('Click here to obtain more information on this section');
        return $text;
    }
}
