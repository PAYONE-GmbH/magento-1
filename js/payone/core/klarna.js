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
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

function payoneKlarnaCustomerDobInput(output_element)
{
    var daySelect = $('payone_klarna_additional_fields_customer_dob_day');
    var monthSelect = $('payone_klarna_additional_fields_customer_dob_month');
    var yearSelect = $('payone_klarna_additional_fields_customer_dob_year');
    if(output_element == 'payone_financing_klarna_additional_fields_customer_dob_full') {
        var daySelect = $('payone_financing_klarna_additional_fields_customer_dob_day');
        var monthSelect = $('payone_financing_klarna_additional_fields_customer_dob_month');
        var yearSelect = $('payone_financing_klarna_additional_fields_customer_dob_year');
    }

    var hiddenDobFull = $(output_element);

    if (daySelect == undefined || monthSelect == undefined || yearSelect == undefined
        || hiddenDobFull == undefined)  {
        return;
    }

    hiddenDobFull.value = yearSelect.value + "-" + monthSelect.value + "-" + daySelect.value
        + " 00:00:00";
}