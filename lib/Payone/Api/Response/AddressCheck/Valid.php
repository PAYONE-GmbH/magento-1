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
class Payone_Api_Response_AddressCheck_Valid
    extends Payone_Api_Response_Abstract
{
    /**
     * @var int
     */
    protected $secstatus = NULL;
    /**
     * @var string
     */
    protected $personstatus = NULL;
    /**
     * @var string
     */
    protected $street = NULL;
    /**
     * @var string
     */
    protected $street2 = NULL;
    /**
     * @var string
     */
    protected $streetname = NULL;
    /**
     * @var string
     */
    protected $streetnumber = NULL;
    /**
     * @var string
     */
    protected $zip = NULL;
    /**
     * @var string
     */
    protected $city = NULL;

    /**
     * @return bool
     */
    public function isCorrect()
    {
        if ($this->secstatus == Payone_Api_Enum_AddressCheckSecstatus::ADDRESS_CORRECT) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @return bool
     */
    public function isCorrectable()
    {
        if ($this->secstatus == Payone_Api_Enum_AddressCheckSecstatus::ADDRESS_CORRECTABLE) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @return bool
     */
    public function isNotCorrectable()
    {
        if ($this->secstatus == Payone_Api_Enum_AddressCheckSecstatus::ADDRESS_NONE_CORRECTABLE) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $personstatus
     */
    public function setPersonstatus($personstatus)
    {
        $this->personstatus = $personstatus;
    }

    /**
     * @return string
     */
    public function getPersonstatus()
    {
        return $this->personstatus;
    }

    /**
     * @param int $secstatus
     */
    public function setSecstatus($secstatus)
    {
        $this->secstatus = $secstatus;
    }

    /**
     * @return int
     */
    public function getSecstatus()
    {
        return $this->secstatus;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $sNewStreet2 = '';
        if(stripos($street, '\n') !== false) {//MAGE-195 - split address by the \n and write it in the 2. address field
            $aStreetExpl = explode('\n', $street);
            
            $street = $aStreetExpl[0];
            unset($aStreetExpl[0]);
            $sNewStreet2 = implode(' - ', $aStreetExpl);
        }
        $this->setStreet2($sNewStreet2);
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }
    
    /**
     * @param string $street2
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @param string $streetname
     */
    public function setStreetname($streetname)
    {
        $this->streetname = $streetname;
    }

    /**
     * @return string
     */
    public function getStreetname()
    {
        return $this->streetname;
    }

    /**
     * @param string $streetnumber
     */
    public function setStreetnumber($streetnumber)
    {
        $this->streetnumber = $streetnumber;
    }

    /**
     * @return string
     */
    public function getStreetnumber()
    {
        return $this->streetnumber;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }
}
