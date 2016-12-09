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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_SessionStatus_Factory
{

    /** @var Payone_SessionStatus_Config */
    protected $config = null;

    /**
     * @constructor
     * @param Payone_SessionStatus_Config $config
     */
    public function __construct(Payone_SessionStatus_Config $config = null)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_SessionStatus_Service_ProtocolRequest
     */
    public function buildServiceProtocolRequest()
    {
        $service = new Payone_SessionStatus_Service_ProtocolRequest();
        return $service;
    }

    /**
     * @return Payone_SessionStatus_Mapper_Request
     */
    protected function buildMapperRequest()
    {
        $mapper = new Payone_SessionStatus_Mapper_Request();
        return $mapper;
    }

    /**
     * @param $key
     * @return Payone_SessionStatus_Service_HandleRequest
     * @throws Exception
     */
    public function buildService($key)
    {
        switch ($key)
        {
            case 'handlerequest' :
                return $this->buildServiceHandleRequest();
                break;
            default :
                throw new Exception('Could not build service with key "' . $key . '"');
                break;
        }
    }

    /**
     * @return Payone_SessionStatus_Service_HandleRequest
     */
    public function buildServiceHandleRequest()
    {
        $service = new Payone_SessionStatus_Service_HandleRequest();
        $service->setMapper($this->buildMapperRequest());

        return $service;
    }

    /**
     * @param string $key
     * @return Payone_SessionStatus_Validator_DefaultParameters
     */
    protected function buildValidatorDefault($key = '')
    {
        $validator = new Payone_SessionStatus_Validator_DefaultParameters();
        $validator->setKey($key);
        return $validator;
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
