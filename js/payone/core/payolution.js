function displayPayolutionOverlay(sCode)
{
    document.getElementById(sCode + '_overlay').style.display = "";
}
function removePayolutionOverlay(sCode)
{
    document.getElementById(sCode + '_overlay').style.display = "none";
}

function switchVisibility(aIds, blShow) 
{
    for(var i = 0; i < aIds.length; i++) {
        var oElement = $(aIds[i]);
        if(oElement) {
            if(blShow == true) {
                oElement.show();
            } else {
                oElement.hide();
            }
        }
    }
}

function payoneSwitchPayolution(oSelect, sCode) 
{
    if (oSelect == undefined) {
        return;
    }

    if(oSelect.value == 'PYV') {
        var aHide = [
            sCode + '_debit_wrap',
            sCode + '_debit_wrap2',
            sCode + '_installment_wrap1',
            sCode + '_installment_wrap2'
        ];
        var aShow = [
            sCode + '_b2b_wrap',
            sCode + '_birthday_wrap',
            sCode + '_acceptance_wrap'
        ];
        switchVisibility(aHide, false);
        switchVisibility(aShow, true);
        $(sCode + '_selected_installmentplan').value = '0';
    } else if(oSelect.value == 'PYD') {
        var aHide = [
            sCode + '_installment_wrap1',
            sCode + '_installment_wrap2',
            sCode + '_debit_subwrap'
        ];
        var aShow = [
            sCode + '_debit_wrap',
            sCode + '_debit_wrap2',
            sCode + '_b2b_wrap',
            sCode + '_birthday_wrap',
            sCode + '_acceptance_wrap'
        ];
        switchVisibility(aHide, false);
        switchVisibility(aShow, true);
        $(sCode + '_selected_installmentplan').value = '0';
    } else if(oSelect.value == 'PYS') {
        if(!$(sCode + '_installment_wrap2').visible()) {// reset installment init state
            var aHide = [
                sCode + '_debit_wrap',
                sCode + '_debit_wrap2',
                sCode + '_debit_subwrap'
            ];
            var aShow = [
                sCode + '_installment_wrap1'
            ];
            switchVisibility(aHide, false);
            switchVisibility(aShow, true);
            $(sCode + '_selected_installmentplan').value = '';
        }
    }
    
    if(oSelect.value == '') {
        $(sCode + '_main_block').hide();
    } else {
        $(sCode + '_main_block').show();
    }
}

function handleInstallmentAllowed(response) 
{
    $(response.code + '_installment_wrap2').update(response.update_section.html);
    
    var aHide = [
        response.code + '_b2b_wrap',
        response.code + '_birthday_wrap',
        response.code + '_acceptance_wrap',
        response.code + '_installment_wrap1'
    ];
    var aShow = [
        response.code + '_installment_wrap2'
    ];
    switchVisibility(aHide, false);
    switchVisibility(aShow, true);
}

function handleInstallment(sCode, sUrl) 
{
    if (checkout.loadWaiting!=false) return;
    
    var validator = new Validation(payment.form);
    if (payment.validate() && validator.validate()) {
        checkout.setLoadWaiting('payment');
        
        var sDob = $(sCode + '_additional_fields_customer_dob_full').value;
        var sType = $(sCode + '_type_select').value;
        var sPaymentMethodId = $(sCode + '_payment_method_id').value;

        new Ajax.Request(
            sUrl, {
            method: 'Post',
            parameters: {
                payone_payolution_type : sType,
                payone_customer_dob : sDob,
                payone_config_payment_method_id : sPaymentMethodId,
                code : sCode
            },
            onComplete: function (transport) {
                checkout.setLoadWaiting(false);
                if(transport.responseText) {
                    response = JSON.parse(transport.responseText);
                    if(response.success == true) {
                        handleInstallmentAllowed(response);
                        return;
                    }
                }

                alert(Translator.translate("The installment calculation failed. Please choose another payment type."));
            }
            }
        );
    }
}

function switchInstallmentPlan(sKey, sCode, iInstallments) 
{
    $$('.payolution_installmentplans').each(
        function (e) {
          e.hide(); 
        } 
    );
    $$('.payolution_installment_overview').each(
        function (e) {
          e.hide(); 
        } 
    );
    
    var aShow = [
        'payolution_installmentplan_' + sKey,
        'payolution_installment_overview_' + sKey,
        sCode + '_debit_wrap',
        sCode + '_debit_subwrap'
    ];
    switchVisibility(aShow, true);
    $(sCode + '_selected_installmentplan').value = iInstallments;
}