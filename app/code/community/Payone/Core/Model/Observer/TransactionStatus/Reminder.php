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
 * @package         Payone_Core_Model_Service_Abstract
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@payone.de> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model_Service_Abstract
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 *
 */
class Payone_Core_Model_Observer_TransactionStatus_Reminder extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function onReminder(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

        /**
         * @var $transactionStatus Payone_Core_Model_Domain_Protocol_TransactionStatus
         */
        $transactionStatus = $event->getTransactionStatus();

        $order = $this->getFactory()->getModelSalesOrder();
        $order->load($transactionStatus->getOrderId());

        $order->setPayoneDunningStatus($transactionStatus->getReminderlevel());
        $order->save();
    }
}