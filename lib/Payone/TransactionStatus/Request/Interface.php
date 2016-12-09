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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_TransactionStatus
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_TransactionStatus
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_TransactionStatus_Request_Interface
{
    /**
     * @abstract
     * @param array $data
     */
    public function init(array $data = array());

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return array
     */
    public function __toString();

    /**
     * @param int $accessid
     */
    public function setAccessid($accessid);

    /**
     * @return int
     */
    public function getAccessid();

    /**
     * @param int $aid
     */
    public function setAid($aid);

    /**
     * @return int
     */
    public function getAid();

    /**
     * @param string $balance
     */
    public function setBalance($balance);

    /**
     * @return string
     */
    public function getBalance();

    /**
     * @param string $clearingtype
     */
    public function setClearingtype($clearingtype);

    /**
     * @return string
     */
    public function getClearingtype();

    /**
     * @param string $currency
     */
    public function setCurrency($currency);

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @param int $customerid
     */
    public function setCustomerid($customerid);

    /**
     * @return int
     */
    public function getCustomerid();

    /**
     * @param string $failedcause
     */
    public function setFailedcause($failedcause);

    /**
     * @return string
     */
    public function getFailedcause();

    /**
     * @param string $invoice_date
     */
    public function setInvoiceDate($invoice_date);

    /**
     * @return string
     */
    public function getInvoiceDate();

    /**
     * @param string $invoice_deliverydate
     */
    public function setInvoiceDeliverydate($invoice_deliverydate);

    /**
     * @return string
     */
    public function getInvoiceDeliverydate();

    /**
     * @param string $invoice_deliveryenddate
     */
    public function setInvoiceDeliveryenddate($invoice_deliveryenddate);

    /**
     * @return string
     */
    public function getInvoiceDeliveryenddate();

    /**
     * @param string $invoice_grossamount
     */
    public function setInvoiceGrossamount($invoice_grossamount);

    /**
     * @return string
     */
    public function getInvoiceGrossamount();

    /**
     * @param string $invoiceid
     */
    public function setInvoiceid($invoiceid);

    /**
     * @return string
     */
    public function getInvoiceid();

    /**
     * @param string $key
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $mode
     */
    public function setMode($mode);

    /**
     * @return string
     */
    public function getMode();

    /**
     * @param string $param
     */
    public function setParam($param);

    /**
     * @return string
     */
    public function getParam();

    /**
     * @param int $portalid
     */
    public function setPortalid($portalid);

    /**
     * @return int
     */
    public function getPortalid();

    /**
     * @param int $productid
     */
    public function setProductid($productid);

    /**
     * @return int
     */
    public function getProductid();

    /**
     * @param string $receivable
     */
    public function setReceivable($receivable);

    /**
     * @return string
     */
    public function getReceivable();

    /**
     * @param string $reference
     */
    public function setReference($reference);

    /**
     * @return string
     */
    public function getReference();

    /**
     * @param string $reminderlevel
     */
    public function setReminderlevel($reminderlevel);

    /**
     * @return string
     */
    public function getReminderlevel();

    /**
     * @param string $sequencenumber
     */
    public function setSequencenumber($sequencenumber);

    /**
     * @return string
     */
    public function getSequencenumber();

    /**
     * @param string $txaction
     */
    public function setTxaction($txaction);

    /**
     * @return string
     */
    public function getTxaction();

    /**
     * @param int $txid
     */
    public function setTxid($txid);

    /**
     * @return int
     */
    public function getTxid();

    /**
     * @param int $txtime
     */
    public function setTxtime($txtime);

    /**
     * @return int
     */
    public function getTxtime();

    /**
     * @param int $userid
     */
    public function setUserid($userid);

    /**
     * @return int
     */
    public function getUserid();
}
