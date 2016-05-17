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
abstract class Payone_Api_Request_Authorization_Abstract
    extends Payone_Api_Request_Abstract
{
    /**
     * Sub account ID
     *
     * @var int
     */
    protected $aid = NULL;
    /**
     * @var string
     */
    protected $clearingtype = NULL;
    /**
     * Merchant reference number for the payment process. (Permitted symbols: 0-9, a-z, A-Z, .,-,_,/)
     *
     * @var string
     */
    protected $reference = NULL;
    /**
     * Total amount (in smallest currency unit! e.g. cent)
     *
     * @var int
     */
    protected $amount = NULL;
    /**
     * Currency (ISO-4217)
     *
     * @var string
     */
    protected $currency = NULL;
    /**
     * Individual parameter
     *
     * @var string
     */
    protected $param = NULL;
    /**
     * dynamic text for debit and creditcard payments
     *
     * @var string
     */
    protected $narrative_text = NULL;

    /**
     * @var Payone_Api_Request_Parameter_Authorization_PersonalData
     */
    protected $personalData = null;
    /**
     * @var Payone_Api_Request_Parameter_Authorization_DeliveryData
     */
    protected $deliveryData = null;
    /**
     * @var Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
     */
    protected $payment = null;
    /**
     * @var Payone_Api_Request_Parameter_Authorization_3dsecure
     */
    protected $_3dsecure = null;

    /**
     * @var Payone_Api_Request_Parameter_Invoicing_Transaction
     */
    protected $invoicing = null;
    
    /**
     * Mandatory for PayPal Express Checkout
     * Alphanumeric max 16 chars
     * @var string 
     */
    protected $workorderid = null;

    /**
     * @param int $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return int
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $clearingtype
     */
    public function setClearingtype($clearingtype)
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string
     */
    public function getClearingtype()
    {
        return $this->clearingtype;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $narrative_text
     */
    public function setNarrativeText($narrative_text)
    {
        $this->narrative_text = $narrative_text;
    }

    /**
     * @return string
     */
    public function getNarrativeText()
    {
        return $this->narrative_text;
    }

    /**
     * @param string $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param Payone_Api_Request_Parameter_Authorization_PersonalData $personalData
     */
    public function setPersonalData(Payone_Api_Request_Parameter_Authorization_PersonalData $personalData)
    {
        $this->personalData = $personalData;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_PersonalData
     */
    public function getPersonalData()
    {
        return $this->personalData;
    }

    /**
     * @param Payone_Api_Request_Parameter_Authorization_DeliveryData $deliveryData
     */
    public function setDeliveryData(Payone_Api_Request_Parameter_Authorization_DeliveryData $deliveryData)
    {
        $this->deliveryData = $deliveryData;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_DeliveryData
     */
    public function getDeliveryData()
    {
        return $this->deliveryData;
    }

    /**
     * @param Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract $payment
     */
    public function setPayment(Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param Payone_Api_Request_Parameter_Authorization_3dsecure $secure
     */
    public function set3dsecure(Payone_Api_Request_Parameter_Authorization_3dsecure $secure)
    {
        $this->_3dsecure = $secure;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_3dsecure
     */
    public function get3dsecure()
    {
        return $this->_3dsecure;
    }


    /**
     * @param Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing
     */
    public function setInvoicing(Payone_Api_Request_Parameter_Invoicing_Transaction $invoicing)
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
     * @return string
     */
    function getWorkorderId() {
        return $this->workorderid;
    }

    /**
     * @param string $workorderid
     */
    function setWorkorderId($workorderid) {
        $this->workorderid = $workorderid;
    }
    
    public function convertToFrontendApiUrl() {
        $sFrontendApiUrl = 'https://secure.pay1.de/frontend/';
        $aFrontendUnsetParams = array(
            'mid',
            'integrator_name',
            'integrator_version',
            'solution_name',
            'solution_version',
            'ip',
            'errorurl',
            'salutation',
            'pseudocardpan',
        );
        $aFrontendHashParams = array(
            'aid',
            'amount',
            'backurl',
            'clearingtype',
            'currency',
            'customerid',
            'de',
            'encoding',
            'id',
            'mode',
            'no',
            'portalid',
            'pr',
            'reference',
            'request',
            'successurl',
            'targetwindow',
            'va',
            'key',
            'invoiceappendix',
            'invoice_deliverydate',
            'invoice_deliveryenddate',
            'param',
            'narrative_text',
        );
        
        $aParameters = $this->toArray();
        $aParameters['targetwindow'] = 'parent';

        $aHashParams = array();
        foreach ($aParameters as $sKey => $sValue) {
            if(array_search($sKey, $aFrontendUnsetParams) !== false) {
                unset($aParameters[$sKey]);
            } elseif(array_search($sKey, $aFrontendHashParams) !== false || stripos($sKey, '[') !== false) {
                $aHashParams[$sKey] = $sValue;
            }
        }
        $aParameters['hash'] = $this->_getFrontendHash($aHashParams);
        
        $sUrlParams = '?';
        foreach ($aParameters as $sKey => $sValue) {
            $sUrlParams .= $sKey.'='.urlencode($sValue).'&';
        }
        $sUrlParams = rtrim($sUrlParams, '&');
        $sFrontendApiUrl = $sFrontendApiUrl.$sUrlParams;

        return $sFrontendApiUrl;
    }
    
    public function getFrontendApiResponse() {
        $aResponse = array(
            'redirecturl' => $this->convertToFrontendApiUrl(),
            'status' => 'REDIRECT',
            'txid' => '',
        );
        
        return $aResponse;
    }

    protected function _getConfigKey() {
        $oOrder = Mage::getSingleton('checkout/session')->getQuote();
        $oPayment = $oOrder->getPayment();
        $oPaymentMethod = $oPayment->getMethodInstance();
        $oPaymentConfig = $oPaymentMethod->getConfigByOrder($oOrder);
        return $oPaymentConfig->getKey();
    }
    
    protected function _getFrontendHash($aHashParams) {
        ksort($aHashParams, SORT_STRING);
        unset($aHashParams['key']);
        $aHashParams['key'] = $this->_getConfigKey();

        $sHashString = '';
        foreach ($aHashParams as $sKey => $sValue) {
            $sHashString .= $sValue;
        }
        return md5($sHashString);
    }
    
}
