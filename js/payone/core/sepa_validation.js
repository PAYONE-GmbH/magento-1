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

var Translator = new Translate([]);

Validation.add('validate-bank-code', Translator.translate('Bank code must contain 8 digits'), function (value) {
    value = value.replace(/\s/g, '');
    if (value == '') {
        return true;
    }
    if (value.length != 8) {
        return false;
    }
    return true;
});

Validation.add('validate-sepa-iban', Translator.translate('IBAN should contain only letters and digits'), function (value) {
    value = value.replace(/\s/g, '');
    if (value == '') {
        return true;
    }
    if (!/[a-zA-Z]{2}[A-Za-z0-9]{10,}$/.test(value)) {
        return false;
    }
    return true;
});

Validation.add('validate-sepa-bic', Translator.translate('BIC can contain only 8-11 characters: digits and letters'), function (value) {
    value = value.replace(/\s/g, '');
    if (value == '') {
        return true;
    }
    if (!/[A-Za-z0-9]{8,11}$/.test(value)) {
        return false;
    }
    return true;
});