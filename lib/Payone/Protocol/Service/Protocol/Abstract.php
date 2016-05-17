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
 * @package         Payone_Protocol
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Protocol
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Protocol_Service_Protocol_Abstract
{
    /**
     * @var Payone_Protocol_Service_ApplyFilters
     */
    protected $serviceApplyFilters = null;
    /**
     * @var Payone_Protocol_Logger_Interface[]
     */
    protected $loggers = array();

    /**
     * @param \Payone_Protocol_Service_ApplyFilters $serviceApplyFilters
     */
    public function setServiceApplyFilters(Payone_Protocol_Service_ApplyFilters $serviceApplyFilters)
    {
        $this->serviceApplyFilters = $serviceApplyFilters;
    }

    /**
     * @return \Payone_Protocol_Service_ApplyFilters
     */
    public function getServiceApplyFilters()
    {
        return $this->serviceApplyFilters;
    }

    /**
     * @param Payone_Protocol_Logger_Interface $logger
     */
    public function addLogger(Payone_Protocol_Logger_Interface $logger)
    {
        $this->loggers[$logger->getKey()] = $logger;
    }

    /**
     * @param $key
     * @return bool
     */
    public function removeLogger($key)
    {
        if (array_key_exists($key, $this->loggers)) {
            unset($this->loggers[$key]);
            return true;
        }
        return false;
    }

    /**
     * @param string $key
     * @return null|Payone_Protocol_Logger_Interface NULL if logger was not found.
     */
    public function getLogger($key)
    {
        if (array_key_exists($key, $this->loggers)) {
            return $this->loggers[$key];
        }
        return NULL;
    }
}
