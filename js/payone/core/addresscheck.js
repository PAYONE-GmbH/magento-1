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

Event.observe(window, 'load', function () {
    wrapAddressNextStepEvents();
});


/**
 * Place a wrapper for the nextStep function on Magento´s Billing and Shipping objects, to allow additional output on addresscheck failure
 */
function wrapAddressNextStepEvents() {
    billing.onSave = nextStepWithAddresscheckOutput.bindAsEventListener(billing.nextStep, 'billing', billing.nextStep);

    if (typeof shipping != "undefined") // no shipping inputs for virtual orders.
    {
        shipping.onSave = nextStepWithAddresscheckOutput.bindAsEventListener(shipping.nextStep, 'shipping', shipping.nextStep);
    }
}

function nextStepWithAddresscheckOutput(transport, address_type, origSaveMethod) {

    if (transport && transport.responseText) {
        var response = transport.responseText.evalJSON();
        if (response.error) {
            if (response.message.payone_address_invalid) {
                response.message = response.message.payone_address_invalid;
                    transport.responseText = '{"error":1,"message":"' + response.message + '"}';

            }
            if (response.message.payone_address_error) {
                response.message = response.message.payone_address_error;
                    transport.responseText = '{"error":1,"message":"' + response.message + '"}';
            }
            if (response.message.payone_address_corrected) {
                handleCorrectedAddress(response.message.payone_address_corrected, address_type);
                response.message = response.message.payone_address_corrected.customermessage;

                    //transport.responseText = '{"error":1,"message":"' + response.message + '"}';
            }

        }
    }

    var result = origSaveMethod(transport);
}


function handleCorrectedAddress(data, address_type) {
    sConfirmMessage  = data.customermessage + "\n\n";
    sConfirmMessage += data.street + "\n";
    if(data.street2 != '') {
        sConfirmMessage += data.street2 + "\n";
    }
    sConfirmMessage += data.city + "\n";
    sConfirmMessage += data.postcode;
    if(confirm(sConfirmMessage)) {
        $(address_type + ':street1').value = data.street;
        $(address_type + ':street2').value = data.street2;
        $(address_type + ':city').value = data.city;
        $(address_type + ':postcode').value = data.postcode;
        Element.show(address_type + '-new-address-form');
        
        var addressSelectBox = $(address_type + '-address-select');
        if(addressSelectBox != undefined)
            addressSelectBox.value = '';
    } else {
        if($(address_type + '-new-address-form') && !$(address_type + "_change_denied")) {
            var newHiddenInput = document.createElement("input");
            newHiddenInput.setAttribute("type", "hidden");
            newHiddenInput.setAttribute("id", address_type + "_change_denied");
            newHiddenInput.setAttribute("name", address_type + "_change_denied");
            newHiddenInput.setAttribute("value", "1");
            
            document.getElementById(address_type + '-new-address-form').parentNode.appendChild(newHiddenInput);
        }
    }
}