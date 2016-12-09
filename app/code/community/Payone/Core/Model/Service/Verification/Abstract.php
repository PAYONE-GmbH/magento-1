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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_Service_Verification_Abstract
    extends Payone_Core_Model_Service_Abstract
{
    protected $prefix = 'abstract';

    /**
     * @param Mage_Customer_Model_Address_Abstract $address
     * @return bool
     */
    protected function addressHasChanged(Mage_Customer_Model_Address_Abstract $address)
    {
        $hashSaved = $address->getData($this->prefix . '_hash');
        $hashNew = $this->helper()->createAddressHash($address);

        if ($hashNew === $hashSaved) {
            return false;
        }
        else {
            return true;
        }
    }


    /**
     * Retrieve saved score if:
     * - It exists
     * - It is not expired
     * - The address has not changed
     *
     * @param Mage_Customer_Model_Address_Abstract $address
     * @param int $validityInSeconds maximum validity period / lifetime of the saved score
     *
     * @return bool|string score (G,Y,R) on success,  false otherwise
     */
    protected function getSavedScore(Mage_Customer_Model_Address_Abstract $address, $validityInSeconds)
    {
        // 1. check address:
        $savedScore = $this->checkAddress($address, $validityInSeconds);

        if ($savedScore) {
            return $savedScore;
        }

        // Nothing saved on address, see if there is a saved customer address that has the value:
        $customerAddressId = $address->getCustomerAddressId();
        if (empty($customerAddressId)) {
            return false;
        }

        $customerAddress = $this->getFactory()->getModelCustomerAddress();
        $customerAddress->load($customerAddressId);

        // Verify the addresses are the same (customer address might have changed since last check), and that the saved hash is still valid for it´ address:
        $helper = $this->helper();
        $customerAddressHash = $helper->createAddressHash($customerAddress);
        $currentAddressHash = $helper->createAddressHash($address);
        if ($customerAddressHash !== $currentAddressHash) {
            return false;
        }

        // Run the check on the customer address:
        return $this->checkAddress($customerAddress, $validityInSeconds);
    }

    protected function checkAddress(Mage_Customer_Model_Address_Abstract $address, $validityInSeconds)
    {
        if (!$address->hasData()) {
            return false;
        }

        $savedDate = $address->getData($this->prefix . '_date');
        $savedScore = $address->getData($this->prefix . '_score');
        if (empty($savedDate) or empty($savedScore)) {
            return false;
        }


        if ($this->addressHasChanged($address)) {
            return false;
        }

        // Verify the validity period is not expired:
        if (!$this->helper()->isDateStillValid($savedDate, $validityInSeconds)) {
            return false;
        }

        $address->setData($this->prefix . '_score', $savedScore);
        return $savedScore;
    }
    

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return bool
     */
    protected function isRequiredForQuote(Mage_Sales_Model_Quote $quote)
    {
        $config = $this->getConfig();
        $quoteTotal = $quote->getSubtotal();

        /** @var $method Payone_Core_Model_Config_Payment_Method_Interface */
        $maxOrderTotal = $config->getMaxOrderTotal();
        $minOrderTotal = $config->getMinOrderTotal();

        if (!empty($maxOrderTotal) and $maxOrderTotal < $quoteTotal) {
            return false; // quote total too high.
        }

        if (!empty($minOrderTotal) and $minOrderTotal > $quoteTotal) {
            return false; // quote total is too low.
        }

        return true;
    }
    
}