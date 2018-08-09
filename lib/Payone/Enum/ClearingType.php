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
 * @package         Payone_Enum
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com, Copyright (c) 2017 <support@e3n.de> - www.e3n.de
 * @author          Matthias Walter <info@noovias.com>,  Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com, http://www.e3n.de
 */
class Payone_Enum_ClearingType
{
    /**
     * NOTE ABOUT ORDER OF THE CONSTANTS (MAGE-380)
     * Some parts of the code use this list of constants in a reverse array, values become keys.
     * In that case, keys must be unique and only the last instance of each key can be considered.
     */

    const AMAZONPAY = 'wlt';
    const DEBITPAYMENT = 'elv';
    const CREDITCARD_IFRAME = 'cc';
    const CREDITCARD = 'cc';
    const ADVANCEPAYMENT = 'vor';
    const INVOICE = 'rec';
    const ONLINEBANKTRANSFERBCT = 'sb';
    const ONLINEBANKTRANSFERPFF = 'sb';
    const ONLINEBANKTRANSFERP24 = 'sb';
    const ONLINEBANKTRANSFERPFC = 'sb';
    const ONLINEBANKTRANSFEREPS = 'sb';
    const ONLINEBANKTRANSFERIDL = 'sb';
    const ONLINEBANKTRANSFERGIROPAY = 'sb';
    const ONLINEBANKTRANSFERSOFORT = 'sb';
    const ONLINEBANKTRANSFER = 'sb';
    const CASHONDELIVERY = 'cod';
    const MASTERPASS = 'wlt';
    const WALLETPAYDIREKT = 'wlt';
    const WALLETPAYPALEXPRESS = 'wlt';
    const WALLETALIPAY = 'wlt';
    const WALLET = 'wlt';
    const BARZAHLEN = 'csh';
    const RATEPAY = 'fnc';
    const RATEPAYDIRECTDEBIT = 'fnc';
    const PAYOLUTIONINVOICING = 'fnc';
    const PAYOLUTIONDEBIT = 'fnc';
    const PAYOLUTIONINSTALLMENT = 'fnc';
    const PAYOLUTION = 'fnc';
    const FINANCING = 'fnc';
    const PAYMENTGUARANTEEINVOICE = 'rec';
}
