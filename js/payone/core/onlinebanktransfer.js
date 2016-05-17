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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @param element
 * @param country
 * @param currency
 */
function payoneSwitchOnlineBankTransfer(element, country, currency) {
    if (element == undefined) {
        return;
    }
    var ElementValue = element.value;
    var ElementValueSplit = ElementValue.split('_');
    var typeId = ElementValueSplit[0];
    var typeCode = ElementValueSplit[1];

    $("payone_online_bank_transfer_obt_type").setValue(typeCode);
    $("payone_online_bank_transfer_config_id").setValue(typeId);

    var accountNumberWrap = $('account_number_wrap');
    var bankCodeWrap = $('bank_code_wrap');
    var sepaIbanWrap = $('sepa_iban_wrap');
    var sepaBicWrap = $('sepa_bic_wrap');
    var bankGroupWrapAt = $('bank_group_wrap_at');
    var bankGroupWrapNl = $('bank_group_wrap_nl');

    var accountNumberInput = $('payone_online_bank_transfer_account_number');
    var bankCodeInput = $('payone_online_bank_transfer_bank_code');
    var sepaIbanInput = $('payone_online_bank_transfer_sepa_iban');
    var sepaBicInput = $('payone_online_bank_transfer_sepa_bic');
    var bankGroupSelectAt = $('payone_online_bank_transfer_bank_group_at');
    var bankGroupSelectNl = $('payone_online_bank_transfer_bank_group_nl');

    if (ElementValue == '' || typeCode == 'PFF' || typeCode == 'PFC' || typeCode == 'P24') {
        disableAll();
    } else if (typeCode == 'PNT') {
        disableAll();
        if (country == 'CH' && currency == 'CHF') {
            enableAccountNumber();
            enableBankCode();
        } else {
            enableSepaIban();
            enableSepaBic();
        }
    } else if (typeCode == 'GPY') {
        disableAll();
        enableSepaIban();
        enableSepaBic();
    } else if (typeCode == 'EPS') {
        disableAll();
        enableBankGroupAt();
    } else if (typeCode == 'IDL') {
        disableAll();
        enableBankGroupNl();
    }

    function disableAll() {
        if(accountNumberWrap) {
            accountNumberWrap.hide();
            accountNumberInput.setAttribute("disabled", "disabled");
        }
        if(bankCodeWrap) {
            bankCodeWrap.hide();
            bankCodeInput.setAttribute("disabled", "disabled");
        }
        if(sepaIbanWrap) {
            sepaIbanWrap.hide();
            sepaIbanInput.setAttribute("disabled", "disabled");
        }
        if(sepaBicWrap) {
            sepaBicWrap.hide();
            sepaBicInput.setAttribute("disabled", "disabled");
        }
        if(bankGroupWrapAt) {
            bankGroupWrapAt.hide();
            bankGroupSelectAt.setAttribute("disabled", "disabled");
        }
        if(bankGroupWrapNl) {
            bankGroupWrapNl.hide();
            bankGroupSelectNl.setAttribute("disabled", "disabled");
        }
    }

    function enableAccountNumber() {
        if(accountNumberWrap) {
            accountNumberWrap.show();
            accountNumberInput.removeAttribute("disabled");
        }
    }

    function enableBankCode() {
        if(bankCodeWrap) {
            bankCodeWrap.show();
            bankCodeInput.removeAttribute("disabled");
        }
    }

    function enableSepaIban() {
        if(sepaIbanWrap) {
            sepaIbanWrap.show();
            sepaIbanInput.removeAttribute("disabled");
        }
    }

    function enableSepaBic() {
        if(sepaBicWrap) {
            sepaBicWrap.show();
            sepaBicInput.removeAttribute("disabled");
        }
    }

    function enableBankGroupAt() {
        if(bankGroupWrapAt) {
            bankGroupWrapAt.show();
            bankGroupSelectAt.removeAttribute("disabled");
        }
    }

    function enableBankGroupNl() {
        if(bankGroupWrapNl) {
            bankGroupWrapNl.show();
            bankGroupSelectNl.removeAttribute("disabled");
        }
    }
}

function copyOnlineBankTransferSepaIban(code) {
    var input_sepa_iban_xxx_el = $(code + '_sepa_iban_xxx');
    var input_sepa_iban_el = $(code + '_sepa_iban');
    input_sepa_iban_el.value = input_sepa_iban_xxx_el.value;
}
