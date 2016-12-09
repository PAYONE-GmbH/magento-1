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
class Payone_Api_Enum_RequestType
{
    const PREAUTHORIZATION = 'preauthorization';
    const AUTHORIZATION = 'authorization';
    const CAPTURE = 'capture';
    const REFUND = 'refund';
    const DEBIT = 'debit';
    const CHECK3DS = '3dscheck';
    const ADDRESSCHECK = 'addresscheck';
    const CONSUMERSCORE = 'consumerscore';
    const BANKACCOUNTCHECK = 'bankaccountcheck';
    const CREDITCARDCHECK = 'creditcardcheck';
    const GETINVOICE = 'getinvoice';
    const CREATEACCESS = 'createaccess';
    const UPDATEACCESS = 'updateaccess';
    const MANAGEMANDATE = 'managemandate';
    const GETFILE = 'getfile';
    const VAUTHORIZATION = 'vauthorization';
    
    /*
     * init paypal express checkout
     */
    const GENERICPAYMENT = 'genericpayment';
}
