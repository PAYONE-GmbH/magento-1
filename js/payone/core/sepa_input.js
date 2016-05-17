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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @param input
 */
function inputToUpperCase(input) {
    var caretPosition = getCaretPos(input);
    input.value = input.value.toUpperCase().replace(/\s/g, '');
    setCaretPos(input, caretPosition);
}

/**
 *
 * @param input
 */
function inputToUppaerCaseAndNumbers(input) {
    var caretPosition = getCaretPos(input);
    input.value = input.value.toUpperCase().replace(/\W|[_]/g, '');
    setCaretPos(input, caretPosition);
}

/**
 *
 * @param input
 */
function inputToNumbers(input) {
    var caretPosition = getCaretPos(input);
    input.value = input.value.replace(/\D/g, '');
    setCaretPos(input, caretPosition);
}

/**
 *
 * @param oField
 * @returns {number}
 */
function getCaretPos(oField) {
    var iCaretPos = 0;
    if (Prototype.Browser.IE) {
        var oSel = document.selection.createRange();
        oSel.moveStart('character', -oField.value.length);
        iCaretPos = oSel.text.length;
    } else {
        iCaretPos = oField.selectionEnd;
    }
    return iCaretPos;
}

/**
 *
 * @param oField
 * @param iCaretPos
 */
function setCaretPos(oField, iCaretPos) {
    if (Prototype.Browser.IE) {
        var oSel = document.selection.createRange();
        oSel.moveStart('character', -oField.value.length);
        oSel.moveStart('character', iCaretPos);
        oSel.moveEnd('character', 0);
        oSel.select();
    } else {
        oField.selectionStart = iCaretPos;
        oField.selectionEnd = iCaretPos;
        oField.focus();
    }
}