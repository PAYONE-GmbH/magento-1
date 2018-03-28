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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePayDirectDebit
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{

    /**
     * Enum FinancingType
     * @var string
     */
    protected $financingtype = NULL;
    /**
     * @var null
     */
    protected $paydata = NULL;
    /**
     * @var null
     */
    protected $birthday = NULL;
    /**
     * @var null
     */
    protected $telephonenumber = NULL;

    /**
     * @var string
     */
    protected $api_version = NULL;

    /**
     * @var string
     */
    protected $ratePayType = NULL;

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
    protected $bankcountry = NULL;

    /**
     * @var null
     */
    protected $bankaccountholder = NULL;

    public function setApiVersion()
    {
        $this->api_version = '3.10';
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->api_version;
    }

    /**
     * @param $ratePayType
     */
    public function setRatePayType($ratePayType)
    {
        $this->ratePayType = $ratePayType;
    }

    /**
     * @return string
     */
    public function getRatePayType()
    {
        return $this->ratePayType;
    }

    /**
     * For now there is only "RPV" for Invoicing, but there will be more added.
     * 
     * @param string $financingtype
     */
    public function setFinancingtype($financingtype = 'RPV')
    {
        $this->financingtype = $financingtype;
    }

    /**
     * @return string
     */
    public function getFinancingtype()
    {
        return $this->financingtype;
    }

    /**
     * @param Payone_Api_Request_Parameter_Paydata_Paydata $paydata
     */
    public function setPaydata($paydata) 
    {
        $this->paydata = $paydata;
    }

    /**
     * 
     * @return Payone_Api_Request_Parameter_Paydata_Paydata
     */
    public function getPaydata() 
    {
        return $this->paydata;
    }
    
    /**
     * @param string $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param string $telephonenumber
     */
    public function setTelephonenumber($telephonenumber)
    {
        $this->telephonenumber = $telephonenumber;
    }

    /**
     * @return string
     */
    public function getTelephonenumber()
    {
        return $this->telephonenumber;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
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
    public function getBic()
    {
        return $this->bic;
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
    public function getBankcountry()
    {
        return $this->bankcountry;
    }

    /**
     * @param string $bankcountry
     */
    public function setBankcountry($bankcountry)
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return null
     */
    public function getBankaccountholder()
    {
        return $this->bankaccountholder;
    }

    /**
     * @param null $bankaccountholder
     */
    public function setBankaccountholder($bankaccountholder)
    {
        $this->bankaccountholder = $bankaccountholder;
    }
}
