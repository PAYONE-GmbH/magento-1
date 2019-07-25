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

/**
 * Container with properties and event handlers for
 * the interactive Onepage Checkout with Amazon Pay
 */
var PayoneCheckout = {
    amazonOrderReferenceId: null,
    addressConsentToken: null,
    shippingMethodCode: null,
    displayOrderReview: function (result) {
        var parsedResult = new Element('div').update(result['orderReviewHtml']).descendants();

        var review = parsedResult.filter(function(element) {
            return element.getAttribute('id') === 'checkout-review-table-wrapper';
        });

        var agreements = parsedResult.filter(function(element) {
            return element.getAttribute('id') === 'checkout-agreements';
        });

        var orderReview = $('orderReviewDiv');
        review = review.map(function(element){
            return element.outerHTML;
        });
        if (agreements.length === 1) {
            agreements = agreements.map(function(element){
                return element.outerHTML;
            });
            orderReview.update(agreements.concat(review).join(''));
        } else {
            orderReview.update(review.join(''));
        }

        var shortDescriptions = orderReview.select('.item-options dd.truncated');
        shortDescriptions.forEach(function(element){
            Event.observe(element, 'mouseover', function(){
                if (element.down('div.truncated_full_value')) {
                    element.down('div.truncated_full_value').addClassName('show');
                }
            });
            Event.observe(element, 'mouseout', function(){
                if (element.down('div.truncated_full_value')) {
                    element.down('div.truncated_full_value').removeClassName('show');
                }
            });
        });
    },
    afterConfirmSelection: function (result) {
        quoteBaseGrandTotal = result['quoteBaseGrandTotal'];
        checkQuoteBaseGrandTotal = quoteBaseGrandTotal;
        $('shippingMethodsDiv').update(result['shippingRatesHtml']);
        var availableMethods = $$('input[type="radio"][name="shipping_method"]');
        if (availableMethods.length > 1) {
            availableMethods.forEach(function(method){
                method.onclick = function (event) {
                    if (event.currentTarget.checked === true) {
                        PayoneCheckout.shippingMethodCode = event.currentTarget.getValue();
                        window.onCheckoutProgress(
                            $(event.currentTarget).ancestors().filter(
                                function(element){
                                    return element.match('form[id]');
                                }
                            ).first()
                        );
                    }
                };
            });
        }
        var checkedMethod = availableMethods.filter(function(element){return element.checked;});
        if (checkedMethod.length === 1) {
            PayoneCheckout.shippingMethodCode = checkedMethod[0].value;
            $('placeOrder').writeAttribute('disabled', false);
        } else if (availableMethods.length === 1) {
            // In case there's only one method that's not already checked
            var singleMethod = availableMethods.first();
            singleMethod.writeAttribute('checked', true);
            PayoneCheckout.shippingMethodCode = singleMethod.value;
            window.onCheckoutProgress(
                singleMethod.ancestors().filter(
                    function(element){
                        return element.match('form[id]');
                    }
                ).first()
            );
        }
        this.displayOrderReview(result);
        $('checkoutStepInit').removeClassName('active');
        $('checkoutStepFinish').classList.add('allow');
        $('checkoutStepFinish').classList.add('active');
    },
    afterChooseMethod: function (result) {
        this.displayOrderReview(result);
        $('placeOrder').writeAttribute('disabled', false);
    },
    afterPlaceOrder: function (result) {
        amazon.Login.logout();
        window.location = result['redirectUrl'];
    }
};

var match, pl = /\+/g, search = /([^&=]+)=?([^&]*)/g,
    decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
    query = window.location.hash.substring(1) || window.location.search.substring(1),
    initiatedByPopup = true;
if (window.location.hash.substring(1)) {
    initiatedByPopup = false;
}

while (match = search.exec(query)) {
  if (decode(match[1]) === "access_token") {
    var accessToken = decode(match[2]);
    if (typeof accessToken === 'string' && accessToken.match(/^Atza/)) {
      document.cookie = "amazon_Login_accessToken=" + accessToken + ";secure";
      PayoneCheckout.addressConsentToken = accessToken;
    }
  }
}

window.onCheckoutProgress = function (target) {
    target.disabled = true;
    target.parentElement.addClassName('disabled');
    $('addressBookWidgetCover', 'walletWidgetCover').invoke('addClassName','show');
    var Progress = {currentStep: target.getAttribute('id')};
    var agreements = $('checkout-agreements');
    if (agreements) {
        Progress = $H(Progress).merge(agreements.serialize(true)).toObject();
    }

    var parameters = $H(PayoneCheckout).merge(Progress).toObject();
    new Ajax.Request(PayoneCheckout.progressAction, {
        method: 'post',
        parameters: parameters,
        onSuccess: function (transport) {
            if (transport.responseText) {
                var Result = JSON.parse(transport.responseText);
                if (Result['shouldLogout'] === true) {
                    amazon.Login.logout();
                }
                if (Result['successful'] === true) {
                    var Callback = "after"
                        + Progress.currentStep.charAt(0).toUpperCase()
                        + Progress.currentStep.slice(1);
                    PayoneCheckout[Callback](Result);
                } else if (['InvalidPaymentMethod', 'PaymentMethodNotAllowed', 'PaymentPlanNotSet'].indexOf(Result['errorMessage']) !== -1) {
                    window.onAmazonPaymentsInvalidPayment();
                } else {
                    alert(Result['errorMessage']);
                    $('placeOrder').writeAttribute('disabled', true);
                    $('checkoutStepInit').nextSiblings()
                        .forEach(function(element) {
                            element.classList.remove('allow', 'active');
                        });
                    $('checkoutStepInit').classList.add('allow', 'active');
                }
            }
            $('addressBookWidgetCover', 'walletWidgetCover').invoke('removeClassName', 'show');
            target.parentElement.removeClassName('disabled');
            target.disabled = false;
        }
    });
};

window.onDocumentReady = function () {
    for(var key in PayoneCheckoutParams)
        if(PayoneCheckoutParams.hasOwnProperty(key))
            PayoneCheckout[key] = PayoneCheckoutParams[key];
    $$('button.amz').forEach(function(element) {
        element.onclick = function (event) {
            event.preventDefault();
            window.onCheckoutProgress(event.currentTarget);
        };
    });
    $$('li.section').forEach(function(element) {
        element.onclick = function (event) {
            if (event.currentTarget.hasClassName('allow') &&
                !event.currentTarget.hasClassName('active') &&
                event.currentTarget.getAttribute('id') !== null
            ) {
                event.preventDefault();
                $('placeOrder').writeAttribute('disabled', true);
                $(event.currentTarget).nextSiblings().forEach(function(element) {
                    element.classList.remove('allow', 'active');
                });
                $(event.currentTarget).classList.add('allow', 'active');
            }
        };
    });
};

window.onAmazonWidgetsInitialized = function (orderReference) {
    PayoneCheckout.amazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
};

window.onAmazonLoginReady = function () {
    amazon.Login.setClientId(PayoneCheckout.amazonClientId);
    amazon.Login.setUseCookie(true);
};

window.onAmazonPaymentsError = function (error) {
    if (error.getErrorCode() === 'BuyerSessionExpired') {
        jQuery('#addressBookWidgetCover, #walletWidgetCover')
            .css('background', 'lightgrey').addClass('show').delay(15)
            .promise().done(function () {
                alert(PayoneCheckout.expiredAlert);
                window.location.href = PayoneCheckout.cartAction;
            });
    } else {
        console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
    }
};

window.onAmazonPaymentsReady = function () {
    if (PayoneCheckout.amazonOrderReferenceId !== null) {
        return window.onAmazonPaymentsInvalidPayment();
    }
    new OffAmazonPayments.Widgets.AddressBook({
        sellerId: PayoneCheckout.amazonSellerId,
        scope: 'payments:billing_address payments:shipping_address payments:widget profile',
        onAddressSelect: function () {
            $('confirmSelection').writeAttribute('disabled', true);
        },
        design: {
            designMode: 'responsive'
        },
        onReady: window.onAmazonWidgetsInitialized,
        onError: window.onAmazonPaymentsError
    })  // Bind the widget to the DOM
        // element with the given ID.
        .bind('addressBookWidgetDiv')
        // Reset this widget's flag to
        // avoid redrawing, which might
        // happen under circumstances.
        .renderRequested = initiatedByPopup;
    new OffAmazonPayments.Widgets.Wallet({
        sellerId: PayoneCheckout.amazonSellerId,
        scope: 'payments:billing_address payments:shipping_address payments:widget profile',
        onPaymentSelect: function () {
            $('confirmSelection').writeAttribute('disabled', false);
        },
        design: {
            designMode: 'responsive'
        },
        onError: function (error) {
            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
        }
    })  // Bind the widget to the DOM
        // element with the given ID.
        .bind('walletWidgetDiv')
        // Reset this widget's flag to
        // avoid redrawing, which might
        // happen under circumstances.
        .renderRequested = initiatedByPopup;
};

window.onAmazonPaymentsInvalidPayment = function () {
    $('placeOrder').writeAttribute('disabled', true);
    $('checkoutStepInitContent', 'chooseMethod').invoke('addClassName', 'locked');
    $('addressBookWidgetDiv', 'walletWidgetDiv').invoke('empty');
    new OffAmazonPayments.Widgets.AddressBook({
        displayMode: 'Read',
        sellerId: PayoneCheckout.amazonSellerId,
        amazonOrderReferenceId: PayoneCheckout.amazonOrderReferenceId,
        scope: 'payments:billing_address payments:shipping_address payments:widget profile',
        onAddressSelect: function () {
            $('confirmSelection').writeAttribute('disabled', true);
        },
        design: {
            designMode: 'responsive'
        },
        onReady: window.onAmazonWidgetsInitialized,
        onError: window.onAmazonPaymentsError
    })  // Bind the widget to the DOM
        // element with the given ID.
        .bind('addressBookWidgetDiv')
        // Reset this widget's flag to
        // avoid redrawing, which might
        // happen under circumstances.
        .renderRequested = initiatedByPopup;
    new OffAmazonPayments.Widgets.Wallet({
        sellerId: PayoneCheckout.amazonSellerId,
        amazonOrderReferenceId: PayoneCheckout.amazonOrderReferenceId,
        scope: 'payments:billing_address payments:shipping_address payments:widget profile',
        onPaymentSelect: function () {
            $('confirmSelection').writeAttribute('disabled', false);
            $('checkoutStepInitContent').addClassName('solved');
        },
        design: {
            designMode: 'responsive'
        },
        onError: function (error) {
            console.log(error.getErrorCode() + ': ' + error.getErrorMessage());
        }
    })  // Bind the widget to the DOM
        // element with the given ID.
        .bind('walletWidgetDiv')
        // Reset this widget's flag to
        // avoid redrawing, which might
        // happen under circumstances.
        .renderRequested = initiatedByPopup;
    $('checkoutStepInit').nextSiblings()
        .forEach(function(element) {
            element.classList.remove('allow', 'active');
        });
    $('checkoutStepInit').classList.add('allow', 'active');

};

$(document).onready = window.onDocumentReady;
