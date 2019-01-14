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
class Payone_Core_Model_Observer_Checkout_Onepage_Payment_Methods
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @var Varien_Object
     */
    protected $settings = null;

    /**
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function getMethods(Varien_Event_Observer $observer)
    {
        $this->init($observer);
        /**
         * @var $quote Mage_Sales_Model_Quote
         */
        $quote = $observer->getEvent()->getQuote();
        if(!$quote->getCustomerIsGuest()) {
            try {
                $oCustomer = $quote->getCustomer();
                if($oCustomer && $oCustomer->getPayoneLastPaymentMethod()) {
                    $this->restoreLastPaymentMethod($oCustomer, $quote);
                }
            } catch (Exception $e) {
                //do nothing - getPayoneLastPaymentMethod method was just not accessible - no big deal

                // MAGE-400: Should deprecates MAGE-392 patch below
                // MAGE-392: Removing creditcard iframe method
                // if the method was last used, it will provoke troubles at checkout first time
                // workaround to unregister the last used payment method
                $oCustomer->setPayoneLastPaymentMethod('')->save();
                $quote->getPayment()->setMethod('');
                $quote->getPayment()->save();
            }
        }

        /** @var $fullActionName string */
        $fullActionName = $observer->getEvent()->getFullActionName();
        if ($fullActionName === 'checkout/onepage/index') {
            return;
        }

        $configProtect = $this->helperConfig()->getConfigProtect($quote->getStoreId());
        $configCreditrating = $configProtect->getCreditrating();
        $configAddresscheck = $configProtect->getAddressCheck();
        if (!$configCreditrating->getEnabled() && !$configAddresscheck->getEnabled()) {
            return;
        }

        $scores = array();
        if($configAddresscheck->getEnabled())
        {
            // get worst address-score and add to score array
            $worstAddressScore = $this->helperScore()->detectWorstAddressScoreByQuote($quote);
            array_push($scores, $worstAddressScore);
        }

        // check if config is enabled and event is before payment
        if($configCreditrating->getEnabled() && $configCreditrating->isIntegrationEventBeforePayment())
        {
            // get score for creditrating and add to score array
            $service = $this->getFactory()->getServiceVerificationCreditrating($configCreditrating);
            $worstCreditratingScore = $service->execute($quote);
            array_push($scores, $worstCreditratingScore);
        }

        // compare scores, select worst
        $worstScore = $this->helperScore()->detectWorstScore($scores);
        // evaluate score, load allowed payment methods
        $allowedPaymentMethods = $this->helperScore()->evaluate($worstScore, $quote->getStoreId());

        // Check not necessary
        if ($allowedPaymentMethods === true) {
            $this->setSettingsHavetoFilterMethods(false);
            return;
        }

        $this->setSettingsHavetoFilterMethods(true);
        $this->getSettingsAllowedMethods()->addData($allowedPaymentMethods);

    }

    /**
     * @param int $value
     */
    protected function setSettingsHavetoFilterMethods($value)
    {
        $key = Payone_Core_Block_Checkout_Onepage_Payment_Methods::RESULT_HAVE_TO_FILTER_METHODS;
        $this->getSettings()->setData($key, $value);
    }

    /**
     * @return Varien_Object
     */
    protected function getSettingsAllowedMethods()
    {
        $key = Payone_Core_Block_Checkout_Onepage_Payment_Methods::RESULT_ALLOWED_METHODS;
        return $this->getSettings()->getData($key);
    }

    /**
     * @param Varien_Object $value
     * @return Varien_Object
     */
    protected function setSettingsAllowedMethods(Varien_Object $value)
    {
        $key = Payone_Core_Block_Checkout_Onepage_Payment_Methods::RESULT_ALLOWED_METHODS;
        return $this->getSettings()->setData($key, $value);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function init(Varien_Event_Observer $observer)
    {
        $this->setSettings($observer->getEvent()->getSettings());
    }

    /**
     * @param Varien_Object $settings
     */
    public function setSettings(Varien_Object $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return Varien_Object
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @param Mage_Sales_Model_Quote$quote
     */
    private function restoreLastPaymentMethod(Mage_Customer_Model_Customer $customer, Mage_Sales_Model_Quote $quote)
    {
        $method = $customer->getPayoneLastPaymentMethod();
        $paymentMethodConfigId = $this->getPaymentMethodConfig($method, $quote);
        if ($method && $paymentMethodConfigId === 0) {
            $customer->setPayoneLastPaymentMethod('')->save();
            $method = null;
        }

        if ($method) {
            $quote->getPayment()
                ->setMethod($method)
                ->setPayoneConfigPaymentMethodId($paymentMethodConfigId); // MAGE-395: Add Payment method id to the quote

            // MAGE-395: Get and copy stored fields for that Customer with this Method
            /** @var Payone_Core_Model_Domain_Customer $payoneCustomer */
            $payoneCustomer = Mage::getModel('payone_core/domain_customer');
            $payoneCustomer = $payoneCustomer->loadByCustomerIdPaymentCode($customer->getId(), $method);
            $data = $payoneCustomer->getCustomerData();
            if (null !== $data) {
                foreach ($data as $key => $value) {
                    $quote->getPayment()->setData($key, $value);
                }
            }

            $quote->getPayment()->getMethodInstance();
        }
    }

    /**
     * @param string $method
     * @param Mage_Sales_Model_Quote $quote
     * @return int
     */
    private function getPaymentMethodConfig($method, $quote)
    {
        try{
            return Mage::helper('payone_core/config')->getConfigPaymentMethodForQuote(str_replace('payone_', '', $method), $quote)->getId();
        } catch (\Exception $ex) {
            return 0;
        }
    }

}
