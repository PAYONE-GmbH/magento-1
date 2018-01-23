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
class Payone_TransactionStatus_Request extends Payone_TransactionStatus_Request_Abstract
{
    /**
     * @var string Payment portal key as MD5 value
     */
    protected $key = NULL;
    /**
     * @var string
     */
    protected $txaction = NULL;
    /**
     * @var string
     */
    protected $mode = NULL;
    /**
     * @var int Payment portal ID
     */
    protected $portalid = NULL;
    /**
     * @var int Account ID (subaccount ID)
     */
    protected $aid = NULL;
    /**     *
     * @var string
     */
    protected $clearingtype = NULL;
    /**
     * unix timestamp
     *
     * @var int
     */
    protected $txtime = NULL;
    /**
     * @var string ISO-4217
     */
    protected $currency = NULL;
    /**
     * @var int
     */
    protected $userid = NULL;
    /**
     * @var int
     */
    protected $customerid = NULL;
    /**
     * @var string
     */
    protected $param = NULL;

    // Parameter bei einer Statusmeldung eines Zahlungsvorgangs

    /**
     * @var int
     */
    protected $txid = NULL;
    /**
     * @var string
     */
    protected $reference = NULL;
    /**
     * @var string
     */
    protected $sequencenumber = NULL;
    /**
     * @var string
     */
    protected $receivable = NULL;
    /**
     * @var string
     */
    protected $balance = NULL;
    /**
     * @var string
     */
    protected $transaction_status = NULL;
    /**
     * @var string
     */
    protected $failedcause = NULL;
    /**
     * @var string
     */
    protected $reasoncode = NULL;

    // Zusätzliche Parameter Contract bei Statusmeldung eines Zahlungsvorgangs

    /**
     * @var int
     */
    protected $productid = NULL;
    /**
     * @var int
     */
    protected $accessid = NULL;

    // Zusätzliche Parameter Collect (txaction=reminder) bei Statusmeldung eines Zahlungsvorgangs

    /**
     * @var string
     */
    protected $reminderlevel = NULL;

    // Parameter Invoicing (txaction=invoice)

    /**
     * @var string
     */
    protected $invoiceid = NULL;
    /**
     * @var string
     */
    protected $invoice_grossamount = NULL;
    /**
     * @var string
     */
    protected $invoice_date = NULL;
    /**
     * @var string
     */
    protected $invoice_deliverydate = NULL;
    /**
     * @var string
     */
    protected $invoice_deliveryenddate = NULL;


    /**
     * @var string
     */
    protected $clearing_bankaccountholder = NULL;
    /**
     * @var string
     */
    protected $clearing_bankcountry = NULL;
    /**
     * @var string
     */
    protected $clearing_bankaccount = NULL;
    /**
     * @var string
     */
    protected $clearing_bankcode = NULL;
    /**
     * @var string
     */
    protected $clearing_bankiban = NULL;
    /**
     * @var string
     */
    protected $clearing_bankbic = NULL;
    /**
     * @var string
     */
    protected $clearing_bankcity = NULL;
    /**
     * @var string
     */
    protected $clearing_bankname = NULL;


    /** @var string */
    protected $clearing_legalnote = NULL;

    /**
     * (YYYYMMDD)
     * @var string
     */
    protected $clearing_duedate = NULL;

    /** @var string */
    protected $clearing_reference = NULL;

    /** @var string */
    protected $clearing_instructionnote = NULL;

    /**
     * @var string
     */
    protected $iban = NULL;
    /**
     * @var string
     */
    protected $bic = NULL;
    /**
     * @var string
     */
    protected $mandate_identification = NULL;
    /**
     * @var string
     */
    protected $creditor_identifier = NULL;
    /**
     * Format YYYYMMDD
     * @var int
     */
    protected $clearing_date = NULL;
    /**
     * @var float
     */
    protected $clearing_amount = NULL;


    /**
     * @param int $accessid
     */
    public function setAccessid($accessid)
    {
        $this->accessid = $accessid;
    }

    /**
     * @return int
     */
    public function getAccessid()
    {
        return $this->accessid;
    }

    /**
     * @param int $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return int
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @param string $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param string $clearingtype
     */
    public function setClearingtype($clearingtype)
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string
     */
    public function getClearingtype()
    {
        return $this->clearingtype;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param int $customerid
     */
    public function setCustomerid($customerid)
    {
        $this->customerid = $customerid;
    }

    /**
     * @return int
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * @param string $transaction_status
     */
    public function setTransactionStatus($transaction_status)
    {
        $this->transaction_status = $transaction_status;
    }

    /**
     * @return string
     */
    public function getTransactionStatus()
    {
        return $this->transaction_status;
    }

    /**
     * @param string $failedcause
     */
    public function setFailedcause($failedcause)
    {
        $this->failedcause = $failedcause;
    }

    /**
     * @return string
     */
    public function getFailedcause()
    {
        return $this->failedcause;
    }

    /**
     * @param string $reasoncode
     */
    public function setReasoncode($reasoncode)
    {
        $this->reasoncode = $reasoncode;
    }

    /**
     * @return string
     */
    public function getReasoncode()
    {
        return $this->reasoncode;
    }

    /**
     * @param string $invoice_date
     */
    public function setInvoiceDate($invoice_date)
    {
        $this->invoice_date = $invoice_date;
    }

    /**
     * @return string
     */
    public function getInvoiceDate()
    {
        return $this->invoice_date;
    }

    /**
     * @param string $invoice_deliverydate
     */
    public function setInvoiceDeliverydate($invoice_deliverydate)
    {
        $this->invoice_deliverydate = $invoice_deliverydate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliverydate()
    {
        return $this->invoice_deliverydate;
    }

    /**
     * @param string $invoice_deliveryenddate
     */
    public function setInvoiceDeliveryenddate($invoice_deliveryenddate)
    {
        $this->invoice_deliveryenddate = $invoice_deliveryenddate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliveryenddate()
    {
        return $this->invoice_deliveryenddate;
    }

    /**
     * @param string $invoice_grossamount
     */
    public function setInvoiceGrossamount($invoice_grossamount)
    {
        $this->invoice_grossamount = $invoice_grossamount;
    }

    /**
     * @return string
     */
    public function getInvoiceGrossamount()
    {
        return $this->invoice_grossamount;
    }

    /**
     * @param string $invoiceid
     */
    public function setInvoiceid($invoiceid)
    {
        $this->invoiceid = $invoiceid;
    }

    /**
     * @return string
     */
    public function getInvoiceid()
    {
        return $this->invoiceid;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param int $portalid
     */
    public function setPortalid($portalid)
    {
        $this->portalid = $portalid;
    }

    /**
     * @return int
     */
    public function getPortalid()
    {
        return $this->portalid;
    }

    /**
     * @param int $productid
     */
    public function setProductid($productid)
    {
        $this->productid = $productid;
    }

    /**
     * @return int
     */
    public function getProductid()
    {
        return $this->productid;
    }

    /**
     * @param string $receivable
     */
    public function setReceivable($receivable)
    {
        $this->receivable = $receivable;
    }

    /**
     * @return string
     */
    public function getReceivable()
    {
        return $this->receivable;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reminderlevel
     */
    public function setReminderlevel($reminderlevel)
    {
        $this->reminderlevel = $reminderlevel;
    }

    /**
     * @return string
     */
    public function getReminderlevel()
    {
        return $this->reminderlevel;
    }

    /**
     * @param string $sequencenumber
     */
    public function setSequencenumber($sequencenumber)
    {
        $this->sequencenumber = $sequencenumber;
    }

    /**
     * @return string
     */
    public function getSequencenumber()
    {
        return $this->sequencenumber;
    }

    /**
     * @param string $txaction
     */
    public function setTxaction($txaction)
    {
        $this->txaction = $txaction;
    }

    /**
     * @return string
     */
    public function getTxaction()
    {
        return $this->txaction;
    }

    /**
     * @param int $txid
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;
    }

    /**
     * @return int
     */
    public function getTxid()
    {
        return $this->txid;
    }

    /**
     * @param int $txtime
     */
    public function setTxtime($txtime)
    {
        $this->txtime = $txtime;
    }

    /**
     * @return int
     */
    public function getTxtime()
    {
        return $this->txtime;
    }

    /**
     * @param int $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param string $clearing_bankaccount
     */
    public function setClearingBankaccount( $clearing_bankaccount)
    {
        $this->clearing_bankaccount = $clearing_bankaccount;
    }

    /**
     * @return string
     */
    public function getClearingBankaccount()
    {
        return $this->clearing_bankaccount;
    }

    /**
     * @param string $clearing_bankaccountholder
     */
    public function setClearingBankaccountholder( $clearing_bankaccountholder)
    {
        $this->clearing_bankaccountholder = $clearing_bankaccountholder;
    }

    /**
     * @return string
     */
    public function getClearingBankaccountholder()
    {
        return $this->clearing_bankaccountholder;
    }

    /**
     * @param string $clearing_bankbic
     */
    public function setClearingBankbic( $clearing_bankbic)
    {
        $this->clearing_bankbic = $clearing_bankbic;
    }

    /**
     * @return string
     */
    public function getClearingBankbic()
    {
        return $this->clearing_bankbic;
    }

    /**
     * @param string $clearing_bankcity
     */
    public function setClearingBankcity( $clearing_bankcity)
    {
        $this->clearing_bankcity = $clearing_bankcity;
    }

    /**
     * @return string
     */
    public function getClearingBankcity()
    {
        return $this->clearing_bankcity;
    }

    /**
     * @param string $clearing_bankcode
     */
    public function setClearingBankcode( $clearing_bankcode)
    {
        $this->clearing_bankcode = $clearing_bankcode;
    }

    /**
     * @return string
     */
    public function getClearingBankcode()
    {
        return $this->clearing_bankcode;
    }

    /**
     * @param string $clearing_bankcountry
     */
    public function setClearingBankcountry( $clearing_bankcountry)
    {
        $this->clearing_bankcountry = $clearing_bankcountry;
    }

    /**
     * @return string
     */
    public function getClearingBankcountry()
    {
        return $this->clearing_bankcountry;
    }

    /**
     * @param string $clearing_bankiban
     */
    public function setClearingBankiban( $clearing_bankiban)
    {
        $this->clearing_bankiban = $clearing_bankiban;
    }

    /**
     * @return string
     */
    public function getClearingBankiban()
    {
        return $this->clearing_bankiban;
    }

    /**
     * @param string $clearing_bankname
     */
    public function setClearingBankname( $clearing_bankname)
    {
        $this->clearing_bankname = $clearing_bankname;
    }

    /**
     * @return string
     */
    public function getClearingBankname()
    {
        return $this->clearing_bankname;
    }

    /**
     * @param string $clearing_duedate
     */
    public function setClearingDuedate( $clearing_duedate)
    {
        $this->clearing_duedate = $clearing_duedate;
    }

    /**
     * @return string
     */
    public function getClearingDuedate()
    {
        return $this->clearing_duedate;
    }

    /**
     * @param string $clearing_instructionnote
     */
    public function setClearingInstructionnote( $clearing_instructionnote)
    {
        $this->clearing_instructionnote = $clearing_instructionnote;
    }

    /**
     * @return string
     */
    public function getClearingInstructionnote()
    {
        return $this->clearing_instructionnote;
    }

    /**
     * @param string $clearing_legalnote
     */
    public function setClearingLegalnote( $clearing_legalnote)
    {
        $this->clearing_legalnote = $clearing_legalnote;
    }

    /**
     * @return string
     */
    public function getClearingLegalnote()
    {
        return $this->clearing_legalnote;
    }

    /**
     * @param string $clearing_reference
     */
    public function setClearingReference( $clearing_reference)
    {
        $this->clearing_reference = $clearing_reference;
    }

    /**
     * @return string
     */
    public function getClearingReference()
    {
        return $this->clearing_reference;
    }

    /**
     * @param string $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * @param string $mandateIdentification
     */
    public function setMandateIdentification($mandateIdentification)
    {
        $this->mandate_identification = $mandateIdentification;
    }

    /**
     * @return string
     */
    public function getMandateIdentification()
    {
        return $this->mandate_identification;
    }

    /**
     * @param string $creditorIdentifier
     */
    public function setCreditorIdentifier($creditorIdentifier)
    {
        $this->creditor_identifier = $creditorIdentifier;
    }

    /**
     * @return string
     */
    public function getCreditorIdentifier()
    {
        return $this->creditor_identifier;
    }

    /**
     * @param int $clearingDate
     */
    public function setClearingDate($clearingDate)
    {
        $this->clearing_date = $clearingDate;
    }

    /**
     * @return int
     */
    public function getClearingDate()
    {
        return $this->clearing_date;
    }

    /**
     * @param float $clearingAmount
     */
    public function setClearingAmount($clearingAmount)
    {
        $this->clearing_amount = $clearingAmount;
    }

    /**
     * @return float
     */
    public function getClearingAmount()
    {
        return $this->clearing_amount;
    }
}
