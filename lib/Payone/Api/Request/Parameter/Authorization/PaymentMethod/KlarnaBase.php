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
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */
class Payone_Api_Request_Parameter_Authorization_PaymentMethod_KlarnaBase
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{
    /**
     * Enum FinancingType
     * @var string
     */
    protected $financingtype = NULL;

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

    protected $paydata = NULL;

    /**
     * @var string
     */
    protected $api_version = NULL;

    /** @var string */
    protected $workorderid = NULL;

    /**
     * @param string $financingtype
     */
    public function setFinancingtype($financingtype)
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
     * @param string $successurl
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
     * @param Payone_Api_Request_Parameter_Paydata_Paydata $paydata
     */
    public function setPaydata($paydata)
    {
        $this->paydata = $paydata;
    }

    /**
     * @return Payone_Api_Request_Parameter_Paydata_Paydata
     */
    public function getPaydata()
    {
        return $this->paydata;
    }

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
     * @param string $workorderid
     */
    public function setWorkorderid($workorderid)
    {
        $this->workorderid = $workorderid;
    }

    /**
     * @return string
     */
    public function getWorkorderid()
    {
        return $this->workorderid;
    }
}
