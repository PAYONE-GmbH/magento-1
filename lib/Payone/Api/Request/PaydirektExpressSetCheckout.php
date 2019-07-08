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
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Api_Request_PaydirektExpressSetCheckout
    extends Payone_Api_Request_Genericpayment
{
    /** @var Payone_Api_Request_Parameter_Invoicing_Transaction */
    protected $invoicing = null;
    /** @var string */
    protected $wallettype;
    /** @var string */
    protected $successurl = '';
    /** @var string */
    protected $errorurl = '';
    /** @var string */
    protected $backurl = '';

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data);
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
    public function getWallettype()
    {
        return $this->wallettype;
    }

    /**
     * @param Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing
     */
    public function setInvoicing($invoicing)
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return Payone_Api_Request_Parameter_Invoicing_Transaction
     */
    public function getInvoicing()
    {
        return $this->invoicing;
    }

    /**
     * @param string $successurl
     */
    public function setSuccessurl($successurl)
    {
        $this->successurl = $successurl;
    }

    /**
     * @param string $errorurl
     */
    public function setErrorurl($errorurl)
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @param string $backurl
     */
    public function setBackurl($backurl)
    {
        $this->backurl = $backurl;
    }
}
