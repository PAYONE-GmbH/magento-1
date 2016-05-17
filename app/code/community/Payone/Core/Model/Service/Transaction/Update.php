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
class Payone_Core_Model_Service_Transaction_Update extends Payone_Core_Model_Service_Abstract
{
    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     * @return Payone_Core_Model_Domain_Transaction
     */
    public function updateByTransactionStatus(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $transaction = $this->getFactory()->getModelTransaction();
        $transaction->load($transactionStatus->getTxid(), 'txid');

        $data = $transactionStatus->getData();
        unset($data['id']);
        unset($data['created_at']);
        unset($data['updated_at']);
        unset($data['processed_at']);
        unset($data['processing_status']);

        $transaction->setLastTxaction($transactionStatus->getTxaction());
        $transaction->setLastSequencenumber($transactionStatus->getSequencenumber());

        $transaction->addData($data);
        $transaction->save();

        return $transaction;
    }

    /**
     * @param Payone_Api_Response_Interface $response
     * @return Payone_Core_Model_Domain_Transaction
     */
    public function updateByApiResponse(Payone_Api_Response_Interface $response)
    {
        $transaction = $this->getFactory()->getModelTransaction();
        $transaction->load($response->getTxid(), 'txid'); // should not exist but to be sure load by txid

        $transaction->setLastTxaction($response->getStatus());

        $transaction->save();

        return $transaction;
    }

}