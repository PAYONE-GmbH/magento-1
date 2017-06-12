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
 * @subpackage      Enum
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Enum
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Enum_WalletType
{

    const PAYPAL_EXPRESS = 'PPE';

    /* Example
     * 
     * 
     *       $builder = new Payone_Builder();
     *       $service = $builder->buildServicePaymentAuthorize();
     *
     *       $requestData = array(
     *           'clearingtype' => Payone_Enum_ClearingType::WALLET,
     *           'reference' => 'your order number',
     *           'amount' => 12.95,
     *           'currency' => 'EUR',
     *           'personalData' => new Payone_Api_Request_Parameter_Authorization_PersonalData(array(...customer data ...)),
     *           'payment' => new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet(array(
     *               'wallettype' => Payone_Api_Enum_WalletType::YAPITAL,
     *               'successurl' => 'http://your-shop.com/success.php',
     *               'errorurl' => 'http://your-shop.com/error.php',
     *               'backurl' => 'http://your-shop.com/back.php'
     *                   ))
     *       );
     *       
     *       $authorization = new Payone_Api_Request_Authorization(array_merge($this->getAccountData(), $requestData));
     *
     *       $response = $service->authorize($authorization);
     * 		
     *       Response:
     * 	
     *       Payone_Api_Response_Authorization_Redirect Object
     *       (
     * 		[txid:protected] => 155901830
     * 		[userid:protected] => 57080810
     * 		[redirecturl:protected] => https://demo.dev.yapital.com/web-ui/widget.html?id=01-9XXX
     * 		[status:protected] => REDIRECT
     * 		...
     *       )
     *      
     *       If response status is REDIRECT, send customer to the redirecturl (Yapital).
     */
    const PAYDIREKT = 'PDT';
    const ALIPAY = 'ALP';

}
