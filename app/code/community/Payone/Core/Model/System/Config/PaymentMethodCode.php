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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com,  Copyright (c) 2017 <support@e3n.de> - www.e3n.de
 * @author          Matthias Walter <info@noovias.com>, Tim Rein <tim.rein@e3n.de>
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
class Payone_Core_Model_System_Config_PaymentMethodCode extends Payone_Core_Model_System_Config_Abstract
{
    const PREFIX = 'payone_';

    const ADVANCEPAYMENT = 'payone_advance_payment';
    const AMAZONPAY = 'payone_amazon_pay';
    const CASHONDELIVERY = 'payone_cash_on_delivery';
    const CREDITCARD = 'payone_creditcard';
    //const CREDITCARD_IFRAME = 'payone_creditcard_iframe'; // MAGE-392: Deprecated, method is removed
    const DEBITPAYMENT = 'payone_debit_payment';
    const INVOICE = 'payone_invoice';
    const SAFEINVOICE = 'payone_safe_invoice';
    const ONLINEBANKTRANSFERBCT = 'payone_online_bank_transfer_bct';
    const ONLINEBANKTRANSFERGIROPAY = 'payone_online_bank_transfer_giropay';
    const ONLINEBANKTRANSFERP24 = 'payone_online_bank_transfer_p24';
    const ONLINEBANKTRANSFEREPS = 'payone_online_bank_transfer_eps';
    const ONLINEBANKTRANSFERPFF = 'payone_online_bank_transfer_pff';
    const ONLINEBANKTRANSFERPFC = 'payone_online_bank_transfer_pfc';
    const ONLINEBANKTRANSFERIDL = 'payone_online_bank_transfer_idl';
    const ONLINEBANKTRANSFERSOFORT = 'payone_online_bank_transfer_sofortueberweisung';
    const ONLINEBANKTRANSFER = 'payone_online_bank_transfer';
    const WALLET = 'payone_wallet';
    const BARZAHLEN = 'payone_barzahlen';
    const RATEPAY = 'payone_ratepay';
    const RATEPAYINVOICING = 'payone_ratepay_invoicing';
    const RATEPAYDIRECTDEBIT = 'payone_ratepay_direct_debit';
    const PAYOLUTION = 'payone_payolution';
    const PAYOLUTIONINVOICING = 'payone_payolution_invoicing';
    const PAYOLUTIONDEBIT = 'payone_payolution_debit';
    const PAYOLUTIONINSTALLMENT = 'payone_payolution_installment';
    const PAYMENTGUARANTEEINVOICE = 'payone_payment_guarantee_invoice';
    const WALLETPAYDIREKT = 'payone_wallet_paydirekt';
    const WALLETPAYDIREKTEXPRESS = 'payone_wallet_paydirekt_express';
    const WALLETPAYPALEXPRESS = 'payone_wallet_paypal_express';
    const WALLETALIPAY = 'payone_wallet_alipay';
    const KLARNABASE = 'payone_klarna_base';
    const KLARNAINVOICING = 'payone_klarna_invoicing';
    const KLARNAINSTALLMENT = 'payone_klarna_installment';
    const KLARNADIRECTDEBIT = 'payone_klarna_direct_debit';
    const ONLINEBANKTRANSFERTRUSTLY = 'payone_online_bank_transfer_trustly';
//    const MASTERPASS =  'payone_masterpass'; // MAGE-457 : method removed

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
            self::INVOICE => 'Invoice',
            self::SAFEINVOICE => 'Safe Invoice',
            self::ONLINEBANKTRANSFER => 'Online Bank Transfer',
            self::WALLET => 'Wallet',
            self::BARZAHLEN => 'Barzahlen',
            self::RATEPAY => 'RatePay Installments',
            self::RATEPAYINVOICING => 'RatePay Invoicing',
            self::RATEPAYDIRECTDEBIT => 'Ratepay Direct Debit',
            //self::PAYOLUTION => 'Payolution',
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
            self::KLARNABASE => 'Klarna',
            self::KLARNAINVOICING => 'Klarna "Pay Later" (Invoice)',
            self::KLARNAINSTALLMENT => 'Klarna "Slice It" (Installments)',
            self::KLARNADIRECTDEBIT => 'Klarna "Pay Now" (Direct Debit)',
            self::ONLINEBANKTRANSFERTRUSTLY => 'Trustly',
//            self::MASTERPASS => 'Masterpass' // MAGE-457 : method removed
        );
    }
}
