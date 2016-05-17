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
 * @subpackage      Adapter
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Adapter
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Api_Adapter_Http_Abstract
    implements Payone_Api_Adapter_Interface
{
    /**
     * @todo refactor to use some kind of config
     * @todo use timeout from config and fallback to default
     */
    const DEFAULT_TIMEOUT   = 45;

    const SDK_VERSION_KEY   = 'sdk_version';
    const SDK_TYPE_KEY      = 'sdk_type';
    const SDK_VERSION       = '2.0.0';
    const SDK_TYPE          = 'php';

    /**
     * @var string
     */
    protected $url = '';
    /**
     * @var array
     */
    protected $params = array();

    /** @var string */
    protected $rawResponse = '';

    /**
     * @param array $params
     * @throws Payone_Api_Exception_InvalidParameters
     * @throws Payone_Api_Exception_InvalidUrl
     * @throws Payone_Api_Exception_InvalidResponse
     * @return array
     */
    public function request(array $params)
    {
        $this->setParams($params);

        $this->validate();

        $this->params[self::SDK_TYPE_KEY]    = self::SDK_TYPE;
        $this->params[self::SDK_VERSION_KEY] = self::SDK_VERSION;

        $responseRaw = $this->doRequest();
        
        $result = $this->parseResponse($responseRaw);

        return $result;
    }

    /**
     * @return array
     * @throws Payone_Api_Exception_InvalidResponse
     */
    abstract protected function doRequest();

    /**
     * @throws Payone_Api_Exception_InvalidParameters
     * @throws Payone_Api_Exception_InvalidUrl
     */
    protected function validate()
    {
        if ($this->getUrl() == '') {
            throw new Payone_Api_Exception_InvalidUrl();
        }

        if (count($this->getParams()) <= 0) {
            throw new Payone_Api_Exception_InvalidParameters();
        }
    }

    /**
     * @return array
     */
    protected function generateUrlArray()
    {
        $urlRequest = $this->getUrl() . '?' . http_build_query($this->getParams(), null, '&');
        $urlArray = parse_url($urlRequest);
        return $urlArray;
    }

    /**
     * @param array $responseRaw
     * @return array
     */
    protected function parseResponse(array $responseRaw = array())
    {
        $result = array();

        if (count($responseRaw) == 0) {
            return $result;
        }

        foreach ($responseRaw as $key => $line) {
            $pos = strpos($line, "=");

            if ($pos === false) {
                if (strlen($line) > 0) {
                    $result[$key] = $line;
                }
                continue;
            }

            $lineArray = explode('=', $line);
            $resultKey = array_shift($lineArray);
            $result[$resultKey] = implode('=', $lineArray);
        }

        return $result;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @param string $rawResponse
     */
    protected function setRawResponse($rawResponse)
    {
        $this->rawResponse = $rawResponse;
    }

}
