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
 * Do not edit or add to this file if you wish to upgrade Payone_Core to newer
 * versions in the future. If you wish to customize Payone_Core for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Api_Request_Parameter_Authorization_PaymentMethod_Masterpass
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{
    /**
     * @var string
     */
    protected $successurl = NULL;
    /**
     * @var string
     */
    protected $errorurl = NULL;
    /**
     * @var string
     */
    protected $backurl = NULL;
    /**
     * @var Payone_Api_Request_Parameter_Paydata_Paydata
     */
    protected $paydata = NULL;
    /** @var string */
    protected $wallettype = null;
    /** @var string */
    protected $clearingType = NULL;

    /**
     * @param $successurl
     */
    public function setSuccessurl($successurl)
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string
     */
    public function getSuccessurl()
    {
        return $this->successurl;
    }

    /**
     * @param string $backurl
     */
    public function setBackurl($backurl)
    {
        $this->backurl = $backurl;
    }

    /**
     * @return string
     */
    public function getBackurl()
    {
        return $this->backurl;
    }

    /**
     * @param string $errorurl
     */
    public function setErrorurl($errorurl)
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @return string
     */
    public function getErrorurl()
    {
        return $this->errorurl;
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
     * @return string
     */
    public function getWallettype()
    {
        return $this->wallettype;
    }

    /**
     * @param string $wallettype
     */
    public function setWallettype($wallettype)
    {
        $this->wallettype = $wallettype;
    }

    /**
     * @return string
     */
    public function getClearingType()
    {
        return $this->clearingType;
    }

    /**
     * @param string $clearingType
     */
    public function setClearingType($clearingType)
    {
        $this->clearingType = $clearingType;
    }
}
