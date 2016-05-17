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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */
class Payone_Core_Model_Config_General_PaymentPaypalExpressCheckout extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var int
     */
    protected $paypal_express_checkout_visible_on_cart = 0;

    /**
     * @var string
     */
    protected $paypal_express_checkout_image = '';


    /**
     * @param int $paypal_express_checkout_visible_on_cart
     */
    public function setPaypalExpressCheckoutVisibleOnCart($paypal_express_checkout_visible_on_cart)
    {
        $this->paypal_express_checkout_visible_on_cart = $paypal_express_checkout_visible_on_cart;
    }

    /**
     * @return int
     */
    public function getPaypalExpressCheckoutVisibleOnCart()
    {
        return $this->paypal_express_checkout_visible_on_cart;
    }

    /**
     * @param string $paypal_express_checkout_image
     */
    public function setPaypalExpressCheckoutImage($paypal_express_checkout_image)
    {
        $this->paypal_express_checkout_image = $paypal_express_checkout_image;
    }

    /**
     * @return string
     */
    public function getPaypalExpressCheckoutImage()
    {
        return $this->paypal_express_checkout_image;
    }
}