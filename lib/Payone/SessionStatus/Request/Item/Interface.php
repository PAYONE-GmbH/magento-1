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
interface Payone_SessionStatus_Request_Item_Interface extends Payone_Protocol_Filter_Filterable
{
    /**
     * @abstract
     * @param array $data
     */
    public function init(array $data = array());

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return array
     */
    public function __toString();

    /**
     * @param int $accessid
     */
    public function setAccessid($accessid);

    /**
     * @return int
     */
    public function getAccessid();

    /**
     * @param string $action
     */
    public function setAction($action);

    /**
     * @return string
     */
    public function getAction();

    /**
     * @param int $portalid
     */
    public function setPortalid($portalid);

    /**
     * @return int
     */
    public function getPortalid();

    /**
     * @param int $productid
     */
    public function setProductid($productid);

    /**
     * @return int
     */
    public function getProductid();

    /**
     * @param int $expiretime
     */
    public function setExpiretime($expiretime);

    /**
     * @return int
     */
    public function getExpiretime();

    /**
     * @param int $userid
     */
    public function setUserid($userid);

    /**
     * @return int
     */
    public function getUserid();

    /**
     * @param string $customerid
     */
    public function setCustomerid($customerid);

    /**
     * @return string
     */
    public function getCustomerid();

    /**
     * @param string $accessname
     */
    public function setAccessname($accessname);

    /**
     * @return string
     */
    public function getAccessname();

    /**
     * @param string $accesscode
     */
    public function setAccesscode($accesscode);

    /**
     * @return string
     */
    public function getAccesscode();

    /**
     * @param string $ip
     */
    public function setIp($ip);

    /**
     * @return string
     */
    public function getIp();

    /**
     * @param string $param
     */
    public function setParam($param);

    /**
     * @return string
     */
    public function getParam();
}
