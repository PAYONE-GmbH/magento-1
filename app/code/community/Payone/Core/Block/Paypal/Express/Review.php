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
 * @subpackage      Adminhtml
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

class Payone_Core_Block_Paypal_Express_Review extends Mage_Paypal_Block_Express_Review
{
    /**
     * Retrieve payment method and assign additional template values
     *
     * @return Payone_Core_Block_Paypal_Express_Review
     */
    protected function _beforeToHtml()
    {
        $methodInstance = $this->_quote->getPayment()->getMethodInstance();
        // are we in a secure environment?
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        $this->setPaymentMethodTitle($methodInstance->getTitle());
        $this->setUpdateOrderSubmitUrl($this->getUrl("{$this->_paypalActionPrefix}/express/updateOrder", array('_secure' => $isSecure)));
        $this->setUpdateShippingMethodsUrl($this->getUrl("{$this->_paypalActionPrefix}/express/updateShippingMethods", array('_secure' => $isSecure)));

        $this->setShippingRateRequired(true);
        if ($this->_quote->getIsVirtual()) {
            $this->setShippingRateRequired(false);
        } else {
            // prepare shipping rates
            $this->_address = $this->_quote->getShippingAddress();
            $groups = $this->_address->getGroupedAllShippingRates();
            if ($groups && $this->_address) {
                $this->setShippingRateGroups($groups);
                // determine current selected code & name
                foreach ($groups as $code => $rates) {
                    foreach ($rates as $rate) {
                        if ($this->_address->getShippingMethod() == $rate->getCode()) {
                            $this->_currentShippingRate = $rate;
                            break(2);
                        }
                    }
                }
            }

            // misc shipping parameters
            $this->setShippingMethodSubmitUrl($this->getUrl("{$this->_paypalActionPrefix}/express/saveShippingMethod", array('_secure' => $isSecure)))
                ->setCanEditShippingAddress(false)
                ->setCanEditShippingMethod($this->_quote->getMayEditShippingMethod());
        }

        $this->setEditUrl(null) //$this->getUrl("{$this->_paypalActionPrefix}/express/edit")
            ->setPlaceOrderUrl($this->getUrl("payone_core/pexpress/placeOrder", array('_secure' => $isSecure)));
    }
}