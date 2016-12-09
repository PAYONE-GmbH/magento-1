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
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Checkout_Onepage_SuccessController extends Payone_Core_Controller_Abstract
{
    /**
     *
     */
    public function getSepaPdfAction()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $mandateIdentification = $this->getRequest()->getParam('mandate_identification');
        /** @var Mage_Sales_Model_Order $order */
        $order = $this->getFactory()->getModelSalesOrder()->load($orderId);
        $payment = $order->getPayment();

        $paymentMethodConfigId = $payment->getData('payone_config_payment_method_id');
        $storeId = $order->getStoreId();
        $getFileService = $this->getFactory()->getServiceManagementGetFile($paymentMethodConfigId, $storeId);

        try{
            $response = $getFileService->execute($mandateIdentification);
            if (!$response) {
                Mage::getSingleton('customer/session')->addError($this->helper()->__("Error trying to download the pdf"));
                $this->_redirect('');
            } else {
                $this->_prepareDownloadResponse("payone_sepa_mandate.pdf", $response, 'application/pdf');
            }
        }
        catch (Exception $e) {
            $this->handleException($e);
            Mage::getSingleton('customer/session')->addError($this->helper()->__("Error trying to download the pdf"));
            $this->_redirect('');
        }
    }

    /**
     * @param Exception $exception
     */
    protected function handleException(Exception $exception)
    {
        // Log exceptions, any messages relevant to customer have been set to the session by service
        Mage::logException($exception);
    }
}