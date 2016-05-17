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
 * Do not edit or add to this file if you wish to upgrade Payone_Migrator to newer
 * versions in the future. If you wish to customize Payone_Migrator for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Migrator_controllers
 * @subpackage      Adminhtml_Configuration_Wizard
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Migrator_controllers
 * @subpackage      Adminhtml_Configuration_Wizard
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Migrator_Adminhtml_Payonemigrator_Wizard_MigrationController
    extends Payone_Core_Controller_Adminhtml_Configuration_Wizard_Abstract
{
    public function indexAction()
    {
        if (!$this->helperConfig()->haveToMigrate()) {
            $this->_forward('index', 'adminhtml_configuration_wizard_page', 'payone_core');
            return;
        }

        $this->initConfig('migrator');

        $layout = $this->getLayout();

        $layout->getUpdate()->load('adminhtml_payonemigrator_migration_wizard_page_migrator');

        $layout->generateXml();
        $layout->generateBlocks();
        $output = $layout->getOutput();

        $this->getResponse()->setBody($output);
    }

    /**
     * @return Payone_Migrator_Helper_Config
     */
    protected function helperConfig()
    {
        return Mage::helper('payone_migrator/config');
    }

}