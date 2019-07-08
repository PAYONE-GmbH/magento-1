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
 * @subpackage      Paydirekt_Express_Review
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Paydirekt_Express_Review_ShippingMethods extends Mage_Checkout_Block_Onepage_Shipping_Method_Available
{
    /**
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
    }

    /**
     * @return array
     */
    public function getShippingRates()
    {
        $rates = parent::getShippingRates();

        $rates = array_filter(
            $rates,
            function ($carrier) {
                return $carrier == 'paydirektexpress';
            },
            ARRAY_FILTER_USE_KEY
        );

        return $rates;
    }
}
