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
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Config
    extends Payone_Core_Helper_Abstract
{
    const CONFIG_KEY_CREDITRATING_SAMPLE_COUNTER = 'payone_creditrating_sample_counter';

    /**
     * @param int $storeId
     * @return bool|Payone_Core_Model_Config_Interface
     */
    public function getConfigStore($storeId = null)
    {
        $config = $this->getFactory()->getServiceInitializeConfig()->execute($storeId);
        return $config;
    }

    /**
     * @param int $storeId
     * @return Payone_Core_Model_Config_General
     */
    public function getConfigGeneral($storeId = null)
    {
        return $this->getConfigStore($storeId)->getGeneral();
    }

    /**
     * @param int $storeId
     * @return Payone_Core_Model_Config_Protect
     */
    public function getConfigProtect($storeId = null)
    {
        return $this->getConfigStore($storeId)->getProtect();
    }

    /**
     * @param int $storeId
     * @return Payone_Core_Model_Config_Misc
     */
    public function getConfigMisc($storeId = null)
    {
        return $this->getConfigStore($storeId)->getMisc();
    }

    /**
     * @param int $storeId
     * @return Payone_Core_Model_Config_Payment
     */
    public function getConfigPayment($storeId = null)
    {
        return $this->getConfigStore($storeId)->getPayment();
    }

    /**
     * @param int $id
     * @param int $storeId
     * @return bool|Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfigPaymentMethodById($id, $storeId = null)
    {
        $general = $this->getConfigGeneral($storeId);
        $defaultConfig = $general->getGlobal()->toArray();
        $invoiceTransmit = $general->getParameterInvoice()->getTransmitEnabled();

        // Add invoice_transmit to defaultConfig
        $defaultConfig['invoice_transmit'] = $invoiceTransmit;

        $config = $this->getFactory()->getModelDomainConfigPaymentMethod();
        $config->load($id);
        $config->loadMergedData();
        $config = $config->toConfigPayment($storeId, $defaultConfig);

        return $config;
    }

    /**
     * Retrieve the payment configuration that was used in an order
     *
     * @param Mage_Sales_Model_Order $order
     * @return Payone_Core_Model_Config_Payment
     */
    public function getConfigPaymentByOrder(Mage_Sales_Model_Order $order)
    {
        return $this->getConfigPayment($order->getStoreId());
    }

    /**
     * Retrieve the payment configuration that is used in a quote
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return Payone_Core_Model_Config_Payment
     */
    public function getConfigPaymentByQuote(Mage_Sales_Model_Quote $quote)
    {
        return $this->getConfigPayment($quote->getStoreId());
    }

    /**
     * Retrieve the paymentMethod configuration that was used in an order
     *
     * @param Mage_Sales_Model_Order $order
     * @return bool|Payone_Core_Model_Config_Payment_Method_Interface
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    public function getConfigPaymentMethodByOrder(Mage_Sales_Model_Order $order)
    {
        $configId = $order->getPayment()->getData('payone_config_payment_method_id');
        if (!$configId) {
            $message = 'Payment method configuration with id "' . $configId . '" not found.';
            throw new Payone_Core_Exception_PaymentMethodConfigNotFound($message);
        }

        $config = $this->getConfigPaymentMethodById($configId, $order->getStoreId());
        return $config;
    }

    /**
     * Retrieve the paymentMethod configuration that is used in a quote
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    public function getConfigPaymentMethodByQuote(Mage_Sales_Model_Quote $quote)
    {
        $configId = $quote->getPayment()->getData('payone_config_payment_method_id');
        if (!$configId) {
            $message = 'Payment method configuration with id "' . $configId . '" not found.';
            throw new Payone_Core_Exception_PaymentMethodConfigNotFound($message);
        }

        $config = $this->getConfigPaymentMethodById($configId, $quote->getStoreId());
        return $config;
    }

    /**
     * Retrieve the paymentMethod configuration that is used in a quote
     *
     * @param string $method
     * @param Mage_Sales_Model_Quote $quote
     * @param int $iStoreId
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    public function getConfigPaymentMethodForQuote($method, Mage_Sales_Model_Quote $quote, $iStoreId = null)
    {
        if($iStoreId === null) {
            $iStoreId = $quote->getStoreId();
        }

        $configPayment = $this->getConfigPayment($iStoreId);
        $config = $configPayment->getMethodForQuote($method, $quote);
        return $config;
    }

    public function getShippingTaxClassId($storeId)
    {
        return $this->getStoreConfig(Mage_Tax_Model_Config::CONFIG_XML_PATH_SHIPPING_TAX_CLASS, $storeId);
    }

    /**
     * Retrieve the creditrating sample counter from config
     *
     * @param int $storeId
     * @return int
     */
    public function getCreditratingSampleCounter($storeId)
    {
        $counter = $this->getStoreConfig(self::CONFIG_KEY_CREDITRATING_SAMPLE_COUNTER, $storeId);
        if (empty($counter) or !is_numeric($counter)) {
            $counter = 0;
        }

        return $counter;
    }


    /**
     * Store new value for creditrating sample counter in config
     * @param $count
     * @param $storeId
     */
    public function setCreditratingSampleCounter($count, $storeId)
    {
        Mage::getConfig()->saveConfig(self::CONFIG_KEY_CREDITRATING_SAMPLE_COUNTER, $count, 'stores', $storeId);
    }

    /**
     * Increment creditrating sample counter in config
     *
     * @param $storeId
     * @return int Returns the new counter value
     */
    public function incrementCreditratingSampleCounter($storeId)
    {
        $counter = $this->getCreditratingSampleCounter($storeId);

        if (empty($counter) or !is_numeric($counter)) {
            $counter = 0;
        }

        $counter += 1;

        $this->setCreditratingSampleCounter($counter, $storeId);

        return $counter;
    }

    /**
     *
     * @param string $path
     * @param int|null $storeId
     * @return mixed
     */
    public function getStoreConfig($path, $storeId = null)
    {
        return Mage::getStoreConfig($path, $storeId);
    }

    /**
     *
     * @param string $path
     * @param int $storeId
     * @return mixed
     */
    /**
     * @param string $path
     * @param int|null $storeId
     * @return bool
     */
    public function getStoreConfigFlag($path, $storeId = null)
    {
        return Mage::getStoreConfigFlag($path, $storeId);
    }
}