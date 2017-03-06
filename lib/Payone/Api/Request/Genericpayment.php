<?php
/**
 * For PayPal ECS the request type genericpayment ist mandatory 
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

 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @author          Ronny Schröder
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 */
class Payone_Api_Request_Genericpayment extends Payone_Api_Request_Abstract
{
    
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
     * dynamic text for debit and creditcard payments
     *
     * @var string
     */
    protected $narrative_text = NULL;

    /**
     * @var Payone_Api_Request_Parameter_Authorization_DeliveryData
     */
    protected $deliveryData = null;
    
    /**
     * @var string
     */
    protected $financingtype = NULL;

    /**
     * With the first genericpayment the workorderid will be generated from the 
     * PAYONE platform and will be sent to you in the response. The ID is unique. 
     * The returned workorderid is mandatory for the following requests of 
     * PayPal Express Checkout.
     * 
     * @var string
     */
    protected $workorderid = NULL;

    /**
     * Wallet provider PPE: PayPal Express
     * @var Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet 
     */
    protected $wallet = null;

    /**
     * @var Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePay
     */
    protected $ratePay = null;

    /**
     * Mandatory for PayPal ECS:
     * 1. action=setexpresscheckout
     * 2. action=getexpresscheckoutdetails
     * 
     * @var Payone_Api_Request_Parameter_Paydata_Paydata 
     */
    protected $paydata = NULL;
    
    protected $company = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $street = null;
    protected $zip = null;
    protected $city = null;
    protected $country = null;

    protected $api_version = null;
    protected $birthday = null;
    protected $email = null;
    protected $ip = null;
    protected $language = null;
    protected $telephonenumber = null;
    
    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->request = Payone_Api_Enum_RequestType::GENERICPAYMENT;
        parent::__construct($data);
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
     * @param string $financingtype
     */
    public function setFinancingType($financingtype) 
    {
        $this->financingtype = $financingtype;
    }

    /**
     * @return string
     */
    public function getFinancingType() 
    {
        return $this->financingtype;
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
     * @param string $narrative_text
     */
    public function setNarrativeText($narrative_text) 
    {
        $this->narrative_text = $narrative_text;
    }

    /**
     * @return string
     */
    public function getNarrativeText() 
    {
        return $this->narrative_text;
    }

    /**
     * @param Payone_Api_Request_Parameter_Authorization_DeliveryData $deliveryData
     */
    public function setDeliveryData(Payone_Api_Request_Parameter_Authorization_DeliveryData $deliveryData) 
    {
        $this->deliveryData = $deliveryData;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_DeliveryData
     */
    public function getDeliveryData() 
    {
        return $this->deliveryData;
    }

    /**
     * 
     * @return string
     */
    function getWorkorderId() 
    {
        return $this->workorderid;
    }

    /**
     * 
     * @param string $workorderid
     */
    function setWorkorderId($workorderid) 
    {
        $this->workorderid = $workorderid;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePay
     */
    function getRatePay()
    {
       return $this->ratePay;
    }

    /**
     * @param Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePay $ratePay
     */
    function setRatePay(Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePay $ratePay)
    {
        $this->ratePay = $ratePay;
    }

    /**
     * 
     * @return Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet
     */
    function getWallet() 
    {
        return $this->wallet;
    }

    /**
     * 
     * @param Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet $wallet
     */
    function setWallet(Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet $wallet) 
    {
        $this->wallet = $wallet;
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
    
    public function setCompany($company) 
    {
        $this->company = $company;
    }
    
    public function getCompany() 
    {
        return $this->company;
    }
    
    public function setFirstname($firstname) 
    {
        $this->firstname = $firstname;
    }
    
    public function getFirstname() 
    {
        return $this->firstname;
    }
    
    public function setLastname($lastname) 
    {
        $this->lastname = $lastname;
    }
    
    public function getLastname() 
    {
        return $this->lastname;
    }
    
    public function setStreet($street) 
    {
        $this->street = $street;
    }
    
    public function getStreet() 
    {
        return $this->street;
    }
    
    public function setZip($zip) 
    {
        $this->zip = $zip;
    }
    
    public function getZip() 
    {
        return $this->zip;
    }
    
    public function setCity($city) 
    {
        $this->city = $city;
    }
    
    public function getCity() 
    {
        return $this->city;
    }
    
    public function setCountry($country) 
    {
        $this->country = $country;
    }
    
    public function getCountry() 
    {
        return $this->country;
    }
    
    public function setApiVersion($api_version)
    {
        $this->api_version = $api_version;
    }
    
    public function getApiVersion()
    {
        return $this->api_version;
    }
    
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }
    
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setIp($ip)
    {
        $this->ip = $ip;
    }
    
    public function getIp()
    {
        return $this->ip;
    }
    
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }

    public function setTelephonenumber($telephonenumber)
    {
        $this->telephonenumber = $telephonenumber;
    }

    public function getTelephonenumber()
    {
        return $this->telephonenumber;
    }

}
