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
 * @package         js
 * @subpackage      payone
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com, Copyright (c) 2017 <info@e3n.de> - www.e3n.de
 * @author          Matthias Walter <info@noovias.com>, Tim Rein <web.it.rein@gmail.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com, http://www.e3n.de
 */

/**
 *
 * @param typeCode
 * @param methodCode
 * @param element
 * @param country
 * @param currency
 */
function payoneSwitchOnlineBankTransfer(typeCode, methodCode, element, country, currency) {

    var accountNumberWrap = $('account_number_wrap');
    var bankCodeWrap = $('bank_code_wrap');
    var sepaIbanWrap = $('sepa_iban_wrap');
    var sepaBicWrap = $('sepa_bic_wrap');
    var bankGroupWrapAt = $('bank_group_wrap_at');
    var bankGroupWrapNl = $('bank_group_wrap_nl');
    var accountNumberInput = $(methodCode + '_account_number');
    var bankCodeInput = $(methodCode + '_bank_code');
    var sepaIbanInput = $(methodCode + '_sepa_iban');
    var sepaBicInput = $(methodCode + '_sepa_bic');
    var bankGroupSelectAt = $(methodCode + '_bank_group_at');
    var bankGroupSelectNl = $(methodCode + '_bank_group_nl');
    var sofortueberweisungShowIban = $(methodCode + '_pnt_show_iban');
    //
    var epsPaymentMethodContainer =  $("dt_method_payone_online_bank_transfer_eps") || $('p_method_payone_online_bank_transfer_eps');
    var idlPaymentMethodContainer =  $("dt_method_payone_online_bank_transfer_idl") || $('p_method_payone_online_bank_transfer_idl');
    var giropayPaymentMethodContainer =  $("dt_method_payone_online_bank_transfer_giropay") || $('p_method_payone_online_bank_transfer_giropay');
    var pffPaymentMethodContainer =  $("dt_method_payone_online_bank_transfer_pff") || $('p_method_payone_online_bank_transfer_pff');
    var sofortPaymentMethodContainer =  $("dt_method_payone_online_bank_transfer_sofortueberweisung") || $('p_method_payone_online_bank_transfer_sofortueberweisung');



    function enableBankGroupNl() {
        if (bankGroupWrapNl) {
            bankGroupWrapNl.show();
            bankGroupSelectNl.removeAttribute("disabled");
        }
    }

    function enableBankGroupAt() {
        if (bankGroupWrapAt) {
            bankGroupWrapAt.show();
            bankGroupSelectAt.removeAttribute("disabled");
        }
    }

    function enableAccountNumber() {
        if (accountNumberWrap) {
            accountNumberWrap.show();
            accountNumberInput.removeAttribute("disabled");
        }
    }

    function enableBankCode() {
        if (bankCodeWrap) {
            bankCodeWrap.show();
            bankCodeInput.removeAttribute("disabled");
        }
    }

    function enableSepaIban() {

        if (sepaIbanWrap) {
            sepaIbanWrap.show();
            sepaIbanInput.removeAttribute("disabled");
        }
    }

    function enableSepaBic() {

        if (sepaBicWrap) {
            sepaBicWrap.show();
            sepaBicInput.removeAttribute("disabled");
        }
    }

    if (typeCode == 'EPS') {
        if(epsPaymentMethodContainer) {
            epsPaymentMethodContainer.on("click", function (event) {
                disableAll();
                enableBankGroupAt();
            });
        }
    }
    if (typeCode == 'IDL') {
        if (idlPaymentMethodContainer) {
            idlPaymentMethodContainer.on("click", function (event) {
                disableAll();
                enableBankGroupNl();
            });
        }
    }
    if (typeCode == 'GPY') {
        if (giropayPaymentMethodContainer) {
            giropayPaymentMethodContainer.on("click", function (event) {
                disableAll();
                enableSepaIban();
                enableSepaBic();
            });
        }
    }


    if (typeCode == 'PFF') {
        if(pffPaymentMethodContainer){
            pffPaymentMethodContainer.on("click", function (event) {
                disableAll();
            });
        }
    }

    if (typeCode == 'PNT') {
        if(sofortPaymentMethodContainer){
            sofortPaymentMethodContainer.on("click", function (event) {
                disableAll();
                if (sofortueberweisungShowIban.value == 1) {
                    enableSepaIban();
                    enableSepaBic();
                }

                if (country == 'CH' && currency == 'CHF') {
                    enableAccountNumber();
                    enableBankCode();
                }
            });
        }
    }

    if (typeCode == 'PFC' || typeCode == 'P24') {
        disableAll();
    }

    function disableAll() {
        if (accountNumberWrap && accountNumberInput) {
            accountNumberWrap.hide();
            accountNumberInput.setAttribute("disabled", "disabled");
        }

        if (bankCodeWrap && bankCodeInput) {
            bankCodeWrap.hide();
            bankCodeInput.setAttribute("disabled", "disabled");
        }

        if (sepaIbanWrap && sepaIbanInput) {
            sepaIbanWrap.hide();
            sepaIbanInput.setAttribute("disabled", "disabled");
        }

        if (sepaBicWrap && sepaBicInput) {
            sepaBicWrap.hide();
            sepaBicInput.setAttribute("disabled", "disabled");
        }

        if (bankGroupWrapNl && bankGroupSelectNl) {
            bankGroupWrapNl.hide();
            bankGroupSelectNl.setAttribute("disabled", "disabled");
        }
    }

}

function copyOnlineBankTransferSepaIban(code) {
    var input_sepa_iban_xxx_el = $(code + '_sepa_iban_xxx');
    var input_sepa_iban_el = $(code + '_sepa_iban');
    input_sepa_iban_el.value = input_sepa_iban_xxx_el.value;
}
