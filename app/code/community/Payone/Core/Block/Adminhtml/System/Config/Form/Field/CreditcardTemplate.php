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
class Payone_Core_Block_Adminhtml_System_Config_Form_Field_CreditcardTemplate
    extends Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract
{
    
    protected $_aFcpoDefaultStringConf = array(
        'Number_type' => 'tel',
        'Number_count' => '30',
        'Number_max' => '16',
        'Number_iframe' => 'standard',
        'Number_width' => '202px',
        'Number_height' => '20px',
        'Number_style' => 'standard',
        'Number_css' => '',
        'CVC_type' => 'tel',
        'CVC_count' => '30',
        'CVC_max' => '4',
        'CVC_iframe' => 'standard',
        'CVC_width' => '202px',
        'CVC_height' => '20px',
        'CVC_style' => 'standard',
        'CVC_css' => '',
        'Month_type' => 'select',
        'Month_count' => '3',
        'Month_max' => '2',
        'Month_iframe' => 'custom',
        'Month_width' => '120px',
        'Month_height' => '20px',
        'Month_style' => 'standard',
        'Month_css' => '',
        'Year_type' => 'select',
        'Year_count' => '5',
        'Year_max' => '4',
        'Year_iframe' => 'custom',
        'Year_width' => '120px',
        'Year_height' => '20px',
        'Year_style' => 'standard',
        'Year_css' => '',
        'Iframe_width' => '365px',
        'Iframe_height' => '30px',
        'Standard_input' => "width:365px;height:30px;padding: 0 8px;color:#636363;font-size:14px;font-family:'Helvetica Neue',Verdana,Arial,sans-serif;",
        'Standard_selection' => 'width:100px;',
    );
    
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('payone/core/system/config/form/field/creditcard_template.phtml');
    }
    
    /**
     *
     */
    protected function _prepareToRender()
    {
        $this->addColumn('Number_type', array(
            'label' =>'',
            'style' => '',
        ));
        $this->addColumn('Number_count', array(
            'label' =>'',
            'style' => '',
        ));
        $this->addColumn('Number_max', array(
            'label' =>'',
            'style' => '',
        ));
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
        if ($columnName == 'txactions') {
            $selectType = Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract::PAYONE_CORE_FIELD_MULTISELECT;

            $modelConfigCode = $this->getFactory()->getModelSystemConfigTransactionStatus();
            $options = $modelConfigCode->toOptionArray();

            $rendered = $this->prepareCellTemplate($columnName,$selectType,$options);
        }
        else
        {
            return parent::_renderCellTemplate($columnName);
        }

        return $rendered;
    }

    public function getCCFields() {
        return array(
            'Number',
            'CVC',
            'Month',
            'Year',
        );
    }
    
    public function getCCStyles() {
        return array(
            'standard' => Mage::helper('payone_core')->__('Standard'),
            'custom' => Mage::helper('payone_core')->__('Custom'),
        );
    }
    
    public function getCCTypes($sField) {
        $aTypes = array();
        if($sField == 'Month' || $sField == 'Year') {
            $aTypes['select'] = Mage::helper('payone_core')->__('Select');
        }
        $aTypes['tel'] = Mage::helper('payone_core')->__('Numeric');
        $aTypes['password'] = Mage::helper('payone_core')->__('Password');
        $aTypes['text'] = Mage::helper('payone_core')->__('Text');
        return $aTypes;
    }
    
    public function fcpoGetValue($sIdent) {
        $aValues = $this->getElement()->getValue();
        if(isset($aValues[$sIdent])) {
            return $aValues[$sIdent];
        } elseif(isset($this->_aFcpoDefaultStringConf[$sIdent])) {
            return $this->_aFcpoDefaultStringConf[$sIdent];
        }
        return '';
    }
    
}
