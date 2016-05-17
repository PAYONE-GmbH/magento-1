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
 * @package         Payone_SessionStatus
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_SessionStatus
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */


class Payone_SessionStatus_Request_Item extends Payone_SessionStatus_Request_Item_Abstract
{
    /**
     * @var int
     */
    protected $accessid = NULL;
    /**
     * @var string
     */
    protected $action = NULL;
    /**
     * @var int
     */
    protected $portalid = NULL;
    /**
     * @var int
     */
    protected $productid = NULL;
    /**
     * @var int
     */
    protected $expiretime = NULL;
    /**
     * @var int
     */
    protected $userid = NULL;
    /**
     * @var string
     */
    protected $customerid = NULL;
    /**
     * @var string
     */
    protected $accessname = NULL;
    /**
     * @var string
     */
    protected $accesscode = NULL;
    /**
     * @var string
     */
    protected $ip = NULL;
    /**
     * @var string
     */
    protected $param = NULL;


    /**
     * @param int $key
     * @return array
     */
    public function toArrayByKey($key)
    {
        $data = array();
        $data['accessid[' . $key . ']'] = $this->getAccessid();
        $data['action[' . $key . ']'] = $this->getAction();
        $data['portalid[' . $key . ']'] = $this->getPortalid();
        $data['productid[' . $key . ']'] = $this->getProductid();
        $data['expiretime[' . $key . ']'] = $this->getExpiretime();
        $data['userid[' . $key . ']'] = $this->getUserid();
        $data['customerid[' . $key . ']'] = $this->getCustomerid();
        $data['accessname[' . $key . ']'] = $this->getAccessname();
        $data['accesscode[' . $key . ']'] = $this->getAccesscode();
        $data['ip[' . $key . ']'] = $this->getIp();
        $data['param[' . $key . ']'] = $this->getParam();
        return $data;
    }

    /**
     * @param string $accesscode
     */
    public function setAccesscode($accesscode)
    {
        $this->accesscode = $accesscode;
    }

    /**
     * @return string
     */
    public function getAccesscode()
    {
        return $this->accesscode;
    }

    /**
     * @param int $accessid
     */
    public function setAccessid($accessid)
    {
        $this->accessid = $accessid;
    }

    /**
     * @return int
     */
    public function getAccessid()
    {
        return $this->accessid;
    }

    /**
     * @param string $accessname
     */
    public function setAccessname($accessname)
    {
        $this->accessname = $accessname;
    }

    /**
     * @return string
     */
    public function getAccessname()
    {
        return $this->accessname;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $customerid
     */
    public function setCustomerid($customerid)
    {
        $this->customerid = $customerid;
    }

    /**
     * @return string
     */
    public function getCustomerid()
    {
        return $this->customerid;
    }

    /**
     * @param int $expiretime
     */
    public function setExpiretime($expiretime)
    {
        $this->expiretime = $expiretime;
    }

    /**
     * @return int
     */
    public function getExpiretime()
    {
        return $this->expiretime;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
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
     * @param int $productid
     */
    public function setProductid($productid)
    {
        $this->productid = $productid;
    }

    /**
     * @return int
     */
    public function getProductid()
    {
        return $this->productid;
    }

    /**
     * @param int $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

}
