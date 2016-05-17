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

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_Configuration_Wizard
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_Configuration_Wizard_PageController
    extends Payone_Core_Controller_Adminhtml_Configuration_Wizard_Abstract
{
    public function preDispatch()
    {
        parent::preDispatch();

        if ($this->getRequest()->getParam('section')) {
            $this->_isSectionAllowedFlag = $this->_isSectionAllowed($this->getRequest()->getParam('section'));
        }

        return $this;
    }

    public function indexAction()
    {
        $this->initConfig('index');

        $this->loadLayout();
        $this->renderLayout();
    }

    public function generalGlobalAction()
    {
        $this->_editAction('generalGlobal');
    }

    public function riskAddressCheckAction()
    {
        $this->_editAction('riskAddressCheck');
    }

    public function riskCreditratingAction()
    {
        $this->_editAction('riskCreditrating');
    }

    protected function _editAction($actionName)
    {
        // $actionName = $this->getRequest()->getActionName(); // @comment we could use ActionName from Request
        $this->initConfig($actionName);

        $configPage = Mage::registry('payone_wizard_config_page');

        Mage::getSingleton('adminhtml/config_data')
            ->setSection($configPage->getData('codes/section'))
            ->setWebsite($configPage->getData('codes/website'))
            ->setStore($configPage->getData('codes/store'));

        $this->loadLayout('adminhtml_payonecore_configuration_wizard_page_edit');
        $this->renderLayout();
    }

    public function finishAction()
    {
        $this->initConfig('finish');

        $this->loadLayout();
        $this->renderLayout();
    }

    public function _redirectByPageConfig()
    {
        $url = $this->helperWizard()->getNextPageUrlAsString();
        $this->_redirect($url, array('_current' => true));
    }

    /**
     * Save configuration
     *
     */
    public function saveAction()
    {
        $pageCode = $this->getRequest()->getParam('page_code');

        $config = $this->initConfig($pageCode);

        /* @var $session Mage_Adminhtml_Model_Session */
        $session = Mage::getSingleton('adminhtml/session');

        try {
            // $section = $this->getRequest()->getParam('section');
            $section = $config->getData('codes/section'); // Section is defined by Action
            $website = $this->getRequest()->getParam('website');
            $store = $this->getRequest()->getParam('store');
            $groups = $this->getRequest()->getPost('groups');

            if (!$this->_isSectionAllowed($section)) {
                throw new Exception(Mage::helper('payone_core')->__('This section is not allowed.'));
            }

            /**
             * @var $configData Mage_Adminhtml_Model_Config_Data
             */
            $configData = Mage::getModel('adminhtml/config_data');
            $configData->setSection($section)
                    ->setWebsite($website)
                    ->setStore($store)
                    ->setGroups($groups)
                    ->save();

            // reinit configuration
            Mage::getConfig()->reinit();
            Mage::app()->reinitStores();

            // website and store codes can be used in event implementation, so set them as well
            $params = array('website' => $website, 'store' => $store);
            Mage::dispatchEvent("admin_system_config_changed_section_{$section}", $params);
            Mage::dispatchEvent("admin_system_config_changed_section_{$section}_{$pageCode}", $params);

            $session->addSuccess(Mage::helper('payone_core')->__('The configuration has been saved.'));
        }
        catch (Mage_Core_Exception $e) {
            foreach (explode("\n", $e->getMessage()) as $message) {
                $session->addError($message);
            }
        }
        catch (Exception $e) {
            $msg = Mage::helper('payone_core')->__('An error occurred while saving:') . ' ' . $e->getMessage();
            $session->addException($e, $msg);
        }

        // $this->_saveState($this->getRequest()->getPost('config_state'));

        $this->_redirectByPageConfig();
    }

}