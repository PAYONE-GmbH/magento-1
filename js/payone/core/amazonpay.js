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
    addressConsentToken: null
};

jQuery(document).on('ready', function () {
    jQuery('button.amz').on('click', function (event) {
        event.preventDefault();
        var action = event.currentTarget.getAttribute('id');
        new Ajax.Request(window.progressUrl, {
            method: 'get',
            parameters: {checkoutAction: action},
            onSuccess: function (transport) {
                if (transport.responseText) {
                    alert(JSON.parse(transport.responseText).result)
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
    amazon.Login.setClientId(window.amazonClientId);
};

window.onAmazonPaymentsReady = function () {
    new OffAmazonPayments.Widgets.AddressBook({
        sellerId: window.amazonSellerId,
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
        sellerId: window.amazonSellerId,
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
