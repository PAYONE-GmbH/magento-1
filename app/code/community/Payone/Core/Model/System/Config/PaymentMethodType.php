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
 * Do not edit or add to this file if you wish to upgrade Payone_Core to newer
 * versions in the future. If you wish to customize Payone_Core for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_System_Config_PaymentMethodType extends Payone_Core_Model_System_Config_Abstract
{
    const ADVANCEPAYMENT = 'advance_payment';
    const AMAZONPAY = 'amazon_pay';
    const CASHONDELIVERY = 'cash_on_delivery';
    const CREDITCARD = 'creditcard';
    //const CREDITCARD_IFRAME = 'creditcard_iframe';  // MAGE-392: Deprecated, method is removed
    const DEBITPAYMENT = 'debit_payment';
    const SAFEINVOICE = 'safe_invoice';
    const INVOICE = 'invoice';
    const ONLINEBANKTRANSFERBCT = 'online_bank_transfer_bct';
    const ONLINEBANKTRANSFERPFC = 'online_bank_transfer_pfc';
    const ONLINEBANKTRANSFERGIROPAY = 'online_bank_transfer_giropay';
    const ONLINEBANKTRANSFERPFF = 'online_bank_transfer_pff';
    const ONLINEBANKTRANSFEREPS = 'online_bank_transfer_eps';
    const ONLINEBANKTRANSFERP24 = 'online_bank_transfer_p24';
    const ONLINEBANKTRANSFERIDL = 'online_bank_transfer_idl';
    const ONLINEBANKTRANSFERSOFORT = 'online_bank_transfer_sofortueberweisung';
    const ONLINEBANKTRANSFER = 'online_bank_transfer';
    const WALLET = 'wallet';
    const BARZAHLEN = 'barzahlen';
    const RATEPAY = 'ratepay';
    const RATEPAYDIRECTDEBIT = 'ratepay_direct_debit';
    const PAYOLUTION = 'payolution';
    const PAYOLUTIONINVOICING = 'payolution_invoicing';
    const PAYOLUTIONDEBIT = 'payolution_debit';
    const PAYOLUTIONINSTALLMENT = 'payolution_installment';
    const PAYMENTGUARANTEEINVOICE = 'payment_guarantee_invoice';
    const WALLETPAYDIREKT = 'wallet_paydirekt';
    const WALLETPAYDIREKTEXPRESS = 'wallet_paydirekt_express';
    const WALLETPAYPALEXPRESS = 'wallet_paypal_express';
    const WALLETALIPAY = 'wallet_alipay';
    const MASTERPASS =  'masterpass';


    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            self::ADVANCEPAYMENT => 'Advance Payment',
            self::AMAZONPAY => 'Amazon Pay',
            self::CASHONDELIVERY => 'Cash on Delivery',
            self::CREDITCARD => 'Creditcard',
            //self::CREDITCARD_IFRAME => 'Creditcard Channel Frontend', // MAGE-392: Deprecated, method is removed
            self::DEBITPAYMENT => 'Debit Payment',
            self::SAFEINVOICE => 'Safe Invoice',
            self::INVOICE => 'Invoice',
            //self::ONLINEBANKTRANSFER => 'Online Bank Transfer',
            //self::WALLET => 'Wallet',
            self::BARZAHLEN => 'Barzahlen',
            self::RATEPAY => 'RatePay',
            self::RATEPAYDIRECTDEBIT => 'Ratepay Direct Debit',
            // self::PAYOLUTION => 'Payolution',
            self::PAYOLUTIONINVOICING => 'Paysafe Pay Later™ Invoicing',
            self::PAYOLUTIONDEBIT => 'Paysafe Pay Later™ Debit',
            self::PAYOLUTIONINSTALLMENT => 'Paysafe Pay Later™ Installment',
            self::PAYMENTGUARANTEEINVOICE => 'Invoice with Payment Guarantee',
            self::WALLETPAYDIREKT => 'Paydirekt',
            self::WALLETPAYDIREKTEXPRESS => 'Paydirekt Express',
            self::WALLETPAYPALEXPRESS => 'Paypal Express',
            self::WALLETALIPAY => 'AliPay',
            self::ONLINEBANKTRANSFERSOFORT => 'Sofortueberweisung',
            self::ONLINEBANKTRANSFERGIROPAY => 'Giropay',
            self::ONLINEBANKTRANSFEREPS => 'eps Online Ueberweisung',
            self::ONLINEBANKTRANSFERIDL => 'Ideal',
            self::ONLINEBANKTRANSFERPFF => 'PostFinance E-Finance',
            self::ONLINEBANKTRANSFERPFC => 'PostFinance Card',
            self::ONLINEBANKTRANSFERP24 => 'Przelewy24',
            self::ONLINEBANKTRANSFERBCT => 'Bancontact',
            self::MASTERPASS => 'Masterpass'
        );
    }
}
