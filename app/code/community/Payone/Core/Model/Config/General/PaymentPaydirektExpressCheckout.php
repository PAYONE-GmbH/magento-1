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
 * @subpackage      Config_General
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */
class Payone_Core_Model_Config_General_PaymentPaydirektExpressCheckout extends Payone_Core_Model_Config_AreaAbstract
{
    /** @var int */
    protected $paydirektExpressCheckoutVisibleOnCart = 0;
    /** @var string */
    protected $paydirektExpressCheckoutImage = '';

    /**
     * @param int $paydirektExpressCheckoutVisibleOnCart
     */
    public function setPaydirektExpressCheckoutVisibleOnCart($paydirektExpressCheckoutVisibleOnCart)
    {
        $this->paydirektExpressCheckoutVisibleOnCart = $paydirektExpressCheckoutVisibleOnCart;
    }

    /**
     * @return int
     */
    public function getPaydirektExpressCheckoutVisibleOnCart()
    {
        return $this->paydirektExpressCheckoutVisibleOnCart;
    }

    /**
     * @param string $paydirektExpressCheckoutImage
     */
    public function setPaydirektExpressCheckoutImage($paydirektExpressCheckoutImage)
    {
        $this->paydirektExpressCheckoutImage = $paydirektExpressCheckoutImage;
    }

    /**
     * @return string
     */
    public function getPaydirektExpressCheckoutImage()
    {
        return $this->paydirektExpressCheckoutImage;
    }
}
