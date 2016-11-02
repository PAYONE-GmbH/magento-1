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
class Payone_Core_Model_Observer_TransactionStatus_OrderConfirmation
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @var Payone_Core_Model_Service_Sales_OrderConfirmation
     */
    protected $serviceOrderConfirmation = null;


    /** @var $order Mage_Sales_Model_Order */
    private $order = null;


    /** @var $transactionStatus Payone_Core_Model_Domain_Protocol_TransactionStatus */
    private $transactionStatus = null;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function onAppointed(Varien_Event_Observer $observer)
    {
        $this->initData($observer);


        $this->getServiceOrderConfirmation()->sendMail($this->order);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function initData(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

        /** @var $transactionStatus Payone_Core_Model_Domain_Protocol_TransactionStatus */
        $this->transactionStatus = $event->getTransactionStatus();

        $order = $this->getOrderByTransactionStatus($this->transactionStatus);
        $this->order = $order;
    }

    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     * @return Mage_Sales_Model_Order
     */
    protected function getOrderByTransactionStatus(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $order = $this->getFactory()->getModelSalesOrder();
        $order->load($transactionStatus->getOrderId());
        return $order;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_OrderConfirmation
     */
    public function getServiceOrderConfirmation()
    {
        if ($this->serviceOrderConfirmation === null) {
            $this->serviceOrderConfirmation = $this->getFactory()->getServiceSalesOrderConfirmation();
        }

        return $this->serviceOrderConfirmation;
    }


}