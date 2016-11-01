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
class Payone_Core_Block_Adminhtml_System_Config_Payment extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     *
     */
    public function __construct()
    {
        $this->_blockGroup = 'payone_core';
        $this->_controller = 'adminhtml_system_config_payment';
        $this->_headerText = $this->helperPayone()->__('');
        $this->_addButtonLabel = $this->helperPayone()->__('Add Payment');
        parent::__construct();
        $this->_removeButton('add');

        $showButtons = Mage::registry('show_new_payment_buttons');
        if ($showButtons) {
            $methodTypes = $this->getFactory()->getModelSystemConfigPaymentMethodType()->toArray();
            foreach ($methodTypes as $key => $name) {
                $this->_addNewMethodButton($key, $name);
            }
        }

        $this->setTemplate('payone/core/system/config/payment/grid/container.phtml');
    }

    /**
     * @return string
     */
    public function getHintHtml()
    {
        /** @var $config Mage_Adminhtml_Model_Config */
        $config = Mage::getSingleton('adminhtml/config');
        $configSection = $config->getSection('payone_payment');
        $hintGroup = $configSection->groups->hint;

        /** @var $hint Payone_Core_Block_Adminhtml_System_Config_Hint */
        $hint = Mage::getBlockSingleton('payone_core/adminhtml_system_config_hint');
        $hint->setGroup($hintGroup);
        $html = $hint->getHintHtml();
        return $html;
    }

    /**
     * @param $type
     * @param $name
     */
    public function _addNewMethodButton($type, $name)
    {
        $this->_addButton(
            'new_' . $type, array(
            'label' => $this->helperPayone()->__('New ' . $name),
            'onclick' => 'setLocation(\'' . $this->getNewUrl(array('type' => $type)) . '\')',
            'class' => 'add',
            )
        );
    }

    /**
     * @param array $params
     * @return string
     */
    public function getNewUrl(array $params = array())
    {
        $params = array_merge($params, array('_current' => true, 'id' => ''));
        return $this->getUrl('*/*/new', $params);
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayone()
    {
        return Mage::helper('payone_core');
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        return $this->helperPayone()->getFactory();
    }

}