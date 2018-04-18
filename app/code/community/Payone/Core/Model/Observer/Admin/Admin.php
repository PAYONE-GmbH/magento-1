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
 * @subpackage      Service
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Model_Observer_Admin_Admin extends Payone_Core_Model_Observer_Abstract
{
    public function addOrderCancelConfirmationBlock(Varien_Event_Observer $observer)
    {
        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $observer->getData('action')->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        if ($controller !== 'sales_order' || $action !== 'view') {
            return true;
        }

        /** @var Mage_Core_Model_Layout $layout */
        $layout = $observer->getData('layout');
        $session = Mage::getModel('payone_core/session');

        $confirmPrompt = $session->getData('payment_cancel_should_confirm', true);
        if ($confirmPrompt) {
            $block = $layout->createBlock('payone_core/adminhtml_sales_order_view_orderCancelConfirmation');
            $layout->getBlock('content')->append($block, 'payone_core_adminhtml_sales_order_view_ordercancelconfirmation');
        }
        else {
            $orderId = $request->getParam('order_id');
            $session->unsetData('payment_processing_capture_zero_'.$orderId);
        }

        return true;
    }
}
