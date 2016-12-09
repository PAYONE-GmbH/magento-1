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
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Checkout_Onepage_Payment_Additional extends Mage_Core_Block_Template
{
    /** @var Payone_Core_Model_Factory */
    private $factory = null;

    /**
     * @return bool
     */
    public function canShowAgreementMessage()
    {
        $config = $this->getConfigCreditrating();
        if($config->isEnabled() && $config->isAgreementEnabled() && $config->isIntegrationEventAfterPayment())
        {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getAgreementMessage()
    {
        $config = $this->getConfigCreditrating();
        return $config->getAgreementMessage();
    }

    /**
     * @return bool
     */
    public function canShowPaymentHintText()
    {
        $config = $this->getConfigCreditrating();
        if($config->isEnabled() && $config->isPaymentHintEnabled() && $config->isIntegrationEventAfterPayment())
        {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPaymentHintText()
    {
        $config = $this->getConfigCreditrating();
        return $config->getPaymentHintText();
    }

    /**
     * @return Payone_Core_Model_Config_Protect_Creditrating
     */
    protected function getConfigCreditrating()
    {
        /** @var $quote Mage_Sales_Model_Quote */
        $checkout = $this->getFactory()->getSingletonCheckoutSession();
        $quote = $checkout->getQuote();
        $helperConfig = $this->getHelperConfig();
        return $helperConfig->getConfigProtect($quote->getStoreId())->getCreditrating();
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function getHelperConfig()
    {
        return $this->getFactory()->helperConfig();
    }
    /**
     * @return Payone_Core_Model_Factory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }
}