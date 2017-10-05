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
 * @author          Ronny Schröder
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 */
class Payone_Api_Response_Genericpayment_Approved extends Payone_Api_Response_Genericpayment_Abstract
{

    /**
     * add_paydata[workorderid] = workorderid from payone
     * add_paydata[...] = delivery data
     *
     * @var Payone_Api_Response_Parameter_Paydata_Paydata
     */
    protected $paydata = null;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
        $this->initPaydata($params);
    }

    protected function initPaydata($param)
    {

        $payData = new Payone_Api_Response_Parameter_Paydata_Paydata($param);

        if ($payData->hasItems()) {
            $this->setPaydata($payData);
        } else {
            $this->setPaydata(null);
        }
    }


    /**
     * usage:
     * $request = new Payone_Api_Request_Genericpayment(array_merge($this->getAccountData(), $requestData));
     * $builder = $this->getPayoneBuilder();
     *
     * $service = $builder->buildServicePaymentGenericpayment();
     * $response = $service->request($request);
     * print_r($response->getPaydata()->toAssocArray());
     *
     * you get an array like that:
     *
     * Array
     * (
     *    [shipping_zip] => 79111
     *    [shipping_country] => DE
     *    [shipping_state] => Empty
     *    [shipping_city] => Freiburg
     *    [shipping_street] => ESpachstr. 1
     *    [shipping_firstname] => Max
     *    [shipping_lastname] => Mustermann
     * )
     *
     * @return Payone_Api_Response_Parameter_Paydata_Paydata
     */
    public function getPaydata()
    {
        return $this->paydata;
    }

    /**
     * @param Payone_Api_Response_Parameter_Paydata_Paydata $paydata
     */
    public function setPaydata($paydata)
    {
        $this->paydata = $paydata;
    }
    
    /**
     *
     * @return array
     */
    public function getPayDataArray()
    {
        $aPayData = array();
        foreach ($this->getPayData()->getItems() as $item) {
            $sCorrectedKey = strtolower($item->getKey());
            $sCorrectedKey = str_replace('-', '_', $sCorrectedKey);
            $aPayData[$sCorrectedKey] = $item->getData();
        }

        ksort($aPayData);
        return $aPayData;
    }
    
    public function getInstallmentData()
    {
        $aInstallmentData = array();
        
        $aPayData = $this->getPayDataArray();
        foreach ($aPayData as $sKey => $sValue) {
            $aSplit = explode('_', $sKey);
            for ($i = count($aSplit); $i > 0; $i--) {
                if ($i == count($aSplit)) {
                    $aTmp = array($aSplit[$i-1] => $sValue);
                } else {
                    $aTmp = array($aSplit[$i-1] => $aTmp);
                }
            }

            $aInstallmentData = array_replace_recursive($aInstallmentData, $aTmp);
        }
        
        if (isset($aInstallmentData['paymentdetails']) && count($aInstallmentData['paymentdetails']) > 0) {
            return $aInstallmentData['paymentdetails'];
        }

        return false;
    }


}
