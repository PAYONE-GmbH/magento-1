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
class Payone_Api_Request_Parameter_Vauthorization_PersonalData
    extends Payone_Api_Request_Parameter_Vauthorization_Abstract
{
    /**
     * Merchant's customer ID (Permitted symbols: 0-9, a-z, A-Z, .,-,_,/)
     * @var string
     */
    protected $customerid = null;
    /**
     * PAYONE debitor ID
     *
     * @var int
     */
    protected $userid = NULL;
    protected $salutation = null;
    protected $title = null;
    protected $firstname = null;
    protected $lastname = null;
    protected $company = null;
    protected $street = null;
    protected $addressaddition = null;
    protected $zip = null;
    protected $city = null;
    /**
     * Country (ISO-3166)
     *
     * @var string
     */
    protected $country = null;
    protected $email = null;
    protected $telephonenumber = null;
    /**
     * Date of birth (YYYYMMDD)
     *
     * @var int
     */
    protected $birthday = NULL;
    /**
     * Language indicator (ISO639)
     *
     * @var string
     */
    protected $language = null;
    protected $vatid = null;
    protected $ip = null;

    public function setAddressaddition($addressaddition)
    {
        $this->addressaddition = $addressaddition;
    }

    public function getAddressaddition()
    {
        return $this->addressaddition;
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

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $customerid
     */
    public function setCustomerid($customerid)
    {
        $this->customerid = $customerid;
    }

    /**
     * @return string
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
    }

    public function getSalutation()
    {
        return $this->salutation;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setTelephonenumber($telephonenumber)
    {
        $this->telephonenumber = $telephonenumber;
    }

    public function getTelephonenumber()
    {
        return $this->telephonenumber;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return string
     */
    public function getUserid()
    {
        return $this->userid;
    }

    public function setVatid($vatid)
    {
        $this->vatid = $vatid;
    }

    public function getVatid()
    {
        return $this->vatid;
    }

    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    public function getZip()
    {
        return $this->zip;
    }
}
