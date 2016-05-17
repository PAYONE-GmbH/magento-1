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

var Translator = new Translate([]);

Validation.add('validate-18-years', Translator.translate('You have to be at least 18 years old to use this payment type!'), function (value) {
    var oBirthDate = new Date(value);
    var oMinDate = new Date(new Date().setYear(new Date().getFullYear() - 18));
    if(oBirthDate > oMinDate) {
        return false;
    }
    return true;
});