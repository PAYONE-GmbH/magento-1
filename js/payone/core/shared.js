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
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

/**
 * @param code
 */
function payoneCustomerDobInput(code)
{
    var daySelect = $(code + '_additional_fields_customer_dob_day');
    var monthSelect = $(code + '_additional_fields_customer_dob_month');
    var yearSelect = $(code + '_additional_fields_customer_dob_year');
    var hiddenDobFull = $(code + '_additional_fields_customer_dob_full');

    if (daySelect == undefined || monthSelect == undefined || yearSelect == undefined
        || hiddenDobFull == undefined)  {
        return;
    }

    hiddenDobFull.value = yearSelect.value + "-" + monthSelect.value + "-" + daySelect.value;
}

/**
 * @param code
 */
function copyDebitPaymentSepaIban(code)
{
    var input_sepa_iban_xxx_el = $(code + '_sepa_iban_xxx');
    var input_sepa_iban_el = $(code + '_sepa_iban');
    input_sepa_iban_el.value = input_sepa_iban_xxx_el.value;
}

/**
 *
 * @param code
 * @param configShowBankData
 */
function blockPaymentMethodInputs(code, configShowBankData)
{
    var input_sepa_iban_el = $(code + '_sepa_iban');
    var input_sepa_bic_el = $(code + '_sepa_bic');
    var input_account_number_el = $(code + '_account_number');
    var input_bank_code_el = $(code + '_bank_code');

    if (input_sepa_iban_el.value != '' && Validation.get('validate-sepa-iban').test(input_sepa_iban_el.value) == true) {
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
        if(input_sepa_bic_el) {
            disableElement(input_sepa_bic_el);
            $('input_box_payone_sepa_bic').hide();
        }
    } else {
        enableElement(input_sepa_iban_el);
        $('input_box_payone_sepa_iban').show();
        if(input_sepa_bic_el) {
            enableElement(input_sepa_bic_el);
            $('input_box_payone_sepa_bic').show();
        }
    }
}

/**
 * @param code
 * @param configShowBankData
 */
function showBankData(code, configShowBankData)
{
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
 * @param checkboxEl
 */
function changeSubmitButtonStatus(checkboxEl)
{
    if (checkboxEl.checked) {
        $$('.btn-checkout')[0].removeAttribute("disabled");
        $$('.btn-checkout')[0].show();
    } else {
        $$('.btn-checkout')[0].setAttribute("disabled", "disabled");
        $$('.btn-checkout')[0].hide();
    }
}

/**
 * @param elem
 */
function enableElement(elem) {
    if (elem == undefined) {
        return;
    }

    elem.disabled = false;
    elem.removeClassName('disabled');
}