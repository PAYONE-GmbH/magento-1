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
class Payone_Core_Block_Adminhtml_System_Config_Form_Field_PaymentLogosMapping
    extends Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract
{
    protected $aAllowedMethodsForLogo = array(
        Payone_Core_Model_System_Config_PaymentMethodCode::CREDITCARD
    );

    public function __construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/system/config/form/field/array.phtml');
    }

    /**
     *
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'method', array(
            'label' => Mage::helper('payone_core')->__('Method'),
            'style' => 'width:175px;',
            )
        );

        $this->addColumn(
            'logo', array(
            'label' => Mage::helper('payone_core')->__('Logo'),
            'style' => 'width:175px',
            )
        );

        $this->addColumn(
            'size', array(
                'label' => Mage::helper('payone_core')->__('Size'),
                'style' => 'width:125px',
            )
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('payone_core')->__('Add');
        parent::_prepareToRender();
    }

    /**
     * @param string $columnName
     * @return string
     */
    protected function _renderCellTemplate($columnName)
    {
        $selectType = Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract::PAYONE_CORE_FIELD_SELECT;
        if ($columnName == 'method') {
            $aOptions = array();
            $modelConfigCode = $this->getFactory()->getModelDomainConfigPaymentMethod()->getCollection();
            foreach ($modelConfigCode as $paymentMethod) {
                if(!$paymentMethod->getEnabled()) {
                    continue;
                }
                if (!in_array('payone_'.$paymentMethod->getCode(), $this->aAllowedMethodsForLogo)) {
                    continue;
                }

                $aTypes = $paymentMethod->getTypes();
                if(empty($aTypes)) {
                    $aOptions[$paymentMethod->getCode()] = $paymentMethod->getName();
                }
                else {
                    foreach ($aTypes as $sType) {
                        $aOptions[$paymentMethod->getCode() . '_' . $sType] = $paymentMethod->getName() . ' - ' . $sType;
                    }
                }
            }
        }
        elseif ($columnName == 'logo')
        {
            $modelConfigCode = $this->getFactory()->getModelDomainConfigLogos();
            $aOptions = $modelConfigCode->toOptionArray(true);
        }
        elseif ($columnName == 'size')
        {
            $modelConfigCode = $this->getFactory()->getModelLogoSize();
            $aOptions = $modelConfigCode->toArray();
        }
        else {
            return parent::_renderCellTemplate($columnName);
        }

        $rendered = $this->prepareCellTemplate($columnName, $selectType, $aOptions);

        return $rendered;
    }
}
