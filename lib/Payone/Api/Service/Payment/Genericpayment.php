<?php
/*
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
 * With the "genericpayment" request you will initiate the PayPal 
 * Express Checkout process.
 * Process:
 * 
 * 1. you have to use the request “genericpayment” with 
 * “add_paydata[action]=setexpresscheckout” and you get an 
 * unique “workorderid” and a redirect-url to paypal-login from the 
 * PAYONE platform
 * 2. After the successful request you do another “genericpayment” request 
 * with “add_paydata[action]=getexpresscheckoutdetails” to get the 
 * shipment-data of the customer
 * 3. To charge the customers PayPal account you have to do 
 * a request “preauthorization/capture” or an “authorization” 
 * with the unique “workorderid”
 *
 * <b>Example Using the Api Builder</b>
 * <pre class="prettyprint">
 * // Construct the service (Builder handles dependencies):
 * // custom config can be injected, see Payone_Config
 *   $builder = new Payone_Builder();
 *
 *   $service = $builder->buildServicePaymentGenericpayment();
 *
 * // Construct a valid request:
 *   $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
 *   $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem(
 *           array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYPAL_ECS_SET_EXPRESSCHECKOUT)
 *   ));
 *    $requestData = array(
 *       'clearingtype' => Payone_Enum_ClearingType::WALLET,
 *       'amount' => 12.00,
 *       'currency' => 'EUR',
 *       'narrative_text' => 'Paypal ECS Step1',
 *       'paydata' => $paydata,
 *       'wallet' => new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet(array(
 *           'wallettype' => Payone_Api_Enum_WalletType::PAYPAL_EXPRESS,
 *           'successurl' => 'http://your-shop.com/payone/success.php',
 *           'errorurl' => 'http://your-shop.com/payone/error.php',
 *           'backurl' => 'http://your-shop.com/payone/back.php'
 *               ))
 *   );
 *    $request = new Payone_Api_Request_Genericpayment(array_merge($this->getAccountData(), $requestData));
 *
 * // Start genericpayment request:
 *    $response = $service->request($request);
 *
 * </pre>
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Service
 * @author          Ronny Schröder 
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 *
 * @api
 */
class Payone_Api_Service_Payment_Genericpayment extends Payone_Api_Service_Payment_Abstract implements Payone_Api_Service_Payment_GenericpaymentInterface {

    /**
     * Execute Genericpayment Request
     *
     * @api
     * @param Payone_Api_Request_Genericpayment $request
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     * @throws Exception
     */
    public function request(Payone_Api_Request_Genericpayment $request) {
        try {
            $this->validateRequest($request);

            $requestParams = $this->getMapperRequest()->map($request);

            $responseRaw = $this->getAdapter()->request($requestParams);

            $response = $this->getMapperResponse()->map($responseRaw);

            $this->protocol($request, $response);
        } catch (Exception $e) {
            $this->protocolException($e, $request);
            throw $e;
        }

        return $response;
    }

}
