function displayPayolutionOverlay() {
    document.getElementById('payolution_overlay').style.display = "";
}
function removePayolutionOverlay() {
    document.getElementById('payolution_overlay').style.display = "none";
}

function payoneSwitchPayolution(oSelect, sCode) {
    if (oSelect == undefined) {
        return;
    }

    var sValue = oSelect.value;
    var oElementMain = $(sCode + '_main_block');
    var oElementDebit = $(sCode + '_debit_wrap');
    var oElementDebit2 = $(sCode + '_debit_wrap2');

    if(sValue == 'PYV') {
        if(oElementDebit) {
            oElementDebit.hide();
        }
        if(oElementDebit2) {
            oElementDebit2.hide();
        }
    } else if(sValue == 'PYD') {
        if(oElementDebit) {
            oElementDebit.show();
        }
        if(oElementDebit2) {
            oElementDebit2.show();
        }
    }
    
    if(sValue == '') {
        oElementMain.hide();
    } else {
        oElementMain.show();
    }
}