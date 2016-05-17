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
 * @package         Payone_ClientApi
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_ClientApi
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_ClientApi_Request_Abstract implements Payone_ClientApi_Request_Interface
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
     * @var string
     */
    protected $key = '';

    /**
     * @var string
     */
    protected $mode = '';

    /**
     * @var string
     */
    protected $request = '';

    /**
     * @var string
     */
    protected $responsetype;

    /**
     * @var string
     */
    protected $encoding = '';

    /**
     * name of the solution-partner (company)
     *
     * @var string
     */
    protected $solution_name = '';

    /**
     * version of the solution-partner's app / extension / plugin / etc..
     *
     * @var string
     */
    protected $solution_version = '';

    /**
     * system-name
     *
     * @var string
     */
    protected $integrator_name = '';

    /**
     * system-version
     *
     * @var string
     */
    protected $integrator_version = '';

    /**
     * ISO 639
     * @var string
     */
    protected $language = '';

    /**
     * @var string
     */
    protected $hash = '';

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        if (count($data) > 0) {
            $this->init($data);
        }
    }

    /**
     * @param array $data
     */
    public function init(array $data = array())
    {
        foreach ($data as $key => $value) {
            $key = ucwords(str_replace('_', ' ', $key));
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
            $result[$key] = $data;
        }

        ksort($result);

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $stringArray = array();
        foreach ($this->toArray() as $key => $value) {
            $stringArray[] = $key . '=' . $value;
        }

        $result = implode('|', $stringArray);
        return $result;
    }

    /**
     * @param $name
     * @return null|mixed
     */
    public function get($name)
    {
        if (strpos($name, '/')) {
            $explodedName = explode('/', $name);
            if (count($explodedName) != 2) {
                return null;
            }
            $property = $explodedName[0];
            $propertyName = $explodedName[1];
            if (property_exists($this, $property)) {
                $object = $this->$property;
                /**
                 * @var $object Payone_Api_Request_Parameter_Interface
                 */
                if (!($object instanceof Payone_Api_Request_Parameter_Interface)) {
                    return null;
                }

                return $object->get($propertyName);
            }
        }
        elseif (property_exists($this, $name)) {
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
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = md5($key);
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
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
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
     * @param string $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $responseType
     */
    public function setResponsetype($responseType)
    {
        $this->responsetype = $responseType;
    }

    /**
     * @return string
     */
    public function getResponseType()
    {
        return $this->responsetype;
    }

    /**
     * set the system-Name
     *
     * @param string $integrator_name
     */
    public function setIntegratorName($integrator_name)
    {
        $this->integrator_name = $integrator_name;
    }

    /**
     * @return string
     */
    public function getIntegratorName()
    {
        return $this->integrator_name;
    }

    /**
     * set the system-version
     *
     * @param string $integrator_version
     */
    public function setIntegratorVersion($integrator_version)
    {
        $this->integrator_version = $integrator_version;
    }

    /**
     * @return string
     */
    public function getIntegratorVersion()
    {
        return $this->integrator_version;
    }

    /**
     * set the name of the solution-partner (company)
     *
     * @param string $solution_name
     */
    public function setSolutionName($solution_name)
    {
        $this->solution_name = $solution_name;
    }

    /**
     * @return string
     */
    public function getSolutionName()
    {
        return $this->solution_name;
    }

    /**
     * set the version of the solution-partner's app / extension / plugin / etc..
     *
     * @param string $solution_version
     */
    public function setSolutionVersion($solution_version)
    {
        $this->solution_version = $solution_version;
    }

    /**
     * @return string
     */
    public function getSolutionVersion()
    {
        return $this->solution_version;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

}
