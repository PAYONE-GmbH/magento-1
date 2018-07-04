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
 * @package         Payone_Core_Model
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

interface Payone_Core_Model_Service_Mastercard_Masterpass_RequestInterface
{
    const INIT_CHECKOUT_REQUEST_TYPE = 'init_checkout';
    const PREPARE_REVIEW_ORDER_REQUEST_TYPE = 'prepare_review_order';
    const CHOOSE_SHIPPING_METHOD_REQUEST_TYPE = 'choose_shipping_method';
    const PLACE_ORDER_REQUEST_TYPE = 'place_order';

    /**
     * @return string
     */
    public function getType();
}