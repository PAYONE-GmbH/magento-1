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
class Payone_Api_Service_Management_GetFile
    extends Payone_Api_Service_Abstract
{
    /**
     * @param Payone_Api_Request_GetFile $request
     * @return Payone_Api_Response_Management_GetFile
     * @throws Exception
     */
    public function getFile(Payone_Api_Request_GetFile $request)
    {
        try {
            $this->validateRequest($request);

            $requestParams = $request->toArray();

            $adapter = $this->getAdapter();

            $responseRaw = $adapter->request($requestParams);

            $response = $this->getMapperResponse()->map($responseRaw);

            $response->setRawResponse($adapter->getRawResponse());

             $this->protocol($request, $response);
        }
        catch (Exception $e) {
            $this->protocolException($e, $request);
            throw $e;
        }

        return $response;
    }

}
