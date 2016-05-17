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
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_System_Config_PaymentController
    extends Payone_Core_Controller_Adminhtml_Abstract
{
    /**
     * @return Payone_Core_Adminhtml_System_Config_PaymentController
     */
    protected function _initAction()
    {
        $this->loadLayout();
        return $this;
    }

    /**
     *
     */
    public function indexAction()
    {
        $websiteCode = $this->getRequest()->getParam('website');

        $showButtons = true;
        if ($websiteCode) {
            $showButtons = false;
        }

        Mage::register('show_new_payment_buttons', $showButtons);

        $this->_initAction();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            Mage::getBlockSingleton('payone_core/adminhtml_system_config_payment_grid')->toHtml()
        );
    }

    /**
     *
     */
    public function newAction()
    {
        $type = $this->getRequest()->getParam('type');
        if ($type == null) {
            $this->_redirect('*/*/', array('_current' => true));
        }

        $this->_forward('edit');
    }

    /**
     *
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $website = $this->getRequest()->getParam('website');
        $store = $this->getRequest()->getParam('store');
        $type = $this->getRequest()->getParam('type');

        /** @var $model Payone_Core_Model_Domain_Config_PaymentMethod */
        $model = $this->getModelDomainConfigPaymentMethod()->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            $model->setWebsite($website);
            $model->setStore($store);
            $model->setCode($type);

            Mage::register('payone_core_config_payment_method', $model);
            Mage::register('payone_core_config_active_scope', $this->determineActiveScope($website, $store));


            $this->loadLayout();

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->renderLayout();
        }
        else {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->helper()->__('PaymentMethod-Config does not exist.')
            );
            $this->_redirect('*/*/', array('_current' => true));
        }
    }

    /**
     *
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getParam('groups');
        $website = $this->getRequest()->getParam('website');
        $store = $this->getRequest()->getParam('store');
        $type = $this->getRequest()->getParam('type');

        if ($data) {
            /** @var $model Payone_Core_Model_Domain_Config_PaymentMethod */
            $model = $this->getModelDomainConfigPaymentMethod();
            $model->setWebsite($website);
            $model->setStore($store);
            $model->setCode($type);
            $model->setGroups($data);
            $model->setId($this->getRequest()->getParam('id'));

            try {
                $model->save();
                
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->helper()->__('PaymentMethod-Config was successfully saved.')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if($model->getCode() == 'ratepay' && $model->getId()) { // redirect to edit-page so that the ratepay shop-IDs get requested from API
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                
                $this->_redirect('*/*/', array('_current' => true));
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), '_current' => true));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            $this->helper()->__('Unable to find PaymentMethod-Config to save.')
        );
        $this->_redirect('*/*/', array('_current' => true));
    }

    /**
     *
     */
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id > 0) {
            $data = $this->getRequest()->getParam('groups');
            $website = $this->getRequest()->getParam('website');
            $store = $this->getRequest()->getParam('store');
            $type = $this->getRequest()->getParam('type');


            try {
                if ($this->determineActiveScope($website, $store) != 'default') {
                   // Deleting payment configs is only allowed in default scope, go back to grid.
                   $this->_redirect('*/*/index',  array('website' => $website, 'store' => $store));
                    return;
                }

                /** @var $model Payone_Core_Model_Domain_Config_PaymentMethod */
                $model = $this->getModelDomainConfigPaymentMethod();
                $model->setWebsite($website);
                $model->setStore($store);
                $model->setCode($type);
                $model->setGroups($data);
                $model->setId($id);
                $model->setIsDeleted(1);
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('PaymentMethod Config was successfully deleted.')
                );
                $this->_redirect('*/*/', array('_current' => true));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id, '_current' => true));
            }
        }
        $this->_redirect('*/*/', array('_current' => true));
    }

    /**
     * Determine active scope (not payment config scope, but the scope the admin is currently editing.)
     *
     * @param string $website
     * @param string $store
     * @return string
     */
    protected function determineActiveScope($website = '', $store = '')
    {
        if($store)
            return 'stores';
        if ($website)
            return 'websites';
        return 'default';
    }

    /**
     * @return Payone_Core_Model_Domain_Config_PaymentMethod
     */
    protected function getModelDomainConfigPaymentMethod()
    {
        return $this->getFactory()->getModelDomainConfigPaymentMethod();
    }
}