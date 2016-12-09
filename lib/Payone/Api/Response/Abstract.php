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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Api_Response_Abstract implements Payone_Api_Response_Interface
{
    protected $status = NULL;

    protected $rawResponse = NULL;

    /**
     * @var Payone_Protocol_Service_ApplyFilters
     */
    private $applyFilters = NULL;

    /**
     * @param array $params
     */
    function __construct(array $params = array())
    {
        $this->setRawResponse($params);
        if (count($params) > 0) {
            $this->init($params);
        }
    }

    /**
     * @param array $data
     */
    public function init(array $data = array())
    {
        foreach ($data as $key => $value) {
            $key = ucwords(str_replace(array('_', '[', ']'), ' ', $key));
            $method = 'set' . str_replace(' ', '', $key);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $data) {
            if ($data === null) {
                continue;
            }
            elseif ($data instanceof Payone_Protocol_Service_ApplyFilters == false) {
                $result[$key] = $data;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->_toString($this->toArray());
    }
    
    public function getRawResponseToString()
    {
        return $this->_toString($this->getRawResponse());
    }
    
    protected function _toString($aValue)
    {
        if($this->applyFilters) {
            $result = $this->applyFilters->apply($aValue);
        } else {
            $protocolFactory     = new Payone_Protocol_Factory();
            $defaultApplyFilters = $protocolFactory->buildServiceApplyFilters();
            $result = $defaultApplyFilters->apply($aValue);
        }

        return $result;        
    }


    /**
     * @return bool
     */
    public function isApproved()
    {
        if ($this->getStatus() === Payone_Api_Enum_ResponseType::APPROVED) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        if ($this->getStatus() === Payone_Api_Enum_ResponseType::REDIRECT) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if ($this->getStatus() === Payone_Api_Enum_ResponseType::VALID) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isInvalid()
    {
        if ($this->getStatus() === Payone_Api_Enum_ResponseType::INVALID) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isBlocked()
    {
        if ($this->getStatus() === Payone_Api_Enum_ResponseType::BLOCKED) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isEnrolled()
    {
        if ($this->getStatus() === Payone_Api_Enum_ResponseType::ENROLLED) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        if ($this->getStatus() === Payone_Api_Enum_ResponseType::ERROR) {
            return true;
        }

        return false;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $key
     * @return null|mixed
     */
    public function getValue($key)
    {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @param string $name
     * @return boolean|null
     */
    public function setValue($key, $name)
    {
        return $this->set($key, $name);
    }

    /**
     * @param $name
     * @return null|mixed
     */
    protected function get($name)
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
    protected function set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return true;
        }

        return null;
    }

    /**
     * @param $rawResponse
     */
    public function setRawResponse($rawResponse)
    {
        $this->rawResponse = $rawResponse;
    }
    /**
     * @return null
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @param Payone_Protocol_Service_ApplyFilters $applyFilters
     */
    public function setApplyFilters(Payone_Protocol_Service_ApplyFilters $applyFilters)
    {
        $this->applyFilters = $applyFilters;
    }
}
