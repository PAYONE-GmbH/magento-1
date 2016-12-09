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
class Payone_Api_Request_Parameter_Authorization_PaymentMethod_OnlineBankTransfer
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{
    /**
     * @var string
     */
    protected $onlinebanktransfertype = NULL;
    /**
     * @var string
     */
    protected $bankcountry = NULL;
    /**
     * @var string
     */
    protected $bankaccount = NULL;
    /**
     * @var string
     */
    protected $bankcode = NULL;
    /**
     * @var string
     */
    protected $bankgrouptype = NULL;
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
     * @param string $bankaccount
     */
    public function setBankaccount($bankaccount)
    {
        $this->bankaccount = $bankaccount;
    }

    /**
     * @return string
     */
    public function getBankaccount()
    {
        return $this->bankaccount;
    }

    /**
     * @param string $bankcode
     */
    public function setBankcode($bankcode)
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return string
     */
    public function getBankcode()
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankcountry
     */
    public function setBankcountry($bankcountry)
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return string
     */
    public function getBankcountry()
    {
        return $this->bankcountry;
    }

    /**
     * @param string $bankgrouptype
     */
    public function setBankgrouptype($bankgrouptype)
    {
        $this->bankgrouptype = $bankgrouptype;
    }

    /**
     * @return string
     */
    public function getBankgrouptype()
    {
        return $this->bankgrouptype;
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
    public function getIban()
    {
        return $this->iban;
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
    public function getBic()
    {
        return $this->bic;
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
     * @param string $onlinebanktransfertype
     */
    public function setOnlinebanktransfertype($onlinebanktransfertype)
    {
        $this->onlinebanktransfertype = $onlinebanktransfertype;
    }

    /**
     * @return string
     */
    public function getOnlinebanktransfertype()
    {
        return $this->onlinebanktransfertype;
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
}
