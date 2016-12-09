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
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Observer_Sales_Quote_Payment
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * Sets 'payone_config_payment_method_id' to payment.
     * This is required because collectTotals() runs _before_ the payment data gets assigned to the payment object.
     * (Checkout -> savePayment)
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function importDataBefore(Varien_Event_Observer $observer)
    {
        /** @var $payment Mage_Sales_Model_Quote_Payment */
        /** @var $input Varien_Object */
        /** @var $event Varien_Event */
        $event = $observer->getEvent();
        $payment = $event->getPayment();
        $data = $event->getData();
        $input = $data['input'];

        $configId = $input->getPayoneConfigPaymentMethodId();

        if (!empty($configId)) {
            $payment->setPayoneConfigPaymentMethodId($configId);
        }

    }
}