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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Service_Transaction_Create extends Payone_Core_Model_Service_Abstract
{
    /**
     * @param Mage_Sales_Model_Order $order
     * @param Payone_Api_Response_Interface $response
     * @param Payone_Api_Request_Interface $request
     * @throws Payone_Core_Exception_TransactionAlreadyExists
     * @return null|Payone_Core_Model_Domain_Transaction
     */
    public function createByApiResponse(
        Mage_Sales_Model_Order $order, Payone_Api_Response_Interface $response,
        Payone_Api_Request_Interface $request
    )
    {
        $transaction = $this->getFactory()->getModelTransaction();

        if($request->isFrontendApiCall() === false) {
            $transaction->load($response->getTxid(), 'txid'); // should not exist but to be sure load by txid

            if ($transaction->hasData()) {
                throw new Payone_Core_Exception_TransactionAlreadyExists($response->getTxid());
            }
            $transaction->setTxid($response->getTxid());
        } else {
            $transaction->setFrontendApiCall(1);
        }

        $transaction->setLastTxaction($response->getStatus());
        $transaction->setUserid($response->getUserid());

        $transaction->setStoreId($order->getStoreId());
        $transaction->setOrderId($order->getId());
        $transaction->setReference($order->getIncrementId());
        $transaction->setCurrency($order->getOrderCurrencyCode());
        $transaction->setCustomerId($order->getCustomerId());
        $transaction->setClearingtype($request->getClearingtype());
        $transaction->setMode($request->getMode());
        $transaction->setMid($request->getMid());
        $transaction->setAid($request->getAid());
        $transaction->setPortalid($request->getPortalid());
        $transaction->setLastSequencenumber(0);

        $data = $response->toArray();

        $transaction->addData($data);
        $transaction->save();

        return $transaction;
    }

}