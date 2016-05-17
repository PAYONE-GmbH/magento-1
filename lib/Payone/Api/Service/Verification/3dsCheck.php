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
 * Perform a 3dsCheck by providing a Request Object
 *
 * <b>Example:</b>
 * <pre class="prettyprint">
 *
 * // Construct the service (Builder handles dependencies):
 * // custom config can be injected, see Payone_Config
 * $builder = new Payone_Builder();
 *
 * $service = $builder->buildServiceVerification3dscheck();
 *
 * // Construct a valid request:
 * $request = new Payone_Api_Request_3dsCheck();
 * $request->setAid($aid);
 * // Set all required parameters for a "3dscheck" request
 *
 * // Start 3dsCheck action:
 * $response = $service->check($request);
 *
 * </pre>
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Service_Verification_3dsCheck
    extends Payone_Api_Service_Abstract
{
    /**
     * Perform a 3dscheck with the injected request
     *
     * @api
     *
     * @param Payone_Api_Request_3dsCheck $request
     *
     * @return Payone_Api_Response_3dsCheck_Enrolled|Payone_Api_Response_3dsCheck_Invalid|Payone_Api_Response_3dsCheck_Valid|Payone_Api_Response_Error
     * @throws Exception
     */
    public function check(Payone_Api_Request_3dsCheck $request)
    {
        try
        {
            $this->validateRequest($request);

            $requestParams = $request->toArray();

            // Actors:
            $adapter = $this->getAdapter();
            /** @var $mapper Payone_Api_Mapper_Response_3dsCheck */
            $mapper = $this->getMapperResponse();

            // Do the request:
            $responseRaw = $adapter->request($requestParams);
            $response = $mapper->map($responseRaw);

            $this->protocol($request, $response);
        }
        catch (Exception $e) {
            $this->protocolException($e, $request);
            throw $e;
        }

        return $response;
    }

}
