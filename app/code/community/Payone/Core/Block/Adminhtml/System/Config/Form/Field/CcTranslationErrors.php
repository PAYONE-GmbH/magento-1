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
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */
class Payone_Core_Block_Adminhtml_System_Config_Form_Field_CcTranslationErrors
    extends Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract
{

    protected function _prepareToRender()
    {
        $this->addColumn(
            'translation_type', array(
            'label' => Mage::helper('payone_core')->__('Text'),
            'style' => 'width: 160px',
            )
        );
        $this->addColumn(
            'translation_language', array(
            'label' => Mage::helper('payone_core')->__('Language'),
            'style' => 'width: 160px',
            )
        );
        $this->addColumn(
            'translation_message', array(
            'label' => Mage::helper('payone_core')->__('Translation'),
            'style' => 'width: 160px',
            )
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('payone_core')->__('Add');
        parent::_prepareToRender();
    }

    /**
     * @param $columnName
     * @return string
     * @throws Exception
     */
    protected function _renderCellTemplate($columnName)
    {
        if ($columnName == 'translation_type') {
            $selectType = Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract::PAYONE_CORE_FIELD_SELECT;

            $modelConfigCode = $this->getFactory()->getModelSystemConfigTranslationErrors();
            $options = $modelConfigCode->toOptionArray();

            return $this->prepareCellTemplate($columnName, $selectType, $options);
        } elseif($columnName == 'translation_language') {
            $selectType = Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract::PAYONE_CORE_FIELD_SELECT;

            $modelConfigCode = $this->getFactory()->getModelSystemConfigLocale();
            $options = $modelConfigCode->toOptionArray();
            $options = $this->_getFormattedLanguageOptions($options);
            
            return $this->prepareCellTemplate($columnName, $selectType, $options);
        } else {
            return parent::_renderCellTemplate($columnName);
        }
    }

}
