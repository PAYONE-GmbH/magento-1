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
 * @param code
 */
function checkIbanCountryCode(code) 
{
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

function disableElement(element) 
{
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

function enableElement(element) 
{
    if (element == undefined) {
        return;
    }

    element.disabled = false;
    element.toggleClassName('require-entry');
}