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
class Payone_Core_Block_Adminhtml_System_Config_Logos extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     *
     */
    public function __construct()
    {
        $this->_blockGroup = 'payone_core';
        $this->_controller = 'adminhtml_system_config_logos';
        $this->_headerText = $this->helperPayone()->__('');
        $this->_addButtonLabel = $this->helperPayone()->__('Add Logo');
        parent::__construct();

        $this->setTemplate('payone/core/system/config/logo/grid/container.phtml');
    }

    /**
     * TODO VB FIX Complete Hint or remove
     *
     * @return string
     */
    public function getHintHtml()
    {
        /** @var $config Mage_Adminhtml_Model_Config */
        $config = Mage::getSingleton('adminhtml/config');
        $configSection = $config->getSection('payone_logos');
        $hintGroup = $configSection->groups->hint;

        /** @var $hint Payone_Core_Block_Adminhtml_System_Config_Hint */
        $hint = Mage::getBlockSingleton('payone_core/adminhtml_system_config_hint');
        $hint->setGroup($hintGroup);
        $html = $hint->getHintHtml();
        return $html;
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