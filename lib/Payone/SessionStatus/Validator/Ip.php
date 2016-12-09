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
 * @subpackage      Validator
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_SessionStatus
 * @subpackage      Validator
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_SessionStatus_Validator_Ip
    extends Payone_SessionStatus_Validator_Abstract
{
    /** @var array */
    protected $validIps = array();

    /** @var Payone_SessionStatus_Config */
    protected $config = null;

    /**
     * @param Payone_SessionStatus_Request_Interface $request
     * @throws Payone_SessionStatus_Exception_Validation
     * @return bool
     */
    public function validateRequest(Payone_SessionStatus_Request_Interface $request)
    {
        $remoteAddress = $this->getRemoteAddress();
        $validIps = $this->getValidIps();

        if (in_array($remoteAddress, $validIps)) {
            // this is for exact matches
            return true;
        }

        foreach ($validIps as $ip) {
            $ip = $this->checkForDelimiter($ip);
            if (preg_match($ip, $remoteAddress)) {
                return true;
            }
        }

        throw new Payone_SessionStatus_Exception_Validation();
    }

    /**
     * Check if IP-String has delimiter, because preg_match needs string-delimiter
     * @param $ip
     * @return string
     */
    protected function checkForDelimiter($ip)
    {
        if (substr($ip, 0, 1) !== '/') {
            $ip = '/' . $ip;
        }

        if (substr($ip, -1, 1) !== '/') {
            $ip = $ip . '/';
        }

        return $ip;
    }

    /**
     * @param array $validIps
     */
    public function setValidIps(array $validIps)
    {
        $this->validIps = $validIps;
    }

    /**
     * @return array
     */
    public function getValidIps()
    {
        return $this->validIps;
    }

    /**
     * Checks if ProxyCheck should be used. Returns the Remote-IP
     *
     * @return string
     */
    public function getRemoteAddress()
    {
        $blCheckProxy = (bool)$this->getProxyCheckEnabled();
        
        return Mage::app()->getRequest()->getClientIp($blCheckProxy);

    }

    /**
     * @return boolean
     */
    public function getProxyCheckEnabled()
    {
        return $this->getConfig()->getValue('validator/proxy/enabled');
    }

    /**
     * @param Payone_SessionStatus_Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_SessionStatus_Config
     */
    public function getConfig()
    {
        return $this->config;
    }

}