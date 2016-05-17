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
 * @author          Christian Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

require_once 'Mage' . DS . 'Adminhtml' . DS . 'controllers' . DS . 'Sales' . DS . 'Order'. DS .'InvoiceController.php';

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_Sales_Order_InvoiceController extends Mage_Adminhtml_Sales_Order_InvoiceController
{
    /**
     * @return Mage_Core_Controller_Varien_Action
     */
    public function getInvoiceAction()
    {
        /** @var $order Mage_Sales_Model_Order */
        $invoice = $this->_initInvoice();
        $order = $invoice->getOrder();
        $service = $this->getPayoneFactory()->getServiceManagementGetInvoice();
        $pdfBinary = $service->execute($invoice);

        if ($pdfBinary !== false) {
            return $this->_prepareDownloadResponse(
                'payone_order_' . $order->getIncrementId() . '_invoice.pdf', $pdfBinary,
                'application/pdf'
            );
        }

        $this->_getSession()->addError($this->__('Failed to get the Payone Invoice.'));

        $this->_redirect('adminhtml/sales_order_invoice/view', array('invoice_id' => $invoice->getId()));

        return $this;
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