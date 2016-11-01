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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Config_Payment extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var array
     */
    protected $methods = array();

    /**
     * Retrieve Config by Id.
     * Returns false if id does not exist.
     *
     * @param $configId
     * @return bool|Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getMethodById($configId)
    {
        if (array_key_exists($configId, $this->methods)) {
            return $this->methods[$configId];
        }
        else {
            return false;
        }
    }

    /**
     * @param string $type
     * @param Mage_Sales_Model_Quote $quote
     * @return bool
     */
    public function isAvailable($type, Mage_Sales_Model_Quote $quote = null)
    {
        if (is_null($quote)) {
            $methods = $this->getMethodsByType($type);
        }
        else {
            $methods = $this->getMethodsForQuote($type, $quote);
        }

        if (count($methods) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check if Method can be used in Country
     *
     * @param string $type
     * @param string $country
     * @return bool
     */
    public function canUseForCountry($type, $country)
    {
        $canUse = false;

        $availableMethods = $this->getMethodsForCountry($type, $country);

        if (count($availableMethods) > 0) {
            $canUse = true;
        }

        return $canUse;
    }

    /**
     * @param string $type
     * @param Mage_Sales_Model_Quote $quote
     * @return mixed|null|Payone_Core_Model_Config_Payment_Method_Interface
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    public function getMethodForQuote($type, Mage_Sales_Model_Quote $quote)
    {
        $methods = $this->getMethodsForQuote($type, $quote);

        if (count($methods) == 0) {
            $message = 'Payment Method Configuration could not be found for ' . $type;
            throw new Payone_Core_Exception_PaymentMethodConfigNotFound($message);
        }

        /** @var $config Payone_Core_Model_Config_Payment_Method_Interface */
        $config = array_shift($methods); // Use the first matching method config

        return $config;
    }

    /**
     * Get Available Methods for Type by Quote
     *
     * @param $type
     * @param Mage_Sales_Model_Quote $quote
     * @return array
     */
    public function getMethodsForQuote($type, Mage_Sales_Model_Quote $quote)
    {
        $country = $quote->getBillingAddress()->getCountry();
        $quoteTotal = $quote->getGrandTotal();

        $methodsForCountry = $this->getMethodsForCountry($type, $country);

        $methods = array();
        foreach ($methodsForCountry as $key => $method) {
            /** @var $method Payone_Core_Model_Config_Payment_Method_Interface */
            $maxOrderTotal = $method->getMaxOrderTotal();
            $minOrderTotal = $method->getMinOrderTotal();

            if (!empty($maxOrderTotal) and $maxOrderTotal < $quoteTotal) {
                continue; // quote total too high.
            }

            if (!empty($minOrderTotal) and $minOrderTotal > $quoteTotal) {
                continue; // quote total is too low.
            }

            $methods[] = $method;
        }

        return $methods;
    }

    /**
     * Retrieve all Available Methods by MethodType
     *
     * @param string $type
     * @param string $country
     * @return array
     */
    public function getMethodsForCountry($type, $country)
    {
        $methodsbyType = $this->getMethodsByType($type);

        $availableMethods = array();
        foreach ($methodsbyType as $key => $method) {
            /** @var $method Payone_Core_Model_Config_Payment_Method_Interface */
            if (!$method->canUseForCountry($country)) {
                continue;
            }

            $availableMethods[] = $method;
        }

        return $availableMethods;
    }

    /**
     * Retrieve all Available Methods by MethodType
     *
     * @param string $type
     * @return array
     */
    public function getMethodsByType($type)
    {
        $methods = array();
        foreach ($this->getAvailableMethods() as $key => $method) {
            /** @var $method Payone_Core_Model_Config_Payment_Method_Interface */
            if ($method->getEnabled() and $method->getCode() == $type) {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * Get Available Methods, only returns not deleted methods
     *
     * @return array
     */
    public function getAvailableMethods()
    {
        $methods = array();
        foreach ($this->methods as $key => $method) {
            /** @var $method Payone_Core_Model_Config_Payment_Method_Interface */
            if(!$method->getIsDeleted()){
                $methods[$key] = $method;
                if($method->hasParent())
                {
                    unset($methods[$method->getParent()]);
                }
            }
        }

        return $methods;
    }

    /**
     * @return array
     */
    public function getAllMethods()
    {
        return $this->methods;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     */
    public function addMethod(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $this->methods[$config->getId()] = $config;
    }

    /**
     * @param array $methods
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

}
