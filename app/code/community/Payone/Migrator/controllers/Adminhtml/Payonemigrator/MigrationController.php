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
class Payone_Migrator_Adminhtml_Payonemigrator_MigrationController
    extends Payone_Migrator_Controller_Adminhtml_Abstract
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function migrateAction()
    {
        if (!$this->getRequest()->isAjax()) {
            $this->getResponse()
                    ->setHeader('HTTP/1.1', '403 Session Expired')
                    ->setHeader('Login-Required', 'true')
                    ->sendResponse();
            return;
        }

        $step = $this->getRequest()->getParam('step');
        try {
            $status = $this->getServiceMigrate()->migratePart($step);
        }
        catch (Exception $e) {
            $status = false;
            $this->helper()->log($e->getMessage(), Zend_Log::ERR);
            $this->helper()->log($e->getTraceAsString(), Zend_Log::ERR);
        }

        if (!$status) {
            $this->helper()->log('stopping. step ' . $step . ' did not finish successfully', Zend_Log::ERR);
        }

        $result = array();
        $result['next_step'] = $this->getNextStep($step);
        $result['success'] = $status ? true : false;
        $result['error'] = !$status ? true : false;

        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    protected function getNextStep($currentStep)
    {
        return $this->helper()->getNextStep($currentStep);
    }

    /**
     * @return Payone_Migrator_Model_Service_Migrate
     */
    protected function getServiceMigrate()
    {
        return $this->getFactory()->getServiceMigrate();
    }
}