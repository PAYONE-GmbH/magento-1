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

/**
 *
 * @param mode
 * @param paymentMethod
 * @param url
 */
function payoneSwitchRateOrRuntime(mode, paymentMethod, url)
{
    if (mode == 'rate') {
        document.getElementById(paymentMethod + '_SwitchToTerm').className = 'ratepay-Active';
        document.getElementById(paymentMethod + '_SwitchToRuntime').className = '';
        document.getElementById(paymentMethod + '_ContentTerm').style.display = 'block';
        document.getElementById(paymentMethod + '_ContentRuntime').style.display = 'none';
    } else if (mode == 'runtime') {
        document.getElementById(paymentMethod + '_SwitchToRuntime').className = 'ratepay-Active';
        document.getElementById(paymentMethod + '_SwitchToTerm').className = '';
        document.getElementById(paymentMethod + '_ContentRuntime').style.display = 'block';
        document.getElementById(paymentMethod + '_ContentTerm').style.display = 'none';
    }
}

/**
 *
 * @param mode
 * @param paymentMethod
 * @param url
 */
function payoneRatepayRateCalculatorAction(mode, paymentMethod, url)
{
    var calcValue,
        calcMethod,
        notification,
        html,
        ratePayshopId,
        amount,
        ratePayCurrency,
        ajaxLoader = $("ajaxLoaderId"),
        cover = $("cover");

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
        calcValue = document.getElementById(paymentMethod + '-runtime').value;
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
        document.getElementById(paymentMethod + '_SwitchToTerm').style.display = 'none';

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
/**
 * @param element
 */
function payoneSwitchPayRate(element)
{
    if(element.value === 'RPS'){
        $("ratepay-main-cont").setStyle(
            {
            display: 'block'
            }
        );
        $("payone_ratepay_debit_details").setStyle(
            {
                display: 'block'
            }
        );
        checkRequirementFields(element.value, -1);
    } else {
        $("ratepay-main-cont").setStyle(
            {
            display: 'none'
            }
        );
        $("payone_ratepay_debit_details").setStyle(
            {
                display: 'none'
            }
        );
        checkRequirementFields(element.value, -1);
    }
}

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
        requireRegistrationNumber(forceRequirement, fieldPrefix);
        requireVat(forceRequirement, fieldPrefix);
        return;
    }

    var b2b = document.getElementsByName('payment[payone_isb2b]').item(0).value;

    forceRequirement = (b2b !== '1');
    checkRequirementFields(method, forceRequirement);
}