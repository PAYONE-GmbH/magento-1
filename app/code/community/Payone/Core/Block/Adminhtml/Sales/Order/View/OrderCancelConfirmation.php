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
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */
class Payone_Core_Block_Adminhtml_Sales_Order_View_OrderCancelConfirmation
    extends Mage_Adminhtml_Block_Widget
{
    /**
     *
     */
    public function _construct()
    {
        $this->setTemplate("payone/core/sales/order/view/order_cancel_confirmation.phtml");
    }

    /**
     * Returns the action url to cancel an order
     *
     * @return string
     */
    public function getCancelUrl()
    {
        $params['order_id'] = $this->getOrder()->getId();
        return $this->getUrl('*/*/cancel', $params);
    }

    /**
     * Returns the action url to view an order
     *
     * @return string
     */
    public function getOrderViewUrl()
    {
        $params['order_id'] = $this->getOrder()->getId();
        return $this->getUrl('*/*/view', $params);
    }

    /**
     * Retrieve order model object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('sales_order');
    }
}
