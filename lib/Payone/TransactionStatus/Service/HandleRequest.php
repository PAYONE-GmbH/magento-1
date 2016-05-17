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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_TransactionStatus
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_TransactionStatus_Service_HandleRequest
{
    /**
     * @var Payone_TransactionStatus_Service_ProtocolRequest_Interface
     */
    protected $serviceProtocol = null;
    /**
     * @todo we could use a Service here which uses a collection of Valdiators to validate the Request
     *
     * @var Payone_TransactionStatus_Validator_Interface[]
     */
    protected $validators = null;
    /**
     * @var Payone_TransactionStatus_Mapper_RequestInterface
     */
    protected $mapper = null;

    /**
     * @param Payone_TransactionStatus_Request $request
     * @return Payone_TransactionStatus_Response
     */
    public function handleByRequest(Payone_TransactionStatus_Request $request)
    {
        return $this->handle($request);
    }

    /**
     * @param array $data
     * @return Payone_TransactionStatus_Response
     * @throws Payone_TransactionStatus_Exception_NoRequestData
     */
    public function handleByArray(array $data)
    {
        if (count($data) == 0) {
            throw new Payone_TransactionStatus_Exception_NoRequestData();
        }
        $request = $this->getMapper()->mapByArray($data);
        return $this->handle($request);
    }

    /**
     * @return Payone_TransactionStatus_Response
     * @throws Payone_TransactionStatus_Exception_NoPostRequest
     */
    public function handleByPost()
    {
        if (count($_POST) == 0) {
            throw new Payone_TransactionStatus_Exception_NoPostRequest();
        }
        $request = $this->getMapper()->mapByArray($_POST);
        return $this->handle($request);
    }

    /**
     * @param Payone_TransactionStatus_Request $request
     * @return Payone_TransactionStatus_Response
     * @throws Exception
     */
    protected function handle(Payone_TransactionStatus_Request $request)
    {
        try {
            // Validate
            $this->validateRequest($request);

            //
            $response = new Payone_TransactionStatus_Response('TSOK');

            if($request->getClearingtype() == 'cc') {
                $this->_handleTransactionId($request);
            }

            // Protocol
            $this->protocol($request, $response);
        }
        catch (Exception $e) {
            $this->protocolException($e, $request);
            throw $e;
        }

        return $response;
    }
    
    protected function _handleTransactionId(Payone_TransactionStatus_Request $oRequest) {
        $oFactory = new Payone_Core_Model_Factory();
        $oTransaction = $oFactory->getModelTransaction();
        $oTransaction->load($oRequest->getReference(), 'reference');
        if($oTransaction->getFrontendApiCall() == 1 && !$oTransaction->getTxid()) {
            $oTransaction->setTxid($oRequest->getTxid());
            $oTransaction->save();
        }
    }

    /**
     * @param Payone_TransactionStatus_Request $request
     */
    protected function validateRequest(Payone_TransactionStatus_Request $request)
    {
        $validators = $this->getValidators();
        foreach ($validators as $validator) {
            if ($validator instanceof Payone_TransactionStatus_Validator_Interface) {
                $validator->validateRequest($request);
            }
        }
    }


    /**
     * @param Payone_TransactionStatus_Request_Interface $request
     * @param Payone_TransactionStatus_Response_Interface $response
     */
    protected function protocol(Payone_TransactionStatus_Request_Interface $request,
                                Payone_TransactionStatus_Response_Interface $response)
    {
        $serviceProtocol = $this->getServiceProtocol();
        if ($serviceProtocol instanceof Payone_TransactionStatus_Service_ProtocolRequest_Interface) {
            $serviceProtocol->protocol($request, $response);
        }
    }

    /**
     * @param Exception $e
     * @param Payone_TransactionStatus_Request_Interface $request
     */
    protected function protocolException(Exception $e, Payone_TransactionStatus_Request_Interface $request)
    {
        $serviceProtocol = $this->getServiceProtocol();
        if ($serviceProtocol instanceof Payone_TransactionStatus_Service_ProtocolRequest_Interface) {
            $serviceProtocol->protocolException($e, $request);
        }
    }

    /**
     * @param \Payone_TransactionStatus_Service_ProtocolRequest_Interface $serviceProtocol
     */
    public function setServiceProtocol(Payone_TransactionStatus_Service_ProtocolRequest_Interface $serviceProtocol)
    {
        $this->serviceProtocol = $serviceProtocol;
    }

    /**
     * @return \Payone_TransactionStatus_Service_ProtocolRequest_Interface
     */
    public function getServiceProtocol()
    {
        return $this->serviceProtocol;
    }

    /**
     * @param Payone_TransactionStatus_Validator_Interface[] $validator
     */
    public function setValidators(array $validator)
    {
        $this->validators = $validator;
    }

    /**
     * @return Payone_TransactionStatus_Validator_Interface[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @param Payone_TransactionStatus_Validator_Interface $validator
     */
    public function addValidator(Payone_TransactionStatus_Validator_Interface $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * @param \Payone_TransactionStatus_Mapper_RequestInterface $mapper
     */
    public function setMapper(Payone_TransactionStatus_Mapper_RequestInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return \Payone_TransactionStatus_Mapper_RequestInterface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

}
