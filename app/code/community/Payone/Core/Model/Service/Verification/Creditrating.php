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
class Payone_Core_Model_Service_Verification_Creditrating
    extends Payone_Core_Model_Service_Verification_Abstract
{
    protected $prefix = 'payone_protect';

    /** @var Payone_Api_Service_Verification_Consumerscore */
    protected $serviceApiConsumerScore = null;

    /** @var Payone_Core_Model_Mapper_ApiRequest_Verification_Creditrating */
    protected $mapper = null;

    /** @var Payone_Core_Model_Config_Protect_Creditrating */
    protected $config = null;


    /** @var Payone_Core_Model_Handler_Verification_Creditrating */
    protected $handler = null;

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return array|bool array of payment method codes, or true if check not required
     */
    public function execute(Mage_Sales_Model_Quote $quote)
    {
        $config = $this->getConfig();
        if (!$config->getEnabled()) {
            return true;
        }

        if ($this->isRequiredForQuote($quote) === false) {
            return true;
        }

        if (!$this->haveToTakeSample($quote->getStoreId())) {
            return true;
        }

        $address = $quote->getBillingAddress();
        $handler = $this->getHandler();
        $handler->setAddress($address);
        $configStore = $this->helperConfig()->getConfigStore($quote->getStoreId());
        $handler->setConfigStore($configStore);

        $savedProtectScore = $this->getSavedScore($address, $config->getResultLifetimeInSeconds());
        if ($savedProtectScore) {
            // Valid, saved score exists, we can skip the API request:
            $address->setPayoneProtectScore($savedProtectScore);
            return $savedProtectScore;
        }

        $request = $this->getMapper()->mapFromAddress($address);


        try {
            $response = $this->getServiceApiConsumerScore()->score($request);

            $result = $handler->handle($response);
        }
        catch (Exception $ex) {
            $result = $handler->handleException($ex);
        }
        return $result;

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

    /**
     * @param Payone_Api_Service_Verification_Consumerscore $serviceApiConsumerScore
     */
    public function setServiceApiConsumerScore(Payone_Api_Service_Verification_Consumerscore $serviceApiConsumerScore)
    {
        $this->serviceApiConsumerScore = $serviceApiConsumerScore;
    }

    /**
     * @return Payone_Api_Service_Verification_Consumerscore
     */
    public function getServiceApiConsumerScore()
    {
        return $this->serviceApiConsumerScore;
    }

    /**
     * @param Payone_Core_Model_Mapper_ApiRequest_Verification_Creditrating $mapper
     */
    public function setMapper(Payone_Core_Model_Mapper_ApiRequest_Verification_Creditrating $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Payone_Core_Model_Mapper_ApiRequest_Verification_Creditrating
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param Payone_Core_Model_Handler_Verification_Creditrating $handler
     */
    public function setHandler(Payone_Core_Model_Handler_Verification_Creditrating $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Payone_Core_Model_Handler_Verification_Creditrating
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_Creditrating $config
     */
    public function setConfig(Payone_Core_Model_Config_Protect_Creditrating $config)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_Core_Model_Config_Protect_Creditrating
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Determine if a creditcard check has to be performed, via sample mode config.
     * Sample mode deactivated = We must perform every creditrating check.
     *
     * @param int $storeId
     * @return bool
     */
    protected function haveToTakeSample($storeId)
    {
        $config = $this->getConfig();
        $frequency = $config->getSampleModeFrequency();

        if (!$config->isSampleModeEnabled()
                or empty($frequency)
        ) {
            return true; // Sample mode disabled / badly configured. Check has to be performed.
        }

        $counter = $this->helperConfig()->getCreditratingSampleCounter($storeId);


        if ($counter % $frequency === 0) {
            return true;
        }
        return false;

    }
}