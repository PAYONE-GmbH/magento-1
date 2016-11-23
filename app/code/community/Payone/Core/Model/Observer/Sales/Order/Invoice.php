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
 * @author          Christian Walter <info@noovias.com>
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
class Payone_Core_Model_Observer_Sales_Order_Invoice
    extends Payone_Core_Model_Observer_Abstract
{
    /** @var Mage_Sales_Model_Order_Invoice */
    protected $invoice = null;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function prepareInvoice(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $this->invoice = $event->getInvoice();
        if($this->getHelperRegistry()->registry('current_invoice') instanceof Mage_Sales_Model_Order_Invoice)
        {
            Mage::unregister('current_invoice');
        }

        $this->getHelperRegistry()->register('current_invoice', $event->getInvoice());
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function prepareSequencenumber(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var $request Payone_Api_Request_Capture */
        $request = $event->getRequest();
        $this->invoice->setPayoneSequencenumber($request->getSequencenumber());
    }

    /**
     * @return Payone_Core_Helper_Registry
     */
    protected function getHelperRegistry()
    {
        return $this->getFactory()->helperRegistry();
    }
}