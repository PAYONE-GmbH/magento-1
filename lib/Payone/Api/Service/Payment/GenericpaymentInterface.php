<?php
/**
 * With the "genericpayment" request you will initiate the 
 * PayPal Express Checkout process. * 
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
 * @author          Ronny Schröder 
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @api
 */
interface Payone_Api_Service_Payment_GenericpaymentInterface
{
    /**
     * Execute genericpayment request
     * @param Payone_Api_Request_Genericpayment $request
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     * @throws Exception
     */
    public function request(Payone_Api_Request_Genericpayment $request);

}
