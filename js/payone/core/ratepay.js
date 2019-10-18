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
 * @copyright       Copyright (c) 2016 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 *
 *
 * @category        Payone
 * @package         js
 * @subpackage      payone
 * @copyright       Copyright (c) 2016 <support@e3n.de> - www.e3n.de
 * @author          Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.e3n.de
 */

var Translator = new Translate([]);

Validation.add(
    'validate-18-years', Translator.translate('You have to be at least 18 years old to use this payment type!'), function (value) {
        var oBirthDate = new Date(value);
        var oMinDate = new Date(new Date().setYear(new Date().getFullYear() - 18));
        if(oBirthDate > oMinDate) {
            return false;
        }

        return true;
    }
);

var billingAddressSelector = $('order-billing_address_customer_address_id');
if (undefined !== typeof billingAddressSelector && billingAddressSelector !== null) {
    billingAddressSelector.on('change', function() {
        updateRatePaymentMethod('<?php echo $code; ?>', '<?php echo $configDebitCountries; ?>');
        checkRequirementFields('<?php echo $paymentType;?>', -1);
    });
}

var billingCountrySelector = $('order-billing_address_country_id');
if (undefined !== typeof billingCountrySelector && billingCountrySelector !== null) {
    billingCountrySelector.on('change', function() {
        updateRatePaymentMethod('<?php echo $code; ?>', '<?php echo $configDebitCountries; ?>');
    });
}

/**
 *
 * @param mode
 * @param paymentMethod
 * @param url
 * @param calcValue
 */
function payoneRatepayRateCalculatorAction (mode, paymentMethod, url, calcValue)
{
    var calcMethod,
        notification,
        html,
        ratePayshopId,
        amount,
        ratePayCurrency,
        ajaxLoader = $("ajaxLoaderId"),
        cover = $("cover"),
        calculationFlag = $("calculationValidationFlag");

    // MAGE-444 : set the flag down before calculation
    // so it's possible to check if the calculation happened
    // and if it was successful
    calculationFlag.value = "";

    ajaxLoader.setStyle(
        {
            display: 'block'
        }
    );
    cover.setStyle(
        {
            display: 'block'
        }
    );


    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    amount = document.getElementById('amount').value;
    ratePayshopId = document.getElementById('ratePayShopId').value;
    ratePayCurrency = document.getElementById('ratePayCurrency').value;
    if (mode == 'rate') {
        calcValue = document.getElementById(paymentMethod + '-rate').value;
        calcMethod = 'calculation-by-rate';
        if (document.getElementById('debitSelect')) {
            dueDate = document.getElementById('debitSelect').value;
        } else {
            dueDate= '';
        }
    } else if (mode == 'runtime') {
        calcMethod = 'calculation-by-time';
        notification = (document.getElementById(paymentMethod + '_Notification') == null) ? 0 : 1;
        if(document.getElementById('debitSelectRuntime')){
            dueDate = document.getElementById('debitSelectRuntime').value;
        } else {
            dueDate= '';
        }
    }

    xmlhttp.open("POST", url, false);

    xmlhttp.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    var parameters = "paymentMethod=" + paymentMethod + "&calcValue=" + calcValue + "&calcMethod=" + calcMethod + "&dueDate=" + dueDate
        + "&notification=" + notification
        + "&ratePayshopId=" + ratePayshopId + "&ratePayCurrency=" + ratePayCurrency + "&amount=" + amount

    if (document.getElementById('isAdminOrder') && document.getElementById('quoteId')) {
        var isAdmin = document.getElementById('isAdminOrder').value;
        var quoteId = document.getElementById('quoteId').value;
        parameters += "&isAdmin=" + isAdmin + "&quoteId=" + quoteId
    }

    xmlhttp.send(parameters);

    if (xmlhttp.responseText != null) {
        html = xmlhttp.responseText;
        document.getElementById(paymentMethod + '_ResultContainer').innerHTML = html;
        document.getElementById(paymentMethod + '_ResultContainer').style.display = 'block';
        document.getElementById(paymentMethod + '_ResultContainer').style.padding = '3px 0 0 0';

        // MAGE-444 : if calculation succeeded, the validation is raised
        if (html.search('.*rateError.*') === -1) {
            calculationFlag.value = "1";
            var validationAdvice = $('advice-required-entry-calculationValidationFlag');
            if ('undefined' !== typeof validationAdvice && validationAdvice !== null ) {
                validationAdvice.hide();
            }

        }

        ajaxLoader.setStyle(
            {
                display: 'none'
            }
        );
        cover.setStyle(
            {
                display: 'none'
            }
        );
    }
}

function attachCalcButtonsListeners(code, urlRuntime, urlRate)
{
    $$('.' + code + '-btn-runtime').each(
        function(v) {
            v.on("click", function() { triggerRuntimeAction(v, code, urlRuntime); });
        }
    );

    var rateBtn = $(code + '-btn-rate');
    rateBtn.on("click", function() { triggerRateAction(rateBtn, code, urlRate); });
}

function triggerRuntimeAction(element, code, urlRuntime)
{
    $$('.' + code + '-btn-runtime').each( function(btn) { btn.removeClassName('btn-info') });
    $(code + '-btn-rate').removeClassName('btn-info');
    $(code + '-rate').value = "";
    element.addClassName('btn-info');

    payoneRatepayRateCalculatorAction('runtime', code, urlRuntime, element.dataset.bind);
}

function triggerRateAction(element, code, urlRate)
{
    $$('.' + code + '-btn-runtime').each(function(btn) { btn.removeClassName('btn-info') });
    element.addClassName('btn-info');

    payoneRatepayRateCalculatorAction('rate', code, urlRate);
}

/**
 *
 * @param payment_code
 */
function payoneRatepayCustomerDobInput(payment_code)
{
    var daySelect = $(payment_code + '_additional_fields_customer_dob_day');
    var monthSelect = $(payment_code + '_additional_fields_customer_dob_month');
    var yearSelect = $(payment_code + '_additional_fields_customer_dob_year');
    var hiddenDobFull = $(payment_code + '_additional_fields_customer_dob_full');

    if (daySelect == undefined || monthSelect == undefined || yearSelect == undefined
        || hiddenDobFull == undefined)  {
        return;
    }

    hiddenDobFull.value = yearSelect.value + "-" + monthSelect.value + "-" + daySelect.value;
}

/**
 *
 * @param code
 * @param allowedCountryCodesList
 */
function checkIbanSEPACode(code, allowedCountryCodesList)
{
    var ibanEl = $(code + '_sepa_iban');
    if (!ibanEl || typeof ibanEl === 'undefined') {
        return;
    }

    var value = ibanEl.value;
    if (value.length < 2) {
        return;
    }

    var allowedCountryCodes = JSON.parse(allowedCountryCodesList.toUpperCase());
    var countryCode = value.substring(0, 2).toUpperCase();
    var validationAdvice = $("advice-validate-sepa-iban-countrycode");
    if (allowedCountryCodes.indexOf(countryCode) === -1) {
        ibanEl.value = "";
        ibanEl.addClassName("validation-failed");
        if (!validationAdvice || typeof validationAdvice === 'undefined') {
            var valText = Translator.translate("Entered IBAN is not from an authorised SEPA country.");
            ibanEl.insert(
                {
                    after: '<div class="validation-advice" id="advice-validate-sepa-iban-countrycode">' + valText + '</div>'
                }
            );
        }
    } else {
        ibanEl.removeClassName('validation-failed');
        if (validationAdvice && typeof validationAdvice !== 'undefined') {
            validationAdvice.remove();
        }
    }
}

function toggleRatepayDirectDebitOverlay(sCode)
{
    var element = document.getElementById(sCode + '_overlay');
    element.toggle();
}

function toggleBicField(ibanEl, sCode)
{
    var countryCode = ibanEl.value.substring(0,2);
    var bicEl = $(sCode + '_section_sepa_bic');

    if (countryCode === 'DE') {
        bicEl.hide()
    }
    else {
        bicEl.show()
    }
}

function requireRegistrationNumber(required, fieldPrefix)
{
    var fieldId = fieldPrefix + "_trade_registry_number";
    var registrationNumber = $(fieldId);

    if (null !== registrationNumber && 'undefined' !== registrationNumber) {
        var label = $$('label[for=' + fieldId + ']')[0];
        var labelRequiredMark = label.getElementsBySelector('span')[0];

        if (required) {
            labelRequiredMark.show();
            registrationNumber.addClassName('required-entry')
        }
        else {
            labelRequiredMark.hide();
            registrationNumber.removeClassName('required-entry')
        }
    }
}

function requireVat(required, fieldPrefix)
{
    var fieldId = fieldPrefix + "_vat_id";
    var vat = $(fieldId);

    if (null !== vat && 'undefined' !== vat) {
        var label = $$('label[for=' + fieldId + ']')[0];
        var labelRequiredMark = label.getElementsBySelector('span')[0];

        if (required) {
            labelRequiredMark.show();
            vat.addClassName('required-entry')
        }
        else {
            labelRequiredMark.hide();
            vat.removeClassName('required-entry')
        }
    }
}

function checkRequirementFields(method, forceRequirement)
{
    if (forceRequirement !== -1) {
        var fieldPrefix = 'payone_ratepay';
        if (method === 'RPD') {
            fieldPrefix += '_direct_debit';
        }
        if (method === 'RPV') {
            fieldPrefix += '_invoicing';
        }
        requireRegistrationNumber(forceRequirement, fieldPrefix);
        requireVat(forceRequirement, fieldPrefix);
        return;
    }
    var b2b = document.getElementsByName('payment[payone_isb2b]');
    forceRequirement = b2b.length>0 && (b2b.item(0).value !== '1');
    checkRequirementFields(method, forceRequirement);
}

function showInstallmentDetails()
{
    var target = $('ratepay-show-installment-plan-details');
    target.hide();
    $$('.ratepay-installment-plan-details').each(
        function(el) {
            el.show();
        }
    );
    $('ratepay-hide-installment-plan-details').show();
}

function hideInstallmentDetails()
{
    var target = $('ratepay-hide-installment-plan-details');
    target.hide();
    $$('.ratepay-installment-plan-details').each(
        function(el) {
            el.hide();
        }
    );
    $('ratepay-show-installment-plan-details').show();
}

function copyDebitPaymentSepaIban(code)
{
    var input_sepa_iban_xxx_el = $(code + '_sepa_iban_xxx');
    var input_sepa_iban_el = $(code + '_sepa_iban');
    input_sepa_iban_el.value = input_sepa_iban_xxx_el.value;
}

function updateRatePaymentMethod(code, allowedDebitCountries) {
    if ('undefined' !== typeof allowedDebitCountries && allowedDebitCountries !== null) {
        var country = $('order-billing_address_country_id').value.toUpperCase();
        var displaySwitchSection = true;
        var rateMethod = 'DIRECT-DEBIT';

        if (country === 'CH') {
            rateMethod = 'BANK-TRANSFER';
            displaySwitchSection = false;
        } else {
            if (allowedDebitCountries !== 'all') {
                if (allowedDebitCountries.indexOf(country) === -1) {
                    rateMethod = 'BANK-TRANSFER';
                    displaySwitchSection = false;
                }
            }
        }

        switchRateMethodTo(rateMethod, code, displaySwitchSection);
    }
}

function switchRatePaymentMethod(code)
{
    var currentMethod = $(code + '_debit_type').value;
    currentMethod === 'DIRECT-DEBIT'
        ? switchRateMethodTo('BANK-TRANSFER', code, true)
        : switchRateMethodTo('DIRECT-DEBIT', code, true);
}

function switchRateMethodTo(method, code, displaySwitchSection)
{
    var debitTypeMethod = $(code + '_debit_type');
    if ('undefined' !== typeof debitTypeMethod && debitTypeMethod !== null) {
        var switchSection = $('method-switch-section');
        if ('undefined' !== typeof switchSection && switchSection !== null) {
            displaySwitchSection ? switchSection.show() : switchSection.hide();
        }

        $(code + '_debit_type').value = method;

        var methodSwitchCheckbox = $('method-switch-checkbox');
        if (method === 'DIRECT-DEBIT') {
            $(code + '_debit_details').show();
            $(code + '_sepa_iban_xxx').addClassName('required-entry');
            methodSwitchCheckbox.style.backgroundColor = '';
        } else {
            $(code + '_debit_details').hide();
            $(code + '_sepa_iban_xxx').removeClassName('required-entry');
            methodSwitchCheckbox.style.backgroundColor = methodSwitchCheckbox.getStyles().borderBottomColor;
        }
    }
}