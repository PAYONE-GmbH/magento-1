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
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Domain_Resource_Protocol_TransactionStatus_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/domain_protocol_transactionStatus');
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    public function getByOrder(Mage_Sales_Model_Order $order)
    {
        $this->addFieldToFilter('order_id', $order->getId());
    }

    /**
     * @param Payone_Core_Model_Domain_Transaction $transaction
     * @return void
     */
    public function getByTransaction(Payone_Core_Model_Domain_Transaction $transaction)
    {
        $this->addFieldToFilter('txid', $transaction->getTxid());
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_TransactionStatus
     */
    public function getNextPending()
    {
        $status = Payone_Core_Model_Domain_Protocol_TransactionStatus::STATUS_PENDING;

        $this->clear();
        $this->addFieldToFilter('processing_status', $status);
        $this->setOrder('id', 'ASC');
        $this->getSelect()->limit(1);

        $this->load();

        foreach ($this as $data) {
            return $data;
        }

        return null;
    }

}