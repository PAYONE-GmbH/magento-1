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
 * Capture a Payment by providing a Request Object
 *
 * <b>Example:</b>
 * <pre class="prettyprint">
 * // Construct the service (Builder handles dependencies):
 * // custom config can be injected, see Payone_Config
 * $builder = new Payone_Builder();
 *
 * $service = $builder->buildServicePaymentCapture();
 *
 * // Construct a valid request:
 * $request = new Payone_Api_Request_Capture();
 * $request->setAid($aid);
 * // Set all required parameters for a "capture" request
 *
 * // Start capture action:
 * $response = $service->capture($request);
 * </pre>
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Service_Payment_Capture
    extends Payone_Api_Service_Payment_Abstract
    implements Payone_Api_Service_Payment_CaptureInterface
{
    /**
     * Perform Capture for the injected Request
     *
     * @api
     *
     * @param Payone_Api_Request_Capture $request
     * @return Payone_Api_Response_Capture_Approved|Payone_Api_Response_Error
     * @throws Exception
     */
    public function capture(Payone_Api_Request_Capture $request)
    {
        try {
            $this->validateRequest($request);

            $requestParams = $this->getMapperRequest()->map($request);

            $responseRaw = $this->getAdapter()->request($requestParams);

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
