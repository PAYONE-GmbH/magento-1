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
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Request_CreateAccess extends Payone_Api_Request_Abstract
{
    protected $request = Payone_Api_Enum_RequestType::CREATEACCESS;

    /**
     * Sub account ID
     *
     * @var int
     */
    protected $aid = NULL;
    /**
     * @var string
     */
    protected $clearingtype = NULL;
    /**
     * Merchant reference number for the payment process. (Permitted symbols: 0-9, a-z, A-Z, .,-,_,/)
     *
     * @var string
     */
    protected $reference = NULL;

    /**
     * Offer ID
     *
     * @var int
     */
    protected $productid = NULL;

    /**
     * Individual Parameter
     *
     * @var string
     */
    protected $param = NULL;

    /**
     * Username of customer
     *
     * @var string
     */
    protected $accessname = NULL;

    /**
     * Pass of customer
     *
     * @var string
     */
    protected $accesscode = NULL;

    /**
     * Startdate of access as unixtimestamp
     *
     * @var int
     */
    protected $access_starttime = NULL;

    /**
     * Expiredate of first term or timestamp of renewal as unixtimestamp
     *
     * @var int
     */
    protected $access_expiretime = NULL;

    /**
     * Canelationdate as unixtimestamp
     *
     * @var int
     */
    protected $access_canceltime = NULL;

    /**
     * Entire price of first term, must be equal to sum of amount * price. Must be in smallest currency unit
     *
     * @var int
     */
    protected $amount_trail = NULL;

    /**
     * Time unit of first term
     *
     * @var string
     */
    protected $period_unit_trail = NULL;

    /**
     * Lenght of first term
     *
     * @var int
     */
    protected $period_length_trail = NULL;

    /**
     * Entire price of all products in one renewal term. Must be in smallest currency unit
     *
     * @var int
     */
    protected $amount_recurring = NULL;

    /**
     * Time unit of renewal term
     *
     * @var string
     */
    protected $period_unit_recurring = NULL;

    /**
     * Length of renewal term
     *
     * @var int
     */
    protected $period_length_recurring = NULL;

    /**
     * Currency (ISO-4217)
     *
     * @var string
     */
    protected $currency = NULL;

    /**
     * @var Payone_Api_Request_Parameter_CreateAccess_Billing
     */
    protected $billing = null;
    /**
     * @var Payone_Api_Request_Parameter_CreateAccess_Invoicing_Transaction
     */
    protected $invoicing = null;
    /**
     * @var Payone_Api_Request_Parameter_CreateAccess_PersonalData
     */
    protected $personaldata = null;
    /**
     * @var Payone_Api_Request_Parameter_CreateAccess_PaymentMethod_Abstract
     */
    protected $payment = null;
    /**
     * @var Payone_Api_Request_Parameter_CreateAccess_3dsecure
     */
    protected $_3dsecure = null;


    /**
     * @param int $access_canceltime
     */
    public function setAccessCanceltime($access_canceltime)
    {
        $this->access_canceltime = $access_canceltime;
    }

    /**
     * @return int
     */
    public function getAccessCanceltime()
    {
        return $this->access_canceltime;
    }

    /**
     * @param int $access_expiretime
     */
    public function setAccessExpiretime($access_expiretime)
    {
        $this->access_expiretime = $access_expiretime;
    }

    /**
     * @return int
     */
    public function getAccessExpiretime()
    {
        return $this->access_expiretime;
    }

    /**
     * @param int $access_starttime
     */
    public function setAccessStarttime($access_starttime)
    {
        $this->access_starttime = $access_starttime;
    }

    /**
     * @return int
     */
    public function getAccessStarttime()
    {
        return $this->access_starttime;
    }

    /**
     * @param string $accesscode
     */
    public function setAccesscode($accesscode)
    {
        $this->accesscode = $accesscode;
    }

    /**
     * @return string
     */
    public function getAccesscode()
    {
        return $this->accesscode;
    }

    /**
     * @param string $accessname
     */
    public function setAccessname($accessname)
    {
        $this->accessname = $accessname;
    }

    /**
     * @return string
     */
    public function getAccessname()
    {
        return $this->accessname;
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
     * @param int $amount_recurring
     */
    public function setAmountRecurring($amount_recurring)
    {
        $this->amount_recurring = $amount_recurring;
    }

    /**
     * @return int
     */
    public function getAmountRecurring()
    {
        return $this->amount_recurring;
    }

    /**
     * @param int $amount_trail
     */
    public function setAmountTrail($amount_trail)
    {
        $this->amount_trail = $amount_trail;
    }

    /**
     * @return int
     */
    public function getAmountTrail()
    {
        return $this->amount_trail;
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
     * @param int $period_length_recurring
     */
    public function setPeriodLengthRecurring($period_length_recurring)
    {
        $this->period_length_recurring = $period_length_recurring;
    }

    /**
     * @return int
     */
    public function getPeriodLengthRecurring()
    {
        return $this->period_length_recurring;
    }

    /**
     * @param int $period_length_trail
     */
    public function setPeriodLengthTrail($period_length_trail)
    {
        $this->period_length_trail = $period_length_trail;
    }

    /**
     * @return int
     */
    public function getPeriodLengthTrail()
    {
        return $this->period_length_trail;
    }

    /**
     * @param string $period_unit_recurring
     */
    public function setPeriodUnitRecurring($period_unit_recurring)
    {
        $this->period_unit_recurring = $period_unit_recurring;
    }

    /**
     * @return string
     */
    public function getPeriodUnitRecurring()
    {
        return $this->period_unit_recurring;
    }

    /**
     * @param string $period_unit_trail
     */
    public function setPeriodUnitTrail($period_unit_trail)
    {
        $this->period_unit_trail = $period_unit_trail;
    }

    /**
     * @return string
     */
    public function getPeriodUnitTrail()
    {
        return $this->period_unit_trail;
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
     * @param \Payone_Api_Request_Parameter_CreateAccess_Billing $billing
     */
    public function setBilling(Payone_Api_Request_Parameter_CreateAccess_Billing $billing)
    {
        $this->billing = $billing;
    }

    /**
     * @return \Payone_Api_Request_Parameter_CreateAccess_Billing
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * @param \Payone_Api_Request_Parameter_CreateAccess_Invoicing_Transaction $invoicing
     */
    public function setInvoicing(Payone_Api_Request_Parameter_CreateAccess_Invoicing_Transaction $invoicing)
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return \Payone_Api_Request_Parameter_CreateAccess_Invoicing_Transaction
     */
    public function getInvoicing()
    {
        return $this->invoicing;
    }

    /**
     * @param \Payone_Api_Request_Parameter_CreateAccess_PaymentMethod_Abstract $payment
     */
    public function setPayment(Payone_Api_Request_Parameter_CreateAccess_PaymentMethod_Abstract $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return \Payone_Api_Request_Parameter_CreateAccess_PaymentMethod_Abstract
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param \Payone_Api_Request_Parameter_CreateAccess_PersonalData $personaldata
     */
    public function setPersonaldata(Payone_Api_Request_Parameter_CreateAccess_PersonalData $personaldata)
    {
        $this->personaldata = $personaldata;
    }

    /**
     * @return \Payone_Api_Request_Parameter_CreateAccess_PersonalData
     */
    public function getPersonaldata()
    {
        return $this->personaldata;
    }

    /**
     * @param \Payone_Api_Request_Parameter_CreateAccess_3dsecure $secure
     */
    public function set3dsecure(Payone_Api_Request_Parameter_CreateAccess_3dsecure $secure)
    {
        $this->_3dsecure = $secure;
    }

    /**
     * @return \Payone_Api_Request_Parameter_CreateAccess_3dsecure
     */
    public function get3dsecure()
    {
        return $this->_3dsecure;
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

}
