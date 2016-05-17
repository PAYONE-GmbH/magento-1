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
 * @package         Payone_TransactionStatus
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_TransactionStatus
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */



abstract class Payone_TransactionStatus_Request_Abstract
    implements Payone_TransactionStatus_Request_Interface
{

    /**
     * @var Payone_Protocol_Service_ApplyFilters
     */
    private $applyFilters = NULL;

    function __construct(array $params = array())
    {
        if (count($params) > 0) {
            $this->init($params);
        }
    }

    public function init(array $data = array())
    {
        foreach ($data as $key => $value)
        {
            $this->set($key, $value);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if($this->applyFilters) {
            $result = $this->applyFilters->apply($this->toArray());
        } else {
            $protocolFactory     = new Payone_Protocol_Factory();
            $defaultApplyFilters = $protocolFactory->buildServiceApplyFilters();
            $result = $defaultApplyFilters->apply($this->toArray());
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $data)
        {
            if ($data === null) {
                continue;
            }
            elseif ($data instanceof Payone_Protocol_Service_ApplyFilters == false) {
                $result[$key] = $data;
            }
        }

        ksort($result);

        return $result;
    }

    /**
     * @param $name
     * @return null|mixed
     */
    public function get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return boolean|null
     */
    public function set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return true;
        }
        return null;
    }

    /**
     * @param Payone_Protocol_Service_ApplyFilters $applyFilters
     */
    public function setApplyFilters(Payone_Protocol_Service_ApplyFilters $applyFilters)
    {
        $this->applyFilters = $applyFilters;
    }
}
