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
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Checkout_Onepage_Shipping_Method_Available
    extends Mage_Checkout_Block_Onepage_Shipping_Method_Available
{
    /**
     * @return array
     */
    public function getShippingRates()
    {
        $rates = parent::getShippingRates();

        $oSession = Mage::getSingleton('checkout/session');
        if (!$oSession->getPayoneExternalCheckoutActive()) {
            return $this->filterForBasicCheckout($rates);
        }
        else{
            if ($oSession->getPaydirektExpressCheckoutActive()) {
                return $this->filterForPaydirektExpressCheckout($rates);
            }
        }

        return $rates;
    }

    /**
     * @param array $rates
     * @return array
     */
    protected function filterForBasicCheckout($rates)
    {
        $rates = array_filter(
            $rates,
            function ($code) {
                if ($code == Payone_Core_Model_Carrier_PaydirektExpress::PAYDIREKT_EXPRESS_SHIPPING_CODE) {
                    return false;
                }

                return true;
            },
            ARRAY_FILTER_USE_KEY
        );

        return $rates;
    }

    /**
     * @param array $rates
     * @return array
     */
    protected function filterForPaydirektExpressCheckout($rates)
    {
        $rates = array_filter(
            $rates,
            function ($carrier) {
                return $carrier == Payone_Core_Model_Carrier_PaydirektExpress::PAYDIREKT_EXPRESS_SHIPPING_CODE;
            },
            ARRAY_FILTER_USE_KEY
        );

        return $rates;
    }
}
