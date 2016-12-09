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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Api_Service_Abstract
    implements Payone_Api_Service_Interface
{
    /**
     * @var Payone_Api_Adapter_Interface
     */
    protected $adapter = null;
    /**
     * @var Payone_Api_Service_ProtocolRequest_Interface
     */
    protected $serviceProtocol = null;
    /** @var Payone_Api_Mapper_Request_Interface */
    protected $mapperRequest = null;
    /** @var Payone_Api_Mapper_Response_Interface */
    protected $mapperResponse = null;

    /**
     * @todo we could use a Service here which uses a collection of Valdiators to validate the Request
     *
     * @var Payone_Api_Validator_Interface
     */
    protected $validator = null;

    /**
     * @param Payone_Api_Request_Interface $request
     */
    protected function validateRequest(Payone_Api_Request_Interface $request)
    {
        $validator = $this->getValidator();
        if ($validator instanceof Payone_Api_Validator_Interface) {
            $validator->validateRequest($request);
        }
    }

    /**
     * @param Payone_Api_Request_Interface $request
     * @param Payone_Api_Response_Interface $response
     */
    protected function protocol(Payone_Api_Request_Interface $request,
                                Payone_Api_Response_Interface $response)
    {
        $serviceProtocol = $this->getServiceProtocol();
        if ($serviceProtocol instanceof Payone_Api_Service_ProtocolRequest_Interface) {
            $serviceProtocol->protocol($request, $response);
        }
    }

    /**
     * @param Exception $e
     * @param Payone_Api_Request_Interface $request
     */
    protected function protocolException(Exception $e, Payone_Api_Request_Interface $request)
    {
        $serviceProtocol = $this->getServiceProtocol();
        if ($serviceProtocol instanceof Payone_Api_Service_ProtocolRequest_Interface) {
            $serviceProtocol->protocolException($e, $request);
        }
    }

    /**
     * @param Payone_Api_Adapter_Interface $adapter
     */
    public function setAdapter(Payone_Api_Adapter_Interface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return Payone_Api_Adapter_Interface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param Payone_Api_Service_ProtocolRequest_Interface $serviceProtocol
     */
    public function setServiceProtocol(Payone_Api_Service_ProtocolRequest_Interface $serviceProtocol)
    {
        $this->serviceProtocol = $serviceProtocol;
    }

    /**
     * @return Payone_Api_Service_ProtocolRequest_Interface
     */
    public function getServiceProtocol()
    {
        return $this->serviceProtocol;
    }

    /**
     * @param Payone_Api_Mapper_Request_Interface $mapperRequest
     */
    public function setMapperRequest($mapperRequest)
    {
        $this->mapperRequest = $mapperRequest;
    }

    /**
     * @return Payone_Api_Mapper_Request_Interface
     */
    public function getMapperRequest()
    {
        return $this->mapperRequest;
    }

    /**
     * @param Payone_Api_Mapper_Response_Interface $mapperResponse
     */
    public function setMapperResponse(Payone_Api_Mapper_Response_Interface $mapperResponse)
    {
        $this->mapperResponse = $mapperResponse;
    }

    /**
     * @return Payone_Api_Mapper_Response_Interface
     */
    public function getMapperResponse()
    {
        return $this->mapperResponse;
    }

    /**
     * @param \Payone_Api_Validator_Interface $validator
     */
    public function setValidator(Payone_Api_Validator_Interface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return \Payone_Api_Validator_Interface
     */
    public function getValidator()
    {
        return $this->validator;
    }

}