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
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Sales_Button
    extends Payone_Core_Helper_Abstract
{
    /**
     * @return array
     */
    public function getPayoneInvoicePdf()
    {
        /**
         * @var $invoice Mage_Sales_Model_Order_Invoice
         */
        $invoice = Mage::registry('current_invoice');
        $order = $invoice->getOrder();
        $data = array(
            'label' => $this->helperConfig()->__('Download PAYONE-Invoice'),
            'class' => 'save',
            'onclick' => 'setLocation(\'' . $this->getDownloadInvoiceUrl() . '\')',
        );
        $disabled = false;
        try {
            $configPayment = $this->helperConfig()->getConfigPaymentMethodByOrder($order);
            if (!$configPayment || !$configPayment->isInvoiceTransmitEnabled() || $this->isAllowedAction('download_payone_invoice') === false) {
                $disabled = true;
            }
        }
        catch (Payone_Core_Exception_PaymentMethodConfigNotFound $e) {
            $disabled = true;
        }
        catch (Exception $e) {
            Mage::logException($e);
            $disabled = true;
        }

        if ($disabled) {
            $data['disabled'] = 1;
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function getDownloadInvoiceUrl()
    {
        /** @var $invoice Mage_Sales_Model_Order_Invoice */
        $invoice = Mage::registry('current_invoice');

        $url = $this->getUrl('adminhtml/payonecore_sales_order_invoice/getInvoice', array('invoice_id' => $invoice->getId()));
        return $url;
    }

    /**
     * @param $action
     * @return mixed
     */
    protected function isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/order/invoice/actions/' . $action);
    }

    /**
     * @return Mage_Core_Model_Url
     */
    protected function getUrlModel()
    {
        return Mage::getModel('adminhtml/url');
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array  $params
     *
     * @return  string
     */
    public function getUrl($route = '', $params = array())
    {
        return $this->getUrlModel()->getUrl($route, $params);
    }
}