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
class Payone_Core_Block_Adminhtml_System_Config_Notice
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * @var Mage_Core_Model_Config_Element
     */
    protected $configGroup = null;
    /**
     * @var Varien_Data_Form_Element_Abstract
     */
    protected $element = null;

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->element = $element;

        if ($this->getTemplate() == null) {
            $this->initTemplate();
        }

        $html = '';
        if ($this->getTemplate() != '') {
            $html = $this->toHtml();
        }

        return $html;
    }

    protected function initTemplate()
    {
        $hintElement = $this->getHintElement();

        $template = '';
        if ($hintElement instanceof Mage_Core_Model_Config_Element) {
            /** @var $hintElement Mage_Core_Model_Config_Element */
            $template = (string)$hintElement->template;
        }

        if ($template != '') {
            $this->setTemplate($template);
        }
    }

    protected function getHintElement()
    {
        if ($this->configGroup === null) {
            $this->initConfigGroup();
        }

        return $this->configGroup->hint;
    }

    protected function initConfigGroup()
    {
        $this->configGroup = $this->getGroup();

        if ($this->configGroup === null) {
            $elementGroup = $this->element->getGroup();
            $this->configGroup = $elementGroup;
        }
    }

}
