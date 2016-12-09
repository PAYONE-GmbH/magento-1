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
 * Perform an AddressCheck by providing a Request Object
 *
 * <b>Example:</b>
 * <pre class="prettyprint">
 * // Construct the service (Builder handles dependencies):
 * // custom config can be injected, see Payone_Config
 * $builder = new Payone_Builder();
 *
 * $service = $builder->buildServiceVerificationAddressCheck();
 *
 * // Construct a valid request:
 * $request = new Payone_Api_Request_AddressCheck();
 * $request->setAid($aid);
 * // Set all required parameters for an "addresscheck" request
 *
 * // Start AddressCheck action:
 * $response = $service->check($request);
 * </pre>
 *
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Service_Management_GetInvoice
    extends Payone_Api_Service_Abstract
{
    /**
     * @param Payone_Api_Request_GetInvoice $request
     * @return Payone_Api_Response_Management_GetInvoice
     * @throws Exception
     */
    public function getInvoice(Payone_Api_Request_GetInvoice $request)
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
