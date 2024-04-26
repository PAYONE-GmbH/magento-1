if (Review) {
    Review.prototype.nextStep = Review.prototype.nextStep.wrap(function (superMethod, transport) {
        if (transport && payment && payment.currentMethod.indexOf("payone") !== -1) {
            var response = transport.responseJSON || transport.responseText.evalJSON(true) || {};

            if (response.redirect) {
                this.isSuccess = true;
                location.href = response.redirect;
                return;
            }
        }
        return superMethod(transport);
    });
}

if (Payment) {
    Payment.prototype.addAfterInitFunction('fcpoVisibleSelectedMethod', function() {
        let methodsSelectors = document.getElementsByName('payment[method]');

        methodsSelectors.forEach(function(input){
            if (input.checked) {
                input.click();
            }
        });
    });
}
