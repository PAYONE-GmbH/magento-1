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
    const CASHONDELIVERY = 'payone_cash_on_delivery';
    const CREDITCARD = 'payone_creditcard';
    const CREDITCARD_IFRAME = 'payone_creditcard_iframe';
    const DEBITPAYMENT = 'payone_debit_payment';
    const INVOICE = 'payone_invoice';
    const SAFEINVOICE = 'payone_safe_invoice';
    const ONLINEBANKTRANSFER = 'payone_online_bank_transfer';
    const WALLET = 'payone_wallet';
    const BARZAHLEN = 'payone_barzahlen';
    const RATEPAY = 'payone_ratepay';
    #const PAYOLUTION = 'payone_payolution';
    const PAYOLUTIONINVOICING = 'payone_payolution_invoicing';
    const PAYOLUTIONDEBIT = 'payone_payolution_debit';
    const PAYOLUTIONINSTALLMENT = 'payone_payolution_installment';

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            self::ADVANCEPAYMENT => 'Advance Payment',
            self::CASHONDELIVERY => 'Cash on Delivery',
            self::CREDITCARD => 'Creditcard',
            self::CREDITCARD_IFRAME => 'Creditcard Channel Frontend',
            self::DEBITPAYMENT => 'Debit Payment',
            self::INVOICE => 'Invoice',
            self::SAFEINVOICE => 'Safe Invoice',
            self::ONLINEBANKTRANSFER => 'Online Bank Transfer',
            self::WALLET => 'Wallet',
            self::BARZAHLEN => 'Barzahlen',
            self::RATEPAY => 'RatePay',
            #self::PAYOLUTION => 'Payolution',
            self::PAYOLUTIONINVOICING => 'Payolution Invoicing',
            self::PAYOLUTIONDEBIT => 'Payolution Debit',
            self::PAYOLUTIONINSTALLMENT => 'Payolution Installment'
        );
    }
}
