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
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Handler_Payment_Debit
    extends Payone_Core_Model_Handler_Payment_Abstract
    implements Payone_Core_Model_Handler_Payment_Interface
{
    /**
     * @param Payone_Api_Response_Interface $response
     * @return Payone_Core_Model_Handler_Payment_Abstract|Payone_Core_Model_Handler_Payment_Debit
     */
    public function handle(Payone_Api_Response_Interface $response)
    {
        $order = $this->getOrder();
        $paymentMethod = $this->getPaymentMethod();

        if ($response->isApproved()) {
            $this->getPayment()->setLastTransId($response->getTxid());
        }
        elseif($response->isError()) {
            return $this;
        }

        // Update Order Status
        $this->getServiceOrderStatus()->setConfigStore($this->getConfigStore());
        $this->getServiceOrderStatus()->updateByApiResponse($order, $response);

        // Add Order Comment
        $this->getServiceOrderComment()->addByApiResponse($order, $response);

        return $this;
    }
}
