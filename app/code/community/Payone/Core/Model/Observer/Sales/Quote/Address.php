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
 * @author          Matthias Walter <info@noovias.com>
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
class Payone_Core_Model_Observer_Sales_Quote_Address
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function validateAfter(Varien_Event_Observer $observer)
    {
        /** @var $quote Mage_Sales_Model_Quote */
        /** @var $quoteAddress Payone_Core_Model_Sales_Quote_Address */
        /** @var $errors Varien_Object */
        $event = $observer->getEvent();
        $quote = $event->getQuote();
        $quoteAddress = $event->getQuoteAddress();
        $errors = $event->getErrors();
        $useForShipping = (bool) $event->getUseForShipping();

        $fullActionName = $event->getFullActionName();

        if ($this->isEnabledForAction($fullActionName)) {
            $config = $this->helperConfig()->getConfigProtect($quote->getStoreId())->getAddressCheck();
            if (!$config->getEnabled()) {
                return;
            }


            $addressType = $quoteAddress->getAddressType();
            if ($this->mustCheckAddress($addressType, $config, $quote, $useForShipping))
            {
                // Inject into QuoteAdress for later use in mapper
                $quoteAddress->setUseForShipping($useForShipping);

                // Config says we must perform an addresscheck:
                $service = $this->getFactory()->getServiceVerificationAddressCheck($config);

                $service->execute($quoteAddress, $errors);
            }
        }
    }

    /**
     * @param $fullActionName
     * @return bool
     */
    protected function isEnabledForAction($fullActionName)
    {
        return in_array($fullActionName, $this->getEnabledActions());
    }

    /**
     * @return array
     */
    protected function getEnabledActions()
    {
        $actions = array(
            'checkout/onepage/saveBilling',
            'checkout/onepage/saveShipping',
        );
        return $actions;
    }

    protected function _alreadyCheckedAndChangeWasDenied($sType) {
        $blReturn = (bool)Mage::app()->getRequest()->getPost($sType.'_change_denied', false);
        return $blReturn;
    }
    
    /**
     * checks if an addresscheck must be performed
     *
     * @param $addressType
     * @param Payone_Core_Model_Config_Protect_AddressCheck $config
     * @param Mage_Sales_Model_Quote $quote
     * @param $useForShipping
     * @return bool
     */
    protected function mustCheckAddress($addressType, Payone_Core_Model_Config_Protect_AddressCheck $config, Mage_Sales_Model_Quote $quote, $useForShipping)
    {
        // check if address is shipping-address an shipping-address has to be checked
        if ($addressType === 'shipping' && !$this->_alreadyCheckedAndChangeWasDenied($addressType) && $config->mustCheckShipping()) {
            return true;
        }
        // check if address is billing-address
        if ($addressType === 'billing' && !$this->_alreadyCheckedAndChangeWasDenied($addressType)) {
            // check if billing-address has to be checked
            if ($config->mustCheckBilling()) {
                return true;
            }
            // check if billing-address is used for shipping address and shipping-address has to be checked
            if ($useForShipping === true and $config->mustCheckShipping() and !$quote->isVirtual()) {
                return true;
            }
            // check if billing-address has to be checked for virtual order
            if ($quote->isVirtual() and $config->mustCheckBillingForVirtualOrder()) {
                return true;
            }
        }
        return false;
    }
}