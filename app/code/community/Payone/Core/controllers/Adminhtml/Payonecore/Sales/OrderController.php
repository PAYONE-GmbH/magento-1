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
 * @subpackage      Adminhtml_Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

require_once 'Mage' . DS . 'Adminhtml' . DS . 'controllers' . DS . 'Sales' . DS . 'OrderController.php';

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{

    /**
     *
     */
    public function transactionStatusGridAction()
    {
        $this->_initOrder();
        $this->getResponse()->setBody(
            Mage::getBlockSingleton('payone_core/adminhtml_sales_order_view_tab_transactionStatus')->toHtml()
        );
    }

    /**
     *
     */
    public function apiGridAction()
    {
        $this->_initOrder();
        $this->getResponse()->setBody(
            Mage::getBlockSingleton('payone_core/adminhtml_sales_order_view_tab_api')->toHtml()
        );
    }

    /**
     *
     */
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('payone_core/adminhtml_sales_order_grid')->toHtml()
        );
    }

    /**
     * @return Mage_Adminhtml_Sales_OrderController| Payone_Core_Adminhtml_Sales_OrderController
     */
    protected function _initAction()
    {
        $this->loadLayout();
        $this->setUsedModuleName('payone_core');
        $this->_setActiveMenu('payone');
        $this->_addBreadcrumb(
            Mage::helper('payone_core')->__('Payone'),
            Mage::helper('payone_core')->__('Payone')
        );

        $this->_title($this->helperPayoneCore()->__('Payone'));
        $this->_title($this->helperPayoneCore()->__('Order'));

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

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayoneCore()
    {
        return Mage::helper('payone_core');
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getPayoneFactory()
    {
        return $this->helperPayoneCore()->getFactory();
    }
}