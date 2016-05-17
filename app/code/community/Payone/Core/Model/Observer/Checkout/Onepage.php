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
class Payone_Core_Model_Observer_Checkout_Onepage extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @var Varien_Object
     */
    protected $settings = null;

    /** @var array */
    protected $paymentData = array();

    /** @var Mage_Sales_Model_Quote */
    protected $quote = null;

    /**
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function verifyPayment(Varien_Event_Observer $observer)
    {
        $this->init($observer);
        /**
         * @var $quote Mage_Sales_Model_Quote
         */
        $quote = $observer->getEvent()->getQuote();
        /** @var $selectedMethod string */
        $selectedMethod = $observer->getEvent()->getSelectedMethod();

        // Check creditrating config:
        $configProtect = $this->helperConfig()->getConfigProtect($quote->getStoreId());
        $configCreditrating = $configProtect->getCreditrating();
        $configAddresscheck = $configProtect->getAddressCheck();
        if (!$configCreditrating->getEnabled()
            or !$configCreditrating->isIntegrationEventAfterPayment()
            or !$configCreditrating->isEnabledForMethod($selectedMethod)
        ) {
            return;
        }

        $isCreditratingAllowed = $this->isCreditratingAllowed();
        if ($configCreditrating->getAgreementEnabled() && $isCreditratingAllowed === false) {
            return;
        }

        $scores = array();
        if($configAddresscheck->getEnabled())
        {
            // get worst address-score and add to score array
            $worstAddressScore = $this->helperScore()->detectWorstAddressScoreByQuote($quote);
            array_push($scores,$worstAddressScore);
        }

        // Perform creditrating check:
        $serviceCreditrating = $this->getFactory()->getServiceVerificationCreditrating($configCreditrating);
        $worstCreditratingScore = $serviceCreditrating->execute($quote);
        array_push($scores,$worstCreditratingScore);

        $worstScore = $this->helperScore()->detectWorstScore($scores);
        $allowedMethods = $this->helperScore()->evaluate($worstScore,$quote->getStoreId());

        if ($allowedMethods === true) {
            $this->setSettingsHavetoFilterMethods(false);
            return;
        }

        // Check if selectedMethod is allowed
        if (!array_key_exists($selectedMethod, $allowedMethods)) {
            // set Filter Methods to trigger Payment Methods Block generation
            $this->setSettingsHavetoFilterMethods(true);
            $this->getSettingsAllowedMethods()->addData($allowedMethods);
        }
        else {
            // Selected Method is available no need to render Methods Block again
            $this->setSettingsHavetoFilterMethods(false);
        }

    }

    /**
     * @return bool
     */
    protected function isCreditratingAllowed()
    {
        $paymentData = $this->getPaymentData();
        if (!array_key_exists('payone_creditrating_agreement', $paymentData) ||
            (array_key_exists('payone_creditrating_agreement', $paymentData) && $paymentData['payone_creditrating_agreement'] !== '1')
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param int $value
     */
    protected function setSettingsHavetoFilterMethods($value)
    {
        $key = 'have_to_filter_methods';
        $this->getSettings()->setData($key, $value);
    }

    /**
     * @return Varien_Object
     */
    protected function getSettingsAllowedMethods()
    {
        return $this->getSettings()->getData('allowed_methods');
    }

    /**
     * @param Varien_Object $value
     * @return Varien_Object
     */
    protected function setSettingsAllowedMethods(Varien_Object $value)
    {
        return $this->getSettings()->setData('allowed_methods', $value);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    protected function init(Varien_Event_Observer $observer)
    {
        $this->setSettings($observer->getEvent()->getSettings());
        $this->setPaymentData($observer->getEvent()->getPaymentData());
        $this->setQuote($observer->getEvent()->getQuote());
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
     * @param array $paymentData
     */
    public function setPaymentData(array $paymentData)
    {
        $this->paymentData = $paymentData;
    }

    /**
     * @return array
     */
    public function getPaymentData()
    {
        return $this->paymentData;
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     */
    public function setQuote(Mage_Sales_Model_Quote $quote)
    {
        $this->quote = $quote;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->quote;
    }

}