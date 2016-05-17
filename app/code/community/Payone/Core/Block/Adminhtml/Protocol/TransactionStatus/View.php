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
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_Protocol_TransactionStatus_View
    extends Payone_Core_Block_Adminhtml_Widget_View_Container
{

    /**
     *
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'payone_core_transactionstatus_view';
        $this->_controller = 'adminhtml_protocol_transactionStatus';

        $this->_addButton('forward_to_order', array(
            'label' => Mage::helper('payone_core')->__('Go to Order'),
            'onclick' => 'setLocation(\'' . $this->getForwardToOrderUrl() . '\')',
            'class' => 'go',
        ), 0, 250);

        $this->_addButton('forward_to_transaction', array(
            'label' => Mage::helper('payone_core')->__('Go to Transaction'),
            'onclick' => 'setLocation(\'' . $this->getForwardToTransactionUrl() . '\')',
            'class' => 'go',
        ), 0, 500);

        parent::__construct();

        $this->setId('transactionstatus_view');

        $this->_removeButton('edit');
    }

    /**
     * @return string
     */
    public function getForwardToOrderUrl()
    {
        $params = array('order_id' => $this->getTransactionStatus()->getOrderId());
        return $this->getUrl('adminhtml/sales_order/view', $params);
    }

    /**
     * @return string
     */
    public function getForwardToTransactionUrl()
    {
        $transaction = $this->getTransaction();
        $params = array('id' => $transaction->getId());
        return $this->getUrl('*/adminhtml_transaction/view', $params);
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        $transactionStatus = $this->getTransactionStatus();
        $text = Mage::helper('payone_core')->__(
            'Protocol - TransactionStatus #%s | %s',
            $transactionStatus->getId(),
            $transactionStatus->getTxid()
        );
        return $text;
    }

    /**
     * @return Payone_Core_Block_Adminhtml_Protocol_TransactionStatus_View
     */
    protected function _prepareLayout()
    {
        foreach ($this->_buttons as $level => $buttons) {
            foreach ($buttons as $id => $data) {
                $childId = $this->_prepareButtonBlockId($id);
                $this->_addButtonChildBlock($childId);
            }
        }
        $this->setChild('plane',
            $this->getLayout()->createBlock(
                'payone_core/adminhtml_protocol_transactionStatus_view_plane',
                'payone_core_adminhtml_protocol_transactionstatus_view_plane'
            )
        );
        return $this;
    }

    /**
     * @return Payone_Core_Model_Domain_Transaction
     */
    protected function getTransaction()
    {
        return Mage::registry('payone_core_protocol_transaction');
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_TransactionStatus
     */
    public function getTransactionStatus()
    {
        return Mage::registry('payone_core_protocol_transactionstatus');
    }

}