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
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @author          Fatchip GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */
class Payone_Api_Request_Parameter_Authorization_PaymentMethod_ApplePay
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{
    /** @var Payone_Api_Request_Parameter_Paydata_Paydata */
    protected $paydata = null;

    /** @var string $wallettype */
    protected $wallettype = null;

    /** @var string $api_version */
    protected $api_version = null;

    /** @var string $cardtype  */
    protected $cardtype = null;

    /**
     * Payone_Api_Request_Parameter_Authorization_PaymentMethod_ApplePay constructor.
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
        $this->setApiVersion();
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
        $this->wallettype = Payone_Api_Enum_WalletType::APPLEPAY;
    }

    /**
     * @return string
     */
    public function getWallettype()
    {
        return $this->wallettype;
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
     * @param string $cardtype
     */
    public function setCardtype($cardtype)
    {
        $this->cardtype = $cardtype;
    }

    /**
     * @return string
     */
    public function getCardtype()
    {
        return $this->cardtype;
    }


}
