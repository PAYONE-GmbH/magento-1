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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Api_Request_Parameter_Authorization_PaymentMethod_AmazonPay
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{
    /** @var Payone_Api_Request_Parameter_Paydata_Paydata */
    protected $paydata = null;

    /** @var string $wallettype */
    protected $wallettype = null;

    /** @var string $workorderid */
    protected $workorderid = null;

    /** @var string $successurl */
    protected $successurl = null;

    /** @var string $errorurl */
    protected $errorurl = null;

    /**
     * Payone_Api_Request_Parameter_Authorization_PaymentMethod_AmazonPay constructor.
     *
     * @param array $data
     * @param array $paydata
     */
    public function __construct(array $data = [], array $paydata = [])
    {
        parent::__construct($data);

        $items = [];
        foreach ($paydata as $index => $value) {
            array_push($items, new Payone_Api_Request_Parameter_Paydata_DataItem([
                'key'  => $index,
                'data' => $value,
            ]));
        }
        if (count($items)) {
            $this->paydata = new Payone_Api_Request_Parameter_Paydata_Paydata(['items' => $items]);
        }
        $this->setWallettype();
        $this->setWorkorderid();
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

    public function setWallettype()
    {
        $this->wallettype = 'AMZ';
    }

    /**
     * @return string
     */
    public function getWallettype()
    {
        return $this->wallettype;
    }

    public function setWorkorderid()
    {
        /** @var \Payone_Core_Model_Session $session */
        $session = Mage::getSingleton('payone_core/session');
        $this->workorderid = $session->getData('work_order_id');
    }

    /**
     * @return string
     */
    public function getWorkorderid()
    {
        return $this->workorderid;
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
}
