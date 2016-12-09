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
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Registry
    extends Payone_Core_Helper_Abstract
{
    const KEY_PAYMENT_CANCEL = 'payone_payment_cancel_';

    /**
     * Wrapper for Mage::registry()
     * @param string $key
     *
     * @return mixed
     */
    public function registry($key)
    {
       return Mage::registry($key);
    }


    /**
     * Wrapper for Mage::register()
     *
     * @param string $key
     * @param mixed $value
     * @param bool $graceful
     *
     * @throws Mage_Core_Exception
     */
    public function register($key, $value, $graceful = false)
    {
        return Mage::register($key, $value, $graceful);
    }

    /**
     * Wrapper for Mage::unregister()
     *
     * @param string $key
     */
    public function unregister($key)
    {
        Mage::unregister($key);
    }

    /**
     * @param Mage_Payment_Model_Info $payment
     */
    public function registerPaymentCancel(Mage_Payment_Model_Info $payment)
    {
        $this->register(self::KEY_PAYMENT_CANCEL.$payment->getId(), true);
    }

    /**
     *
     * @param Mage_Payment_Model_Info $payment
     * @return bool
     */
    public function isPaymentCancelRegistered(Mage_Payment_Model_Info $payment)
    {
        $value = $this->registry(self::KEY_PAYMENT_CANCEL.$payment->getId());

        if(empty($value) or !($value))
            return false;
        else
            return true;
    }
}