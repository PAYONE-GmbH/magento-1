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
class Payone_Api_Request_Vauthorization extends Payone_Api_Request_Abstract
{
    protected $request = Payone_Api_Enum_RequestType::VAUTHORIZATION;

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
    protected $vreference = NULL;
    /**
     * @var string
     */
    protected $vaccountname = NULL;
    /**
     * Total amount (in smallest currency unit! e.g. cent)
     *
     * @var int
     */
    protected $amount = NULL;
    /**
     * Currency (ISO-4217)
     *
     * @var string
     */
    protected $currency = NULL;
    /**
     * @var int
     */
    protected $settle_period_length = NULL;
    /**
     * @var string
     */
    protected $settle_period_unit = NULL;
    /**
     * @var int
     */
    protected $settletime = NULL;
    /**
     * @var string
     */
    protected $payout_open_balance = NULL;
    /**
     * Individual parameter
     *
     * @var string
     */
    protected $param = NULL;

    /**
     * @var Payone_Api_Request_Parameter_Vauthorization_Invoicing_Transaction
     */
    protected $invoicing = null;
    /**
     * @var Payone_Api_Request_Parameter_Vauthorization_PersonalData
     */
    protected $personalData = null;
    /**
     * @var Payone_Api_Request_Parameter_Vauthorization_PaymentMethod_Abstract
     */
    protected $payment = null;

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
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
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
     * @param \Payone_Api_Request_Parameter_Vauthorization_Invoicing_Transaction $invoicing
     */
    public function setInvoicing($invoicing)
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return \Payone_Api_Request_Parameter_Vauthorization_Invoicing_Transaction
     */
    public function getInvoicing()
    {
        return $this->invoicing;
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
     * @param \Payone_Api_Request_Parameter_Vauthorization_PaymentMethod_Abstract $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return \Payone_Api_Request_Parameter_Vauthorization_PaymentMethod_Abstract
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param string $payout_open_balance
     */
    public function setPayoutOpenBalance($payout_open_balance)
    {
        $this->payout_open_balance = $payout_open_balance;
    }

    /**
     * @return string
     */
    public function getPayoutOpenBalance()
    {
        return $this->payout_open_balance;
    }

    /**
     * @param \Payone_Api_Request_Parameter_Vauthorization_PersonalData $personalData
     */
    public function setPersonalData($personalData)
    {
        $this->personalData = $personalData;
    }

    /**
     * @return \Payone_Api_Request_Parameter_Vauthorization_PersonalData
     */
    public function getPersonalData()
    {
        return $this->personalData;
    }

    /**
     * @param int $settle_period_length
     */
    public function setSettlePeriodLength($settle_period_length)
    {
        $this->settle_period_length = $settle_period_length;
    }

    /**
     * @return int
     */
    public function getSettlePeriodLength()
    {
        return $this->settle_period_length;
    }

    /**
     * @param string $settle_period_unit
     */
    public function setSettlePeriodUnit($settle_period_unit)
    {
        $this->settle_period_unit = $settle_period_unit;
    }

    /**
     * @return string
     */
    public function getSettlePeriodUnit()
    {
        return $this->settle_period_unit;
    }

    /**
     * @param int $settletime
     */
    public function setSettletime($settletime)
    {
        $this->settletime = $settletime;
    }

    /**
     * @return int
     */
    public function getSettletime()
    {
        return $this->settletime;
    }

    /**
     * @param string $vaccountname
     */
    public function setVaccountname($vaccountname)
    {
        $this->vaccountname = $vaccountname;
    }

    /**
     * @return string
     */
    public function getVaccountname()
    {
        return $this->vaccountname;
    }

    /**
     * @param string $vreference
     */
    public function setVreference($vreference)
    {
        $this->vreference = $vreference;
    }

    /**
     * @return string
     */
    public function getVreference()
    {
        return $this->vreference;
    }


}
