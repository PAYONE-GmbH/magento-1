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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

var PayoneCheckout = {
    shippingMethodCode: false,
    baseUrl: "",
    chooseMethodUrl: "payone_core/mastercardMasterpass/chooseShippingMethod",
    placeOrderUrl: "payone_core/mastercardMasterpass/placeOrder",
    reloadReviewUrl: "checkout/onepage/review/",
    init: function (baseUrl) {

        this.baseUrl = baseUrl;

        var button = jQuery('#placeOrder');
        button.on('click', function () {
            window.placeOrder(PayoneCheckout.getPlaceOrderUrl());
        });

        var availableMethods = jQuery('input[type="radio"][name="shipping_method"]');
        if (availableMethods.length > 1) {
            availableMethods.on('click', function (event) {
                if (event.currentTarget.checked === true) {
                    PayoneCheckout.shippingMethodCode = event.currentTarget.value;
                    window.chooseMethod(
                        PayoneCheckout.getChooseMethodeUrl(),
                        PayoneCheckout.shippingMethodCode
                    );
                }
            });
        }
        else {
            var method = availableMethods.get(0).value;
            window.chooseMethod(
                PayoneCheckout.getChooseMethodeUrl(),
                method
            );
        }

        this.handlePlaceOrderButton();

        this.reloadReview();
    },
    handlePlaceOrderButton: function() {
        var availableMethods = jQuery('input[type="radio"][name="shipping_method"]');
        var checkedMethod = availableMethods.filter(':checked');
        if (checkedMethod.length === 1) {
            PayoneCheckout.shippingMethodCode = checkedMethod[0].value;
            jQuery('#placeOrder').attr('disabled', false);
        } else if (availableMethods.length === 1) {
            // In case there's only one method that's not already checked
            var singleMethod = availableMethods.filter(':first');
            singleMethod.attr('checked', true);
            jQuery('#placeOrder').attr('disabled', false);
            PayoneCheckout.shippingMethodCode = singleMethod[0].value;
        }
    },
    reloadReview: function () {
        jQuery.ajax(
            {
                url: this.getReloadReviewUrl(),
                error: function (result) {
                    console.log(result);
                },
                success: function (result) {
                    var review = jQuery(result).filter('#checkout-review-table-wrapper');
                    var container = jQuery('#checkout-review-load');
                    container.html(review);
                    PayoneCheckout.handlePlaceOrderButton();
                },
                complete: function(request, status) {
                    window.unlockActivity();
                }
            }
        );
    },
    getChooseMethodeUrl: function () {
        return this.baseUrl + this.chooseMethodUrl;
    },
    getPlaceOrderUrl: function () {
        return this.baseUrl + this.placeOrderUrl;
    },
    getReloadReviewUrl: function () {
        return this.baseUrl + this.reloadReviewUrl;
    }
};

window.chooseMethod = function(url, methodCode) {
    window.lockActivity();
    jQuery.ajax(
        {
            url: url,
            data: {
                code: methodCode
            },
            error: function(result) {
                window.unlockActivity();
                alert('An error occurred during the Masterpass Checkout.');
            },
            success: function (result) {
                var response = JSON.parse(result);
                if (response.code !== 200) {
                    window.unlockActivity();
                    alert(response.data.message);
                    return;
                }

                PayoneCheckout.reloadReview();
            }
        }
    );
};

window.placeOrder = function (url) {
    var agreementCollection = jQuery("[id^=agreement-]");
    var agreement = [];
    agreementCollection.each(function() {
        if (this.checked) {
            var index = $(this).attr("name").match(/.*\[(.*)\]/);
            if (index.length > 1) {
                agreement.push(index[1]);
            }
        }
    });

    window.lockActivity();
    jQuery.ajax(
        {
            url: url,
            data: {
                agreement: agreement
            },
            error: function(result) {
                window.unlockActivity();
                alert('An error occurred during the Masterpass Checkout.');
            },
            success: function (result) {
                window.unlockActivity();

                var response = JSON.parse(result);
                if (response.code !== 200) {
                    alert(response.data.message);
                    return;
                }

                window.location = response.data.redirectUrl;
            }
        }
    );
};

window.lockActivity = function() {
    var fadeOut = jQuery('.fadeOut');
    fadeOut.show();
};
window.unlockActivity = function() {
    var fadeOut = jQuery('.fadeOut');
    fadeOut.hide();
};