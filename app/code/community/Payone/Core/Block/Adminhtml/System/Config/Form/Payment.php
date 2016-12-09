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
class Payone_Core_Block_Adminhtml_System_Config_Form_Payment extends Mage_Adminhtml_Block_Abstract
{
    public function _construct()
    {
        $this->setData('template', 'payone/core/system/config/form/iframe.phtml');
        parent::_construct();
    }


    public function setParentBlock(Mage_Core_Block_Abstract $block)
    {
        $block->unsetChild('save_button');
        return parent::setParentBlock($block);
    }

    /**
     * only needed to implement required interface from Adminhtml_System_Config_Edit::initForm for frontend_model
     *
     * @return Payone_Core_Block_Adminhtml_System_Config_Form_Payment
     */
    public function initForm()
    {
        return $this;
    }

    public function getSourceUrl()
    {
        $url = $this->getUrl('adminhtml/payonecore_system_config_payment/', array('_current' => true));
        return $url;
    }

}
