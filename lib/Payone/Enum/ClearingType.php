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
     * See comments below
     */

    /** Cash */
    const BARZAHLEN = 'csh';                // Default csh mapping

    /** Cash on delivery */
    const CASHONDELIVERY = 'cod';           // Default cod mapping

    /** Creditcards */
    const CREDITCARD = 'cc';                // Default cc mapping

    /** Debit payments */
    const DEBITPAYMENT = 'elv';             // Default elv mapping

    /** Financing */
    const PAYOLUTION = 'fnc';
    const PAYOLUTIONDEBIT = 'fnc';
    const PAYOLUTIONINSTALLMENT = 'fnc';
    const PAYOLUTIONINVOICING = 'fnc';
    const RATEPAY = 'fnc';
    const RATEPAYINVOICING = 'fnc';
    const RATEPAYDIRECTDEBIT = 'fnc';
    const KLARNAINVOICING = 'fnc';
    const KLARNAINSTALLMENT = 'fnc';
    const KLARNADIRECTDEBIT = 'fnc';
    const FINANCING = 'fnc';                // Default fnc mapping

    /** Invoices */
    const PAYMENTGUARANTEEINVOICE = 'rec';
    const INVOICE = 'rec';                  // Default rec mapping

    /** Online Banktransfer */
    const ONLINEBANKTRANSFERBCT = 'sb';
    const ONLINEBANKTRANSFERPFF = 'sb';
    const ONLINEBANKTRANSFERP24 = 'sb';
    const ONLINEBANKTRANSFERPFC = 'sb';
    const ONLINEBANKTRANSFEREPS = 'sb';
    const ONLINEBANKTRANSFERIDL = 'sb';
    const ONLINEBANKTRANSFERGIROPAY = 'sb';
    const ONLINEBANKTRANSFERSOFORT = 'sb';
    const ONLINEBANKTRANSFER = 'sb';        // Default sb mapping

    /** Pre-payments */
    const ADVANCEPAYMENT = 'vor';           // Default vor mapping

    /** Wallets */
    const AMAZONPAY = 'wlt';
    const WALLETALIPAY = 'wlt';
    const WALLETPAYDIREKT = 'wlt';
    const WALLETPAYDIREKTEXPRESS = 'wlt';
    const WALLETPAYPALEXPRESS = 'wlt';
    const WALLET = 'wlt';                   // Default wlt mapping
}
