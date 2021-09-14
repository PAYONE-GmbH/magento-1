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
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @author          Fatchip GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

function payWithApplePay(amount, country, currency, networks, createSessionUrl, createPaymentUrl) {
    var session = new ApplePaySession(3, {
        countryCode: country,
        currencyCode: currency,
        supportedNetworks: networks,
        merchantCapabilities: ['supports3DS', 'supportsDebit', 'supportsCredit'],
        total: { label: 'PAYONE Apple Pay', amount: amount }
    });

    session.onvalidatemerchant = function(evt) {
        var validationUrl = evt.validationURL;
        var quoteId = $('quoteId').value;

        new Ajax.Request(createSessionUrl,
            {
                method: 'post',
                parameters: {
                    validationUrl: validationUrl,
                    quoteId: quoteId
                },
                onSuccess: function(response) {
                    var data = JSON.parse(response.responseText);
                    if (201 !== response.status) {
                        alert(data.status + ' ' + response.status + ' : ' + data.message);
                        return
                    }
                    session.completeMerchantValidation(data.merchantSession);
                },
                onFailure: function(response) {
                    var data = JSON.parse(response.responseText);
                    alert(data.status + ' ' + response.status + ' : ' + data.message);
                }
            }
        );
    };

    session.onpaymentauthorized = function(evt) {
        var token = evt.payment.token;
        var quoteId = $('quoteId').value;

        new Ajax.Request(createPaymentUrl,
            {
                method: 'post',
                parameters: {
                    token: JSON.stringify(token),
                    quoteId: quoteId
                },
                onSuccess: function(response) {
                    var data = JSON.parse(response.responseText);

                    if (200 !== response.status) {
                        session.completePayment({
                            status: ApplePaySession.STATUS_FAILURE,
                            errors: [data.message]
                        });
                        return
                    }

                    session.completePayment({
                        status: ApplePaySession.STATUS_SUCCESS,
                        errors: []
                    });

                    window.location = data.redirectUrl;
                },
                onFailure: function(response) {
                    var data = JSON.parse(response.responseText);
                    session.completePayment({
                        status: ApplePaySession.STATUS_FAILURE,
                        errors: [data.message]
                    });
                }
            }
        );
    };

    session.begin()
}

function checkDevice(registerDeviceUrl) {
    var allowedDevice = 0;
    if (window.ApplePaySession) {
        var promise = ApplePaySession.canMakePayments();
        promise.then(function (canMakePayments) {
            if (canMakePayments) {
                allowedDevice = 1;
            }

            new Ajax.Request(registerDeviceUrl,
                {
                    method: 'post',
                    parameters: {allowed: allowedDevice},
                    onSuccess: checkDeviceSuccess,
                    onFailure: checkDeviceFailure
                }
            );
        });
    } else {
        new Ajax.Request(registerDeviceUrl,
            {
                method: 'post',
                parameters: {allowed: 0},
                onSuccess: checkDeviceSuccess,
                onFailure: checkDeviceFailure
            }
        );
    }
}
function checkDeviceSuccess (response) {
    var responseData = JSON.parse(response.responseText);

    if (200 !== response.status) {
        alert("Bad response\n" + responseData.message);
    }
}
function checkDeviceFailure(response) {
    var responseData = JSON.parse(response.responseText);
    alert("Failure : Call failed\n" + responseData.message);
}

if ('undefined' === typeof applePayRegisterDeviceUrl) {
    var applePayRegisterDeviceUrl = 'https://' + window.location.hostname + '/payone_core/applepay/registerDevice';
}
checkDevice(applePayRegisterDeviceUrl);
