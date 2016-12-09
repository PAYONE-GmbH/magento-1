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
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */
class Payone_Core_Model_Domain_Customer
    extends Mage_Core_Model_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/customer');
    }



    /**
     * @param int $customerId
     * @param string $paymentMethodCode
     * @return Payone_Core_Model_Domain_Customer
     */
    public function loadByCustomerIdPaymentCode($customerId, $paymentMethodCode)
    {
        $collection = Mage::getModel('payone_core/domain_customer')->getCollection();
        $collection->addFieldToFilter('customer_id', array('eq'=>$customerId));
        $collection->addFieldToFilter('code', array('eq'=>$paymentMethodCode));
        foreach($collection->load() as $item) {
            return $item;
        }

        return $this;
    }

    /**
     * @param int $customerId
     * @return string
     */
    public function getLastPaymentCode($customerId)
    {
        $collection = Mage::getModel('payone_core/domain_customer')->getCollection();
        $collection->addFieldToFilter('customer_id', array('eq'=>$customerId));
        foreach($collection->load() as $item) {
            return $item->getCode();
        }

        return '';
    }

    /**
     * @param array $customerData
     * @return Payone_Core_Model_Domain_Customer
     */
    public function setCustomerData($customerData)
    {
        $plain_customer_data = Mage::helper('core')->jsonEncode($customerData);
        $this->customer_data = Mage::helper('core')->encrypt($plain_customer_data);
//        $this->customer_data = $plain_customer_data;
        return $this;
    }

    /**
     * @param string $key
     * @return array
     */
    public function getCustomerData($key = null)
    {
        $plain_customer_data = Mage::helper('core')->decrypt($this->customer_data);
//        $plain_customer_data = $this->customer_data;
        $result = Mage::helper('core')->jsonDecode($plain_customer_data);
        if(!is_null($key) && is_array($result) && isset($result[$key])) {
            return $result[$key];
        }

        return $result;
    }
}