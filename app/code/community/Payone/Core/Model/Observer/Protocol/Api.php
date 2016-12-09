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
class Payone_Core_Model_Observer_Protocol_Api
    extends Payone_Core_Model_Observer_Abstract
{
    /** @var Payone_Core_Model_Domain_Protocol_Api */
    protected $protocolApi = null;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function prepareApi(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();
        $this->protocolApi = $event->getObject();
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function updateApiData(Varien_Event_Observer $observer)
    {
        if ($this->protocolApi != null) {
            /** @var $order Mage_Sales_Model_Order */
            $order = $observer->getEvent()->getOrder();

            $this->protocolApi->setStoreId($order->getStoreId());
            $this->protocolApi->setOrderId($order->getId());
            $this->protocolApi->save();
        }
    }
}