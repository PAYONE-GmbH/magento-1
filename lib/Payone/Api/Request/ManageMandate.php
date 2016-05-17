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
class Payone_Api_Request_ManageMandate extends Payone_Api_Request_Abstract
{
    protected $request = Payone_Api_Enum_RequestType::MANAGEMANDATE;

    /**
     * @var int
     */
    protected $aid = NULL;
    /**
     * @var string
     */
    protected $clearingtype = NULL;
    /**
     * @var string
     */
    protected $mandate_identification = NULL;
    /**
     * Currency (ISO 4217)
     *
     * @var string
     */
    protected $currency = NULL;


    /**
     * @var Payone_Api_Request_Parameter_ManageMandate_PersonalData
     */
    protected $personalData = null;
    /**
     * @var Payone_Api_Request_Parameter_ManageMandate_PaymentMethod_Abstract
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
     * @param \Payone_Api_Request_Parameter_ManageMandate_PersonalData $personalData
     */
    public function setPersonalData($personalData)
    {
        $this->personalData = $personalData;
    }

    /**
     * @return \Payone_Api_Request_Parameter_ManageMandate_PersonalData
     */
    public function getPersonalData()
    {
        return $this->personalData;
    }

    /**
     * @param \Payone_Api_Request_Parameter_ManageMandate_PaymentMethod_Abstract $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return \Payone_Api_Request_Parameter_ManageMandate_PaymentMethod_Abstract
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
