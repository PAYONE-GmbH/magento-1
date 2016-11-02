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
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_Handler_Verification_Abstract
    extends Payone_Core_Model_Handler_Abstract
    implements Payone_Core_Model_Handler_Verification_Interface
{
    /** @var Mage_Customer_Model_Address_Abstract */
    protected $address = null;

    /** @var Varien_Object */
    protected $errors = null;

    protected $prefix = 'abstract';

    /** @var Payone_Api_Request_Interface */
    protected $request = null;

    /**
     * Store date and score to customerAddress.
     * If the quoteAddress is not a saved customerAddress we do nothing
     * If it gets saved to the addressBook at end of checkout Magento´ convert functionality saves the data for us
     *
     * @param Mage_Customer_Model_Address_Abstract $address
     * @return bool
     */
    public function saveCustomerAddress(Mage_Customer_Model_Address_Abstract $address)
    {
        $customerAddressId = $address->getCustomerAddressId();
        if (empty($customerAddressId)) {
            return false;
        }

        $customerAddress = $this->getFactory()->getModelCustomerAddress();
        $customerAddress->load($customerAddressId);

        if (!$customerAddress->hasData()) {
            return false;
        }

        $customerAddress->setData($this->prefix . '_score', $address->getData($this->prefix . '_score'));
        $customerAddress->setData($this->prefix . '_date', $address->getData($this->prefix . '_date'));
        $customerAddress->setData($this->prefix . '_hash', $address->getData($this->prefix . '_hash'));

        $customerAddress->setCity($address->getCity());
        $customerAddress->setStreetFull($address->getStreetFull());
        $customerAddress->setZip($address->getZip());

        $customerAddress->save();
        return true;
    }

    /**
     * @param Mage_Customer_Model_Address_Abstract $address
     */
    public function setAddress(Mage_Customer_Model_Address_Abstract $address)
    {
        $this->address = $address;
    }

    /**
     * @return Mage_Customer_Model_Address_Abstract
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Varien_Object $errors
     */
    public function setErrors(Varien_Object $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return Varien_Object
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param Payone_Api_Request_Interface $request
     */
    public function setRequest(Payone_Api_Request_Interface $request)
    {
        $this->request = $request;
    }

    /**
     * @return Payone_Api_Request_Interface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
