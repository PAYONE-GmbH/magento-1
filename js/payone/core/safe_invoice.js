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
 * @param element
 */
function payoneSwitchSafeInvoice(element)
{
    if (element == undefined) {
        return;
    }

    var ElementValue = element.value;
    var ElementValueSplit = ElementValue.split('_');
    var typeId = ElementValueSplit[0];
    var typeCode = ElementValueSplit[1];
    $("payone_safe_invoice_sin_type").setValue(typeCode);
    $("payone_safe_invoice_config_id").setValue(typeId);

    var divOne = $('payone_klarna_invoice_terms_div');
    var divTwo = $('payone_klarna_additional_fields');

    if (divOne == undefined || divTwo == undefined) {
        return;
    }

    if (typeCode == 'KLV'){
        divOne.show();
        divTwo.show();
    } else {
        divOne.hide();
        divTwo.hide();
    }
}

Event.observe(document, "dom:loaded", function() {
    payoneSwitchSafeInvoice($('payone_safe_invoice_sin_type_select'));
});

Event.observe(document, "dom:ready", function() {
    payoneSwitchSafeInvoice($('payone_safe_invoice_sin_type_select'));
});

Ajax.Responders.register({
    onComplete: function(transport, element) {
        var typeSelect = $('payone_safe_invoice_sin_type_select');
        if (typeSelect == undefined) {
            return;
        }
        var url = element.request.url;
        if (url.indexOf('checkout/onepage/saveShippingMethod') !== -1 || url.indexOf('checkout/onepage/progress') !== 1) {
            payoneSwitchSafeInvoice(typeSelect);
        }
    }
});
