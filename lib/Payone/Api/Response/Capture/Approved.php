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
 * @package         Payone_Api
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Response_Capture_Approved extends Payone_Api_Response_Abstract
{
    /**
     * @var int
     */
    protected $txid = NULL;
    /**
     * @var string
     */
    protected $settleaccount = NULL;
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
    protected $mandate_identification = NULL;
    /**
     * @var string
     */
    protected $creditor_identifier = NULL;
    /**
     * @var int
     */
    protected $clearing_date = NULL;
    /**
     * @var int
     */
    protected $clearing_amount = NULL;
    
    protected $paydata_clearing_reference = NULL;

    /**
     * @param string $clearing_bankaccount
     */
    public function setClearingBankaccount($clearing_bankaccount)
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
    public function setClearingBankaccountholder($clearing_bankaccountholder)
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
    public function setClearingBankbic($clearing_bankbic)
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
    public function setClearingBankcity($clearing_bankcity)
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
    public function setClearingBankcode($clearing_bankcode)
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
    public function setClearingBankcountry($clearing_bankcountry)
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
    public function setClearingBankiban($clearing_bankiban)
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
    public function setClearingBankname($clearing_bankname)
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
     * @param string $settleaccount
     */
    public function setSettleaccount($settleaccount)
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string
     */
    public function getSettleaccount()
    {
        return $this->settleaccount;
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
     * @param string $clearing_duedate
     */
    public function setClearingDuedate($clearing_duedate)
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
    public function setClearingInstructionnote($clearing_instructionnote)
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
    public function setClearingLegalnote($clearing_legalnote)
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
    public function setClearingReference($clearing_reference)
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
     * @param int $clearingAmount
     */
    public function setClearingAmount($clearingAmount)
    {
        $this->clearing_amount = $clearingAmount;
    }

    /**
     * @return int
     */
    public function getClearingAmount()
    {
        return $this->clearing_amount;
    }
    
    public function getAddPaydataClearingReference() {
        return $this->paydata_clearing_reference;
    }

    public function setAddPaydataClearingReference($sClearingReference) {
        $this->paydata_clearing_reference = $sClearingReference;
    }
    
}
