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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

var PayoneCheckout = {
    amazonOrderReferenceId: null,
    addressConsentToken: null,
    selectAddress: function (result) {
        jQuery('#shippingMethodsList').html(result['shippingRatesHtml']);
        new Ajax.Request(PayoneCheckout.optionalAction, {
            method: 'get',
            onSuccess: function (transport) {
                if (transport.responseText) {
                    jQuery('#shippingMethodsAdditional').html(transport.responseText);
                    jQuery('#amazonCheckoutSelectAddress').removeClass('active');
                    jQuery('#amazonCheckoutSelectMethod').addClass('allow active');
                }
            }
        });
    }
};

jQuery(document).on('ready', function () {
    jQuery.extend(PayoneCheckout, PayoneCheckoutParams);
    jQuery('button.amz').on('click', function (event) {
        event.preventDefault();
        var Progress = {currentStep: event.currentTarget.getAttribute('id')};
        new Ajax.Request(PayoneCheckout.progressAction, {
            method: 'get',
            parameters: jQuery.extend({}, PayoneCheckout, Progress),
            onSuccess: function (transport) {
                if (transport.responseText) {
                    var Result = JSON.parse(transport.responseText);
                    if (Result['successful'] === true) {
                        PayoneCheckout[Progress.currentStep](Result);
                    } else {
                        alert(Result['errorMessage']);
                    }
                }
            }
        });
    });
});

window.onAmazonOrderReady = function (orderReference) {
    var match,
        pl     = /\+/g,
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query  = window.location.search.substring(1);

    while (match = search.exec(query)) {
        if (decode(match[1]) === "access_token") {
            PayoneCheckout.addressConsentToken = decode(match[2]);
        }
    }
    PayoneCheckout.amazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
};

window.onAmazonLoginReady = function () {
    amazon.Login.setClientId(PayoneCheckout.amazonClientId);
};

window.onAmazonPaymentsReady = function () {
    new OffAmazonPayments.Widgets.AddressBook({
        sellerId: PayoneCheckout.amazonSellerId,
        scope: 'payments:billing_address payments:shipping_address payments:widget profile',
        onAddressSelect: function () {
            console.log('AddressBook->onAddressSelect' + ': ' + 'Event triggered.');
        },
        design: {
            designMode: 'responsive'
        },
        onReady: window.onAmazonOrderReady,
        onError: function (error) {
            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
        }
    }).bind("addressBookWidgetDiv");

    new OffAmazonPayments.Widgets.Wallet({
        sellerId: PayoneCheckout.amazonSellerId,
        scope: 'payments:billing_address payments:shipping_address payments:widget profile',
        onPaymentSelect: function () {
            console.log('Wallet->onPaymentSelect' + ': ' + 'Event triggered.');
        },
        design: {
            designMode: 'responsive'
        },
        onReady: window.onAmazonOrderReady,
        onError: function (error) {
            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
        }
    }).bind("walletWidgetDiv");
};
