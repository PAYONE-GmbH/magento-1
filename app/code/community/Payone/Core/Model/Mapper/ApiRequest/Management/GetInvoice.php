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
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Mapper_ApiRequest_Management_GetInvoice
    extends Payone_Core_Model_Mapper_ApiRequest_Abstract
{
    /**
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @return Payone_Api_Request_GetInvoice
     */
    public function mapFromInvoice(Mage_Sales_Model_Order_Invoice $invoice)
    {
        /** @var $paymentMethod Payone_Core_Model_Payment_Method_Abstract */
        $order = $invoice->getOrder();
        $payment = $order->getPayment();
        $paymentMethod = $payment->getMethodInstance();
        $paymentConfig = $paymentMethod->getConfigByOrder($order);

        $request = $this->getFactory()->getRequestManagementGetInvoice();
        $helper = $this->helper();

        $txId = $payment->getLastTransId();

        $sequenceNumber = $invoice->getPayoneSequencenumber();

        $request->setMid($paymentConfig->getMid());
        $request->setMode($paymentConfig->getMode());
        $request->setPortalid($paymentConfig->getPortalid());
        $request->setKey($paymentConfig->getKey());
        $request->setEncoding('UTF-8');

        $request->setIntegratorName('Magento');
        $request->setIntegratorVersion($helper->getMagentoVersion());
        $request->setSolutionName('fatchip');
        $request->setSolutionVersion($helper->getPayoneVersion());

        $invoiceTitle = 'RG-' . $txId . '-' . $sequenceNumber;
        $request->setInvoiceTitle($invoiceTitle);

        return $request;
    }
}