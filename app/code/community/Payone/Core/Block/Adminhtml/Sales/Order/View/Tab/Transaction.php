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
 * @subpackage      Adminhtml_Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_Sales_Order_View_Tab_Transaction
    extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->helperPayoneCore()->__('Transaction');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    public function getTabClass()
    {
        return 'payone-widget-tab';
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return '<span class="payone-tab">' . Mage::helper('payone_core')->__('Transaction') . '</span>';
    }

    /**
     * Converts timezone from "GMT" to locale timezone
     * @param $string
     * @return string|null
     */
    public function getLocaleDatetime($string)
    {
        $helper = $this->helperPayoneCore();
        return $helper->getLocaleDatetime($string);
    }

    /**
     * @return Payone_Core_Model_Domain_Transaction
     */
    public function getTransaction()
    {
        if (!Mage::registry('payone_core_protocol_transaction')) {
            $payment = $this->getOrder()->getPayment();

            /** @var $transactionStatusCollection Payone_Core_Model_Domain_Transaction */
            $transaction = $this->getPayoneFactory()->getModelTransaction();
            $transaction->loadByPayment($payment);
            Mage::register('payone_core_protocol_transaction', $transaction);
        }
        return Mage::registry('payone_core_protocol_transaction');
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getPayoneFactory(){
        return $this->helperPayoneCore()->getFactory();
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayoneCore()
    {
        return Mage::helper('payone_core');
    }
}