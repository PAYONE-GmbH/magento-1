<?php
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
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @author          Fatchip GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */
class Payone_Core_Block_Adminhtml_System_Config_Form_Field_ApplePayCertificateKey
    extends Payone_Core_Block_Adminhtml_System_Config_Form_Field_Abstract
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $html = '<input id="payone_payment_template_apple_pay_apl_certificate_private_key" class="input-text" type="text" name="groups[template_apple_pay][fields][apl_certificate_private_key][value]" value="' . $this->getValue() . '">';
        $html .= '<input id="payone_payment_template_apple_pay_apl_certificate_private_key_file" type="file" name="payone_payment_template_apple_pay_apl_certificate_private_key_file">';
        $html .= '<br />';
        $html .= '<textarea id="payone_payment_template_apple_pay_apl_certificate_private_key_textarea" name="payone_payment_template_apple_pay_apl_certificate_private_key_textarea"></textarea>';
        $html .= '<script type="text/javascript">$("payone_payment_template_apple_pay_apl_certificate_private_key_file").onchange = function(e) {$("payone_payment_template_apple_pay_apl_certificate_private_key").value = this.files[0].name}</script>';
        return $html;
    }

    protected function getValue()
    {
        return $this->getConfigData()['payone_payment/template_apple_pay/apl_certificate_private_key'];
    }
}
