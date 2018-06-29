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

interface Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface
{
    const INIT_CHECKOUT_OK_RESPONSE_TYPE = 'init_checkout_ok';
    const INIT_CHECKOUT_ERROR_RESPONSE_TYPE = 'init_checkout_error';
    const FETCH_CHECKOUT_OK_RESPONSE_TYPE = 'fetch_checkout_ok';
    const FETCH_CHECKOUT_ERROR_RESPONSE_TYPE = 'fetch_checkout_error';
    const CHOOSE_SHIPPING_METHOD_OK_RESPONSE_TYPE = 'choose_shipping_method_ok';
    const CHOOSE_SHIPPING_METHOD_ERROR_RESPONSE_TYPE = 'choose_shipping_method_error';
    const PLACE_ORDER_OK_RESPONSE_TYPE = 'place_order_ok';
    const PLACE_ORDER_ERROR_RESPONSE_TYPE = 'place_order_error';

    const INIT_CHECKOUT_OK_RESPONSE_CODE = 200;
    const INIT_CHECKOUT_ERROR_RESPONSE_CODE = 400;
    const FETCH_CHECKOUT_OK_RESPONSE_CODE = 200;
    const FETCH_CHECKOUT_ERROR_RESPONSE_CODE = 400;
    const CHOOSE_SHIPPING_METHOD_OK_RESPONSE_CODE = 200;
    const CHOOSE_SHIPPING_METHOD_ERROR_RESPONSE_CODE = 400;
    const PLACE_ORDER_OK_RESPONSE_CODE = 200;
    const PLACE_ORDER_ERROR_RESPONSE_CODE = 400;

    /**
     * @return string
     */
    public function getType();

    /**
     * @return int
     */
    public function getCode();

    /**
     * @param string $key
     * @return mixed
     */
    public function getData($key = null);

    /**
     * @param string $key
     * @param string $value
     */
    public function setData($key, $value);

    /**
     * @return string
     */
    public function __toString();
}