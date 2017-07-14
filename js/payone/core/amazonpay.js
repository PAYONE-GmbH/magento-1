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

//noinspection JSUnusedGlobalSymbols
/**
 * Container with properties and helper methods for
 * the interactive Onepage Checkout with Amazon Pay
 */
var PayoneCheckout = {
    amazonOrderReferenceId: null,
    addressConsentToken: null,
    shippingMethodCode: null,
    displayOrderReview: function (result) {
        var review = jQuery(result['orderReviewHtml']).filter('#checkout-review-table-wrapper');
        var agreements = jQuery(result['orderReviewHtml']).filter('#checkout-agreements');
        if (agreements.length === 0) {
            agreements = jQuery(result['orderReviewHtml']).find('#checkout-agreements');
        }
        var orderReview = jQuery('#orderReviewDiv');
        if (agreements.length === 1) {
            orderReview.html(jQuery.merge(agreements, review));
        } else {
            orderReview.html(review);
        }
        var shortDescriptions = orderReview.find('.item-options dd.truncated');
        shortDescriptions.hover(function (event) {
            jQuery(event.currentTarget).find('.truncated_full_value').addClass('show');
        }, function (event) {
            jQuery(event.currentTarget).find('.truncated_full_value').removeClass('show');
        });
    },
    afterConfirmSelection: function (result) {
        quoteBaseGrandTotal = result['quoteBaseGrandTotal'];
        checkQuoteBaseGrandTotal = quoteBaseGrandTotal;
        jQuery('#shippingMethodsDiv').html(result['shippingRatesHtml']);
        var availableMethods = jQuery('input[type="radio"][name="shipping_method"]');
        if (availableMethods.length > 1) {
            availableMethods.on('click', function (event) {
                if (event.currentTarget.checked === true) {
                    PayoneCheckout.shippingMethodCode = event.currentTarget.getValue();
                    window.onCheckoutProgress(jQuery(event.currentTarget).parents('form[id]')[0]);
                }
            });
        }
        var checkedMethod = availableMethods.filter(':checked');
        if (checkedMethod.length === 1) {
            PayoneCheckout.shippingMethodCode = checkedMethod[0].getValue();
            jQuery('#placeOrder').attr('disabled', false);
        } else if (availableMethods.length === 1) {
            // In case there's only one method that's not already checked
            var singleMethod = availableMethods.filter(':first');
            singleMethod.attr('checked', true);
            PayoneCheckout.shippingMethodCode = singleMethod[0].getValue();
            window.onCheckoutProgress(singleMethod.parents('form[id]')[0]);
        }
        this.displayOrderReview(result);
        jQuery('#checkoutStepInit').removeClass('active');
        jQuery('#checkoutStepFinish').addClass('allow active');
    },
    afterChooseMethod: function (result) {
        this.displayOrderReview(result);
        jQuery('#placeOrder').attr('disabled', false);
    },
    afterPlaceOrder: function (result) {
        window.location = result['redirectUrl'];
    }
};

window.onCheckoutProgress = function (target) {
    target.disabled = true;
    target.parentElement.addClassName('disabled');
    jQuery('#addressBookWidgetCover, #walletWidgetCover').addClass('show');
    var Progress = {currentStep: target.getAttribute('id')};
    var Agreements = jQuery('#checkout-agreements');
    if (Agreements) {
        Agreements.serializeArray().each(function(Agreement) {
            Progress[Agreement['name']] = Agreement['value'];
        });
    }
    new Ajax.Request(PayoneCheckout.progressAction, {
        method: 'get',
        parameters: jQuery.extend({}, PayoneCheckout, Progress),
        onSuccess: function (transport) {
            if (transport.responseText) {
                var Result = JSON.parse(transport.responseText);
                if (Result['successful'] === true) {
                    var Callback = "after"
                        + Progress.currentStep.charAt(0).toUpperCase()
                        + Progress.currentStep.slice(1);
                    PayoneCheckout[Callback](Result);
                } else if (Result['errorMessage'] === 'InvalidPaymentMethod') {
                    alert('There\'s an error! We will re-render the widgets now...');
                    // TODO - Properly display the widgets according to this situation
                } else {
                    alert(Result['errorMessage']);
                }
            }
            jQuery('#addressBookWidgetCover, #walletWidgetCover').removeClass('show');
            target.parentElement.removeClassName('disabled');
            target.disabled = false;
        }
    });
};

window.onDocumentReady = function () {
    jQuery.extend(PayoneCheckout, PayoneCheckoutParams);
    jQuery('button.amz').on('click', function (event) {
        event.preventDefault();
        window.onCheckoutProgress(event.currentTarget);
    });
    jQuery('li.section').on('click', function (event) {
        if (event.currentTarget.hasClassName('allow') &&
            !event.currentTarget.hasClassName('active') &&
            event.currentTarget.getAttribute('id') !== null
        ) {
            event.preventDefault();
            jQuery('#placeOrder').attr('disabled', true);
            jQuery(event.currentTarget).nextAll().removeClass('allow active');
            jQuery(event.currentTarget).addClass('allow active');
        }
    })
};

window.onAmazonWidgetsInitialized = function (orderReference) {
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
            jQuery('#confirmSelection').attr('disabled', true);
        },
        design: {
            designMode: 'responsive'
        },
        onReady: window.onAmazonWidgetsInitialized,
        onError: function (error) {
            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
        }
    }).bind("addressBookWidgetDiv");

    new OffAmazonPayments.Widgets.Wallet({
        sellerId: PayoneCheckout.amazonSellerId,
        scope: 'payments:billing_address payments:shipping_address payments:widget profile',
        onPaymentSelect: function () {
            jQuery('#confirmSelection').attr('disabled', false);
        },
        design: {
            designMode: 'responsive'
        },
        onError: function (error) {
            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
        }
    }).bind("walletWidgetDiv");
};

jQuery(document).on('ready', window.onDocumentReady);
