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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Adminhtml_System_Config_Form_Field_AmazonConfig
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        /** @var Varien_Data_Form_Element_Text */
        $element->setReadonly(true);

        if ($element->getId() === 'payone_payment_template_amazon_pay_amz_js_origin') {
            $url = parse_url($this->getFrontendUrl());
            $element->setData('value', 'https://' . $url['host'] . ($url['port'] ? ':' . $url['port'] : ''));
        } elseif ($element->getId() === 'payone_payment_template_amazon_pay_amz_return_url') {
            $element->setData('value', $this->getFrontendUrl('payone_core/amazonpay/checkout'));
        }
        return parent::render($element);
    }

    /**
     * @param string $route
     * @return string
     */
    protected function getFrontendUrl($route = '')
    {
        $scope = $this->getData('config_data')['payone_payment/template_amazon_pay/scope'];
        $scopeId = (int) $this->getData('config_data')['payone_payment/template_amazon_pay/scope_id'];
        if ($scope === 'stores') {
            $storeId = $scopeId;
        } elseif ($scope === 'websites') {
            $storeId = Mage::app()->getWebsite($scopeId)->getDefaultStore()->getId();
        } else {
            $storeId = Mage::app()->getDefaultStoreView()->getId();
        }
        return Mage::getUrl($route, ['_nosid' => true, '_forced_secure' => true, '_store' => $storeId]);
    }
}
