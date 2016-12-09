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
 * @method int getStoreId()
 * @method setStoreId(int $id)
 * @method int getOrderId()
 * @method setOrderId(int $id)
 * @method string getReference()
 * @method setCreatedAt(string $dateTime)
 * @method string getCreatedAt()
 * @method setUpdatedAt(string $dateTime)
 * @method string getUpdatedAt()
 * @method setTxid(int $txid)
 * @method int getTxid()
 * @method setTxaction(string $txAction)
 * @method string getTxaction()
 * @method string getReminderlevel()
 * @method setSequencenumber(int $nr)
 * @method string getSequencenumber()
 * @method setClearingtype(string $type)
 * @method string getClearingtype()
 */
class Payone_Core_Model_Domain_Protocol_TransactionStatus extends Mage_Core_Model_Abstract
{
    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_OK = 'complete';
    const STATUS_ERROR = 'error';

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/protocol_transactionStatus');
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_TransactionStatus
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        if ($this->isObjectNew()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
            $this->setStatusPending();
        }
        else {
            $this->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        return $this;
    }

    /**
     * Filters Data result only containing allowedKey
     *
     * if allowedkeys is empty, all will be returned
     *
     * @param array $allowedKeys
     * @return string
     */
    public function toStringKeyValue(array $allowedKeys = array())
    {
        $filter = false;
        if (count($allowedKeys)) {
            $filter = true;
        }

        $stringArray = array();
        foreach ($this->toArray() as $key => $value) {
            if ($filter and !in_array($key, $allowedKeys)) {
                continue;
            }

            $stringArray[] = $key . '=' . $value;
        }

        $result = implode("\n", $stringArray);
        return $result;
    }

    /**
     * @param array $allowedKeys
     * @return array
     */
    public function __toArray(array $allowedKeys = array())
    {
        if (empty($allowedKeys)) {
            return $this->_data;
        }

        $arrRes = array();
        foreach ($allowedKeys as $attribute) {
            if (isset($this->_data[$attribute]) && $this->_data[$attribute] !== '') {
                $arrRes[$attribute] = $this->_data[$attribute];
            }
        }

        return $arrRes;
    }

    /**
     * Returns the raw request to Payone as an array
     * @return array
     */
    public function getRawRequest()
    {
        $aRawData = $this->getRawRequestArray();
        if(!$aRawData) {
            $allowedKeys = array(
                'key',
                'txaction',
                'mode',
                'portalid',
                'aid',
                'clearingtype',
                'txtime',
                'currency',
                'userid',
                'customerid',
                'param',
                'reference',
                'sequencenumber',
                'receivable',
                'balance',
                'failedcause',
                'productid',
                'accessid',
                'reminderlevel',
                'vaid',
                'vreference',
                'vxid',
                'txid',
                'invoiceid',
                'invoice_grossamount',
                'invoice_date',
                'invoice_deliverydate',
                'invoice_deliveryenddate',
                'clearing_bankaccountholder',
                'clearing_bankcountry',
                'clearing_bankaccount',
                'clearing_bankcode',
                'clearing_bankiban',
                'clearing_bankbic',
                'clearing_bankcity',
                'clearing_bankname',
                'clearing_reference',
                'clearing_duedate',
                'clearing_legalnote',
                'clearing_instructionnote'
            );
            $aRawData = $this->__toArray($allowedKeys);
        }

        ksort($aRawData);

        return $aRawData;

    }

    /**
     * @return bool
     */
    public function isAppointed()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::APPOINTED;
    }

    /**
     * @return bool
     */
    public function isCapture()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::CAPTURE;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::PAID;
    }

    /**
     * @return bool
     */
    public function isUnderpaid()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::UNDERPAID;
    }

    /**
     * @return bool
     */
    public function isCancelation()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::CANCELATION;
    }

    /**
     * @return bool
     */
    public function isRefund()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::REFUND;
    }

    /**
     * @return bool
     */
    public function isDebit()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::DEBIT;
    }

    /**
     * @return bool
     */
    public function isReminder()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::REMINDER;
    }

    /**
     * @return bool
     */
    public function isTransfer()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::TRANSFER;
    }

    /**
     * @return bool
     */
    public function isVauthorization()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::VAUTHORIZATION;
    }

    /**
     * @return bool
     */
    public function isVsettlement()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::VSETTLEMENT;
    }

    /**
     * @return bool
     */
    public function isInvoice()
    {
        return $this->getTxaction() == Payone_TransactionStatus_Enum_Txaction::INVOICE;
    }

    public function setStatusPending()
    {
        $this->setProcessingStatus(self::STATUS_PENDING);
    }

    public function setStatusOk()
    {
        $this->setProcessingStatus(self::STATUS_OK);
    }


    public function setStatusRunning()
    {
        $this->setProcessingStatus(self::STATUS_RUNNING);
    }


    public function setStatusError()
    {
        $this->setProcessingStatus(self::STATUS_ERROR);
    }
    
    public function getRawRequestArray() 
    {
        if(!empty($this->_data['raw_request'])) {
            $aRaw = unserialize($this->_data['raw_request']);
            if($aRaw) {
                return $aRaw;
            }
        }

        return false;
    }

}