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
class Payone_Core_Block_Adminhtml_System_Config_Form_Field
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    /**
     * Enter description here...
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $hint = (array) $element->getFieldConfig()->hint;
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

}
