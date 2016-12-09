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
class Payone_TransactionStatus_Service_ProtocolRequest
    extends Payone_Protocol_Service_Protocol_Abstract
    implements Payone_TransactionStatus_Service_ProtocolRequest_Interface
{
    /**
     * @var Payone_TransactionStatus_Persistence_Interface[]
     */
    protected $repositories = array();

    /**
     * @param Payone_TransactionStatus_Request_Interface $request
     * @param null|Payone_TransactionStatus_Response_Interface $response
     */
    public function protocol(
        Payone_TransactionStatus_Request_Interface $request,
        Payone_TransactionStatus_Response_Interface $response = null
    )
    {
        foreach ($this->loggers as $key => $logger)
        {
            /** @var $logger Payone_Protocol_Logger_Interface */
            $requestAsString = $request->__toString();
            $responseAsString = $response->__toString();

            $logger->log($requestAsString, Payone_Protocol_Logger_Interface::LEVEL_INFO);
            $logger->log($responseAsString, Payone_Protocol_Logger_Interface::LEVEL_INFO);
        }

        foreach ($this->repositories as $key => $repository)
        {
            /** @var $repository Payone_TransactionStatus_Persistence_Interface */
            $repository->save($request, $response);
        }
    }

    /**
     * @param Exception $e
     * @param null|Payone_TransactionStatus_Request_Interface $request
     */
    public function protocolException(Exception $e, Payone_TransactionStatus_Request_Interface $request = null)
    {
        if ($request !== null) {
            $this->getServiceApplyFilters()->apply($request->toArray());
        }

        foreach ($this->loggers as $key => $logger)
        {
            /** @var $logger Payone_Protocol_Logger_Interface */
            $logger->log(get_class($e) . ' ' . $e->getMessage());

            if ($request !== null) {
                $requestAsString = $request->__toString();
                $logger->log($requestAsString, Payone_Protocol_Logger_Interface::LEVEL_ERROR);
            }
        }
    }

    /**
     * @param Payone_TransactionStatus_Persistence_Interface $repository
     */
    public function addRepository(Payone_TransactionStatus_Persistence_Interface $repository)
    {
        $this->repositories[$repository->getKey()] = $repository;
    }

    /**
     * @param $key
     * @return bool
     */
    public function removeRepository($key)
    {
        if (array_key_exists($key, $this->repositories)) {
            unset($this->repositories[$key]);
            return true;
        }
        return false;
    }


}