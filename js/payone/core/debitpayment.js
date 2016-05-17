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

function showBankData(code, configShowBankData) {
    var bankCountry = $(code + '_bank_country').getValue();
    if (configShowBankData && configShowBankData == 1) {
        if (bankCountry == "DE") {
            $('input_box_payone_account_number').show();
            $('input_box_payone_bank_code').show();
        } else {
            $('input_box_payone_account_number').hide();
            $('input_box_payone_bank_code').hide();
        }
    }
}

/**
 *
 * @param code
 */
function checkIbanCountryCode(code) {
    var ibanEl = $(code + '_sepa_iban');
    if (!ibanEl || typeof ibanEl === 'undefined') {
        return;
    }
    var bankCountryEl = $(code + '_bank_country');
    if (!bankCountryEl || typeof bankCountryEl === 'undefined') {
        return;
    }
    var bankCountryCode = bankCountryEl.value;
    var value = ibanEl.value;
    if (value.length < 2) {
        return;
    }
    var countryCode = value.substring(0, 2).toUpperCase();
    var validationAdvice = $("advice-validate-sepa-iban-countrycode");
    if (countryCode != bankCountryCode) {
        ibanEl.value = "";
        ibanEl.addClassName("validation-failed");
        if (!validationAdvice || typeof validationAdvice === 'undefined') {
            var valText = Translator.translate("Entered IBAN is not valid for selected bank country");
            ibanEl.insert({
                after: '<div class="validation-advice" id="advice-validate-sepa-iban-countrycode">' + valText + '</div>'
            });
        }
    } else {
        ibanEl.removeClassName('validation-failed');
        if (validationAdvice && typeof validationAdvice !== 'undefined') {
            validationAdvice.remove();
        }
    }
}

function disableElement(element) {
    if (element == undefined) {
        return;
    }
    element.value = '';
    element.disabled = true;
    element.removeClassName('required-entry');
    element.removeClassName('validation-failed');
    var validationHint = element.next('div .validation-advice');
    if (typeof validationHint !== 'undefined') {
        validationHint.remove();
    }
}

function enableElement(element) {
    if (element == undefined) {
        return;
    }
    element.disabled = false;
    element.toggleClassName('require-entry');
}

function copyDebitPaymentSepaIban(code) {
    var input_sepa_iban_xxx_el = $(code + '_sepa_iban_xxx');
    var input_sepa_iban_el = $(code + '_sepa_iban');
    input_sepa_iban_el.value = input_sepa_iban_xxx_el.value;
}

function blockPaymentMethodInputs(code, configShowBankData) {
    var input_sepa_iban_el = $(code + '_sepa_iban');
    var input_sepa_bic_el = $(code + '_sepa_bic');
    var input_account_number_el = $(code + '_account_number');
    var input_bank_code_el = $(code + '_bank_code');

    if (input_sepa_iban_el.value != ''
        && Validation.get('validate-sepa-iban').test(input_sepa_iban_el.value) == true
        && input_sepa_bic_el.value != ''
        && Validation.get('validate-sepa-bic').test(input_sepa_bic_el.value) == true) {
        disableElement(input_account_number_el);
        var inputboxpayoneaccountnumber = $('input_box_payone_account_number');
        if (inputboxpayoneaccountnumber != undefined) {
            inputboxpayoneaccountnumber.hide();
        }
        disableElement(input_bank_code_el);
        var inputboxpayonebankcode = $('input_box_payone_bank_code');
        if (inputboxpayonebankcode != undefined) {
            inputboxpayonebankcode.hide();
        }
    } else {
        enableElement(input_account_number_el);
        enableElement(input_bank_code_el);
        showBankData(code, configShowBankData);
    }

    if (input_account_number_el != undefined
        && input_account_number_el.value != ''
        && Validation.get('validate-digits').test(input_account_number_el.value) == true
        && input_bank_code_el.value != ''
        && Validation.get('validate-bank-code').test(input_bank_code_el.value) == true
        && Validation.get('validate-digits').test(input_bank_code_el.value) == true) {
        disableElement(input_sepa_iban_el);
        $('input_box_payone_sepa_iban').hide();
        disableElement(input_sepa_bic_el);
        $('input_box_payone_sepa_bic').hide();
    } else {
        enableElement(input_sepa_iban_el);
        $('input_box_payone_sepa_iban').show();
        enableElement(input_sepa_bic_el);
        $('input_box_payone_sepa_bic').show();
    }
}

/**
 *
 * @param checkboxEl
 */
function changeSubmitButtonStatus(checkboxEl) {
    if (checkboxEl.checked) {
        $$('.btn-checkout')[0].removeAttribute("disabled");
        $$('.btn-checkout')[0].show();
    } else {
        $$('.btn-checkout')[0].setAttribute("disabled", "disabled");
        $$('.btn-checkout')[0].hide();
    }
}