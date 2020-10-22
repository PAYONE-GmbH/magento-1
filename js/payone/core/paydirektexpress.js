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
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
var PayoneCheckout = {
    shippingMethodCode: false,
    baseUrl: "",
    placeOrderUrl: "payone_core/paydirektExpress/placeOrder",
    reloadReviewUrl: "checkout/onepage/review/",
    init: function (baseUrl) {
        this.baseUrl = baseUrl;

        var button = $('placeOrder');
        button.on('click', function () {
            window.placeOrder(PayoneCheckout.getPlaceOrderUrl());
        });

        this.reloadReview();
    },
    reloadReview: function () {
        new Ajax.Request(
            this.getReloadReviewUrl(),
            {
                onFailure: function (result) {
                    console.log(result);
                },
                onSuccess: function (result) {
                    var wrapper = document.createElement('div');
                    wrapper.innerHTML = result.responseText;
                    var review = wrapper.firstChild;

                    var container = $$('[id=checkout-review-load]')[0];
                    container.innerHTML = review.outerHTML;
                },
                onComplete: function(request, status) {
                    window.unlockActivity();
                }
            }
        );
    },
    getPlaceOrderUrl: function () {
        return this.baseUrl + this.placeOrderUrl;
    },
    getReloadReviewUrl: function () {
        return this.baseUrl + this.reloadReviewUrl;
    }
};

window.placeOrder = function (url) {
    var agreementCollection = $$("input[id^=agreement-]");
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

    new Ajax.Request(
        url,
        {
            parameters: {
                agreement: agreement
            },
            onFailure: function(result) {
                window.unlockActivity();
                alert('An error occurred during the Paydirekt Express Checkout.');
            },
            onSuccess: function (result) {
                window.unlockActivity();

                var response = JSON.parse(result.responseText);
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
    var fadeOut = $$('.fadeOut')[0];
    $(fadeOut).show();
};
window.unlockActivity = function() {
    var fadeOut = $$('.fadeOut')[0];
    $(fadeOut).hide();
};