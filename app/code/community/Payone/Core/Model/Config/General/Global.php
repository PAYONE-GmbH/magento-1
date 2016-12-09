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
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Config_General_Global extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var int
     */
    protected $mid = 0;
    /**
     * @var int
     */
    protected $portalid = 0;
    /**
     * @var int
     */
    protected $aid = 0;
    /**
     * @var string
     */
    protected $key = '';
    /**
     * @var int
     */
    protected $allowspecific = 0;
    /**
     * @var array
     */
    protected $specificcountry = array();
    /**
     * @var string
     */
    protected $request_type = '';
    /**
     * @var int
     */
    protected $transmit_ip = 1;
    /**
     * @var int
     */
    protected $proxy_mode = 0;
    /**
     * @var int
     */
    protected $currency_convert = 0;
    /**
     * initialize config with specificcounty as array
     * @param array $data
     */
    public function init(array $data)
    {
        if(array_key_exists('specificcountry', $data) && is_string($data['specificcountry']))
        {
            $data['specificcountry_by_string'] = $data['specificcountry'];
            unset($data['specificcountry']);
        }

        parent::init($data);
    }

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
     * @param int $allowspecific
     */
    public function setAllowspecific($allowspecific)
    {
        $this->allowspecific = $allowspecific;
    }

    /**
     * @return int
     */
    public function getAllowspecific()
    {
        return $this->allowspecific;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param int $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    /**
     * @return int
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param int $portalid
     */
    public function setPortalid($portalid)
    {
        $this->portalid = $portalid;
    }

    /**
     * @return int
     */
    public function getPortalid()
    {
        return $this->portalid;
    }

    /**
     * @param string $request_type
     */
    public function setRequestType($request_type)
    {
        $this->request_type = $request_type;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->request_type;
    }

    /**
     * @param array $specificcountry
     */
    public function setSpecificcountry(array $specificcountry)
    {
        $this->specificcountry = $specificcountry;
    }

    /**
     * @return array
     */
    public function getSpecificcountry()
    {
        return $this->specificcountry;
    }

    /**
     * @param $specificcountry
     */
    public function setSpecificcountryByString($specificcountry)
    {
        $this->specificcountry = explode(',', $specificcountry);
    }

    /**
     * @return string
     */
    public function getSpecificcountryAsString()
    {
        return implode(',', $this->specificcountry);
    }

    /**
     * @param int $transmit_ip
     */
    public function setTransmitIp($transmit_ip)
    {
        $this->transmit_ip = $transmit_ip;
    }

    /**
     * @return int
     */
    public function getTransmitIp()
    {
        return $this->transmit_ip;
    }

    /**
     * @param int $proxy_mode
     */
    public function setProxyMode($proxy_mode)
    {
        $this->proxy_mode = $proxy_mode;
    }

    /**
     * @return int
     */
    public function getProxyMode()
    {
        return $this->proxy_mode;
    }

    /**
     * @param int $currency_convert
     */
    public function setCurrencyConvert($currency_convert)
    {
        $this->currency_convert = $currency_convert;
    }

    /**
     * @return int
     */
    public function getCurrencyConvert()
    {
        return $this->currency_convert;
    }
}
