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
 * @param element
 */
function payoneSwitchWallet(element)
{
    var ElementValue = element.value;
    var ElementValueSplit = ElementValue.split('_');
    var typeId = ElementValueSplit[0];
    var typeCode = ElementValueSplit[1];
    $("payone_wallet_wlt_type").setValue(typeCode);
    $("payone_wallet_config_id").setValue(typeId);
}
