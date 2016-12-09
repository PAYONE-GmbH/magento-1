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
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 *
 * @method setStoreId($storeId)
 * @method int getStoreId()
 * @method setOrderId($orderId)
 * @method int getOrderId()
 * @method setTxid(int $txid)
 * @method int getTxid()
 * @method setTxtime($txtime)
 * @method string getTxtime()
 * @method setReference($reference)
 * @method string getReference()
 * @method setLastTxaction(string $txAction)
 * @method string getLastTxaction()
 * @method setLastSequencenumber(int $nr)
 * @method int getLastSequencenumber()
 * @method setClearingtype(string $type)
 * @method string getClearingtype()
 * @method setMode($mode)
 * @method string getMode()
 * @method setMid($mid)
 * @method int getMid()
 * @method setAid($aid)
 * @method int getAid()
 * @method setPortalid($portalid)
 * @method int getPortalid()
 * @method setProductid($productid)
 * @method int getroductid()
 * @method setCurrency($currency)
 * @method string getCurrency()
 * @method setReceivable($receivable)
 * @method float getReceivable()
 * @method setBalance($balance)
 * @method float getBalance()
 * @method setCustomerid($customerid)
 * @method string getCustomerid()
 * @method setUserid($userid)
 * @method int getUserid()
 * @method setReminderlevel($level)
 * @method string getReminderlevel()
 * @method setFailedcause($reason)
 * @method string getFailedcause()
 * @method setAccessid($id)
 * @method int getAccessid()
 * @method setCreatedAt(string $dateTime)
 * @method string getCreatedAt()
 * @method setUpdatedAt(string $dateTime)
 * @method string getUpdatedAt()
 */
class Payone_Core_Model_Domain_Transaction
    extends Mage_Core_Model_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/transaction');
    }

    /**
     * @return Payone_Core_Model_Domain_Transaction
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        if ($this->isObjectNew()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
        }
        else {
            $this->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        return $this;
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Payone_Core_Model_Domain_Transaction
     */
    public function loadByPayment(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->load($payment->getLastTransId(), 'txid');
        return $this;
    }

    /**
     * @return int
     */
    public function getNextSequenceNumber()
    {
        $sequenceNumber = $this->getLastSequencenumber();
        $sequenceNumber++;
        return $sequenceNumber;
    }

    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     * @return Payone_Core_Model_Domain_Transaction
     */
    public function loadByTransactionStatus(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $this->load($transactionStatus->getTxid(), 'txid');
        return $this;
    }

}