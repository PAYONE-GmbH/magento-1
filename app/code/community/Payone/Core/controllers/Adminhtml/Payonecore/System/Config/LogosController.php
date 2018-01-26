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
class Payone_Core_Adminhtml_Payonecore_System_Config_LogosController
    extends Payone_Core_Controller_Adminhtml_Abstract
{
    protected $acl_resource = 'payone/configuration/logos';

    /**
     * @return Payone_Core_Adminhtml_Payonecore_System_Config_LogosController
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
        $this->_initAction();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            Mage::getBlockSingleton('payone_core/adminhtml_system_config_logos_grid')->toHtml()
        );
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $website = $this->getRequest()->getParam('website');
        $store = $this->getRequest()->getParam('store');

        $id = $this->getRequest()->getParam('id');

        /** @var $model Payone_Core_Model_Domain_Config_Logos */
        $model = $this->getModelDomainConfigLogos()->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('payone_core_config_logo', $model);
            Mage::register('payone_core_config_active_scope', $this->determineActiveScope($website, $store));

            $this->loadLayout();

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                $this->helper()->__('Logo-Config does not exist.')
            );
            $this->_redirect('*/*/', array('_current' => true));
        }
    }

    /**
     * Note : File store by default to media/payone/default, therefore there is no distinction by store or website
     */
    public function saveAction()
    {
        $params = $this->getRequest()->getParams();
        $id = $this->getRequest()->getParam('id');
        $label = $this->getRequest()->getParam('label');
        $size =  $this->getRequest()->getParam('size');
        $type =  $this->getRequest()->getParam('type');
        $path = $this->getRequest()->getParam('path');
        $active = $this->getRequest()->getParam('enabled');
        $inputFileName = $_FILES['fileinputname']['name'];

        try {
            $validity = $this->validateBeforeSave($label, size, $type, $path, $active, $inputFileName);
            if ($validity) {
                if (!empty($inputFileName) && $type == Payone_Core_Model_System_Config_LogoType::FILE) {
                    $filepath = Mage::getBaseDir('media') . '/payone/default/';

                    $uploader = new Varien_File_Uploader('fileinputname');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'))
                        ->setAllowRenameFiles(false)
                        ->setFilesDispersion(false);
                    $uploader->save($filepath, $inputFileName);

                    $path = 'payone/default/' . $inputFileName;
                }

                /** @var Payone_Core_Model_Domain_Config_Logos $model */
                $model = $this->getModelDomainConfigLogos()
                    ->setId($id)
                    ->setLabel($label)
                    ->setSize($size)
                    ->setType($type)
                    ->setPath($path)
                    ->setEnabled($active);
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->helper()->__('Logo was successfully saved.')
                );

                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/', array('_current' => true));
                return;
            }
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->setFormData($params);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), '_current' => true));
            return;
        }

        Mage::getSingleton('adminhtml/session')->addError(
            $this->helper()->__('Unable to save data.')
        );
        $this->_redirect('*/*/', array('_current' => true));
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id > 0) {
            $params = $this->getRequest()->getParams();

            try {
                /** @var $model Payone_Core_Model_Domain_Config_PaymentMethod */
                $model = $this->getModelDomainConfigLogos();
                $model->setId($id);
                $model->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Logo was successfully deleted.')
                );
                $this->_redirect('*/*/', array('_current' => true));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->setFormData($params);
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
        if ($store) {
            return 'stores';
        }
        if ($website) {
            return 'websites';
        }
        return 'default';
    }

    /**
     * @return Payone_Core_Model_Domain_Config_Logos
     */
    protected function getModelDomainConfigLogos()
    {
        return $this->getFactory()->getModelDomainConfigLogos();
    }

    /**
     * @param string $label
     * @param string $type
     * @param string $path
     * @param string $active
     * @param $inputFileName
     * @return bool
     *
     * @throws Exception
     */
    protected function validateBeforeSave($label, $size, $type, $path, $active, $inputFileName)
    {
        if (empty($label) || empty($type) || empty($size) || is_null($active)) {
            throw new Exception('Missing required values');
        }

        if (empty($path)) {
            if ($type == Payone_Core_Model_System_Config_LogoType::URL) {
                throw new Exception('Path is mandatory for "Online Image" type');
            }

            if (empty($inputFileName) && $type == Payone_Core_Model_System_Config_LogoType::FILE) {
                throw new Exception('Upload file is mandatory for "Filesystem" type');
            }
        }

        return true;
    }
}
