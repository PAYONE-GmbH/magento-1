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
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Settings_Data_ConfigFile_PaymentMethod_DebitPayment
    extends Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    /** @var string */
    protected $key = Payone_Enum_ClearingType::DEBITPAYMENT;

    /** @var string */
    protected $active = '';

    /** @var string */
    protected $newOrderStatus = '';

    /** @var string */
    protected $countries = '';

    /** @var string */
    protected $authorization = '';

    /** @var string */
    protected $mode = '';

    /** @var string */
    protected $bankAccountCheck = '';


    /**
     * @param string $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $authorization
     */
    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @return string
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param string $countries
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;
    }

    /**
     * @return string
     */
    public function getCountries()
    {
        return $this->countries;
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
     * @param string $newOrderStatus
     */
    public function setNewOrderStatus($newOrderStatus)
    {
        $this->newOrderStatus = $newOrderStatus;
    }

    /**
     * @return string
     */
    public function getNewOrderStatus()
    {
        return $this->newOrderStatus;
    }

    /**
     * @return string
     */
    public function getClearingType()
    {
        return $this->key;
    }

    /**
     * @param string $bankAccountCheck
     */
    public function setBankAccountCheck($bankAccountCheck)
    {
        $this->bankAccountCheck = $bankAccountCheck;
    }

    /**
     * @return string
     */
    public function getBankAccountCheck()
    {
        return $this->bankAccountCheck;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

}