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
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_Configuration_Wizard
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
require_once 'Payone/Core/controllers/Adminhtml/Payonecore/System/Config/PaymentController.php';

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_Configuration_Wizard
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_Configuration_Wizard_Page_PaymentController
    extends Payone_Core_Adminhtml_Payonecore_System_Config_PaymentController
{
    /**
     *
     */
    public function indexAction()
    {
        $this->initConfig('payment');

        parent::indexAction();
    }

    /**
     * @param $actionName
     * @return Varien_Object
     */
    protected function initConfig($actionName)
    {
        return $this->helperWizard()->initConfig($actionName, $this->getRequest());
    }

    /**
     * @return Payone_Core_Helper_Wizard
     */
    public function helperWizard()
    {
        return Mage::helper('payone_core/wizard');
    }
}