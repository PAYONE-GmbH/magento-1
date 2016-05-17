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
class Payone_Settings_Data_ConfigFile_Shop_Global
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'global';

    /** @var string */
    protected $mid = '';

    /** @var string */
    protected $aid = '';

    /** @var string */
    protected $portalid = '';

    /** @var string */
    protected $request_type = '';

    /** @var array */
    protected $parameter_invoice = array();

    /** @var Payone_Settings_Data_ConfigFile_Global_StatusMapping */
    protected $status_mapping = null ;

    /** @var array */
    protected $payment_creditcard = array();

    /**
     * @param string $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return string
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @param string $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    /**
     * @return string
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param string $portalid
     */
    public function setPortalid($portalid)
    {
        $this->portalid = $portalid;
    }

    /**
     * @return string
     */
    public function getPortalid()
    {
        return $this->portalid;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param array $parameterInvoice
     */
    public function setParameterInvoice($parameterInvoice)
    {
        $this->parameter_invoice = $parameterInvoice;
    }

    /**
     * @return array
     */
    public function getParameterInvoice()
    {
        return $this->parameter_invoice;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addParameterInvoice($key, $value)
    {
        $this->parameter_invoice[$key] = $value;
    }

    /**
     * @param string $requestType
     */
    public function setRequestType($requestType)
    {
        $this->request_type = $requestType;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->request_type;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Global_StatusMapping $statusMapping
     */
    public function setStatusMapping($statusMapping)
    {
        $this->status_mapping = $statusMapping;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Global_StatusMapping
     */
    public function getStatusMapping()
    {
        return $this->status_mapping;
    }

    /**
     * @param $type
     * @param $mapping
     */
    public function addStatusMapping($type, $mapping)
    {
        $this->status_mapping[$type] = $mapping;
    }

    /**
     * @param array $paymentCreditcard
     */
    public function setPaymentCreditcard($paymentCreditcard)
    {
        $this->payment_creditcard = $paymentCreditcard;
    }

    /**
     * @return array
     */
    public function getPaymentCreditcard()
    {
        return $this->payment_creditcard;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addPaymentCreditcard($key, $value)
    {
        $this->payment_creditcard[$key] = $value;
    }
}
