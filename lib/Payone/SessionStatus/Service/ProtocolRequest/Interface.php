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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_SessionStatus
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_SessionStatus_Service_ProtocolRequest_Interface
{
    /**
     * @abstract
     * @param Payone_SessionStatus_Request_Interface $request
     * @param null|Payone_SessionStatus_Response_Interface $response
     */
    public function protocol(
        Payone_SessionStatus_Request_Interface $request,
        Payone_SessionStatus_Response_Interface $response = null
    );

    /**
     * @abstract
     * @param Exception $e
     * @param null|Payone_SessionStatus_Request_Interface $request
     */
    public function protocolException(Exception $e, Payone_SessionStatus_Request_Interface $request = null);

    /**
     * @abstract
     * @param Payone_SessionStatus_Persistence_Interface $repository
     */
    public function addRepository(Payone_SessionStatus_Persistence_Interface $repository);

    /**
     * @abstract
     * @param $key
     */
    public function removeRepository($key);

    /**
     * @abstract
     * @param Payone_Protocol_Logger_Interface $logger
     */
    public function addLogger(Payone_Protocol_Logger_Interface $logger);

    /**
     * @abstract
     * @param $key
     */
    public function removeLogger($key);

}
