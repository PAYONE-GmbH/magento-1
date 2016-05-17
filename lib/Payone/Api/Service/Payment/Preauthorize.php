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
 * Preauthorize a new Payment by providing a Request Object
 *
 * An preauthoorized Transaction needs to be captured aftwards
 *
 *
 * <b>Example Using the Api Builder</b>
 * <pre class="prettyprint">
 * // Construct the service (Builder handles dependencies):
 * // custom config can be injected, see Payone_Config
 * $builder = new Payone_Builder();
 *
 * $service = $builder->buildServicePaymentPreauthorize();
 *
 * // Construct a valid request:
 * $request = new Payone_Api_Request_Preauthorization();
 * $request->setAid($aid);
 * // Set all required parameters for a "preauthorize" request
 *
 * // Start preauthorize action:
 * $response = $service->preauthorize($request);
 *
 * </pre>
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 *
 * @api
 */
class Payone_Api_Service_Payment_Preauthorize
    extends Payone_Api_Service_Payment_Abstract
    implements Payone_Api_Service_Payment_PreauthorizeInterface
{
    /**
     * Execute Preauthorize for the injected Request
     *
     * @api
     *
     * @param Payone_Api_Request_Preauthorization $request
     * @return Payone_Api_Response_Error|Payone_Api_Response_Preauthorization_Approved|Payone_Api_Response_Preauthorization_Redirect
     * @throws Exception
     */
    public function preauthorize(Payone_Api_Request_Preauthorization $request)
    {
        try {
            $this->validateRequest($request);

            $requestParams = $this->getMapperRequest()->map($request);

            if($request->isFrontendApiCall() === false) {
                $responseRaw = $this->getAdapter()->request($requestParams);
            } else {
                $responseRaw = $request->getFrontendApiResponse();
            }

            $response = $this->getMapperResponse()->map($responseRaw);

            $this->protocol($request, $response);
        }
        catch (Exception $e) {
            $this->protocolException($e, $request);
            throw $e;
        }

        return $response;
    }
}
