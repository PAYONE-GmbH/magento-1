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
class Payone_Core_Model_Service_InitializeConfig
    extends Payone_Core_Model_Service_Abstract
{
    const CONFIG_CACHE_ID = 'store_%s_payone_config_cache';
    const CONIG_REGISTRY_KEY = 'payone_core_config_%s';
    const CACHE_TAG = 'payone_config';

    const CONFIG_SECTION_PREFIX = 'payone_';
    const CONFIG_SECTION_GENERAL = 'general';
    const CONFIG_SECTION_PAYMENT = 'payment';
    const CONFIG_SECTION_PROTECT = 'protect';
    const CONFIG_SECTION_MISC = 'misc';

    /** @var int */
    protected $storeId = null;

    /**
     * Generates the Configuration Object for Payone Settings
     *
     * Each StoreId will have an Object respresenting its Configuration
     * The Config Object will be cached
     *
     * @param int $storeId
     * @return Payone_Core_Model_Config_Interface
     */
    public function execute($storeId = null)
    {
        $this->setStoreId($storeId);

        $helperRegistry = $this->helperRegistry();
        $registryKey = $this->getConfigRegistryKey($storeId);
        $config = $helperRegistry->registry($registryKey);
        if ($config instanceof Payone_Core_Model_Config_Interface) {
            return $config;
        }

        $config = $this->loadFromCache();
        if ($config instanceof Payone_Core_Model_Config_Interface) {
            $helperRegistry->register($registryKey, $config);
            return $config;
        }

        /** @var $config Payone_Core_Model_Config */
        $config = $this->getConfigModel();

        // Store Id
        $config->setStoreId($storeId);

        // General
        $general = $this->initConfigBySection(self::CONFIG_SECTION_GENERAL);
        $config->setGeneral($general);

        // Payment
        $payment = $this->initConfigPayment($general);
        $config->setPayment($payment);

        // Protect
        $protect = $this->initConfigBySection(self::CONFIG_SECTION_PROTECT);
        $config->setProtect($protect);

        // Misc
        $misc = $this->initConfigBySection(self::CONFIG_SECTION_MISC);
        $config->setMisc($misc);

        // Caching
        $this->saveToCache($config);

        return $config;
    }

    /**
     * @param $sectionKey
     * @return null|Payone_Core_Model_Config_AreaAbstract
     */
    protected function initConfigBySection($sectionKey)
    {
        $configSection = $this->getConfigModel($sectionKey);

        $_configFields = Mage::getSingleton('adminhtml/config');
        /**
         * @var $section Mage_Core_Model_Config_Element
         */
        $section = $_configFields->getSection(self::CONFIG_SECTION_PREFIX . $sectionKey);

        /**
         * @var $groups Mage_Core_Model_Config_Element
         */
        $groups = $section->groups;

        foreach ($groups->children() as $groupKey => $group) {
            /**
             * @var $group Mage_Core_Model_Config_Element
             */
            if (!property_exists($group, 'fields')) {
                continue;
            }

            // we want a clean directory structure
            $configGroup = $this->initConfigByGroup($sectionKey, $groupKey);
            $configSection->init(array($groupKey => $configGroup));
        }

        return $configSection;
    }

    /**
     * @param string $sectionKey
     * @param string $groupKey
     * @return null|Payone_Core_Model_Config_AreaAbstract
     */
    protected function initConfigByGroup($sectionKey, $groupKey)
    {
        $classKey = $sectionKey . '_' . uc_words($groupKey, '');

        $data = $this->getStoreConfig(self::CONFIG_SECTION_PREFIX . $sectionKey . '/' . $groupKey);

        $config = $this->getConfigModel($classKey);
        if ($config === null) {
            return null;
        }

        if ($data !== null) {
            $config->init($data);
        }

        return $config;
    }

    /**
     * @param Payone_Core_Model_Config_General $general
     * @return Payone_Core_Model_Config_Payment
     */
    protected function initConfigPayment(Payone_Core_Model_Config_General $general)
    {
        $global = $general->getGlobal();
        $defaultConfig = $global->toArray();
        $invoiceTransmit = $general->getParameterInvoice()->getTransmitEnabled();

        // Add invoice_transmit to defaultConfig
        $defaultConfig['invoice_transmit'] = $invoiceTransmit;

        /** @var $payment Payone_Core_Model_Config_Payment */
        $payment = $this->getConfigModel(self::CONFIG_SECTION_PAYMENT);

        /** @var $methodConfigCollection Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection */
        $methodConfigCollection = $this->getFactory()->getModelDomainConfigPaymentMethod()->getCollection();
        $methodConfigCollection->getCollectionByStoreId($this->getStoreId(), true);
        $methodConfigCollection->addSortOrder();

        foreach ($methodConfigCollection as $methodConfig) {
            /** @var $methodConfig Payone_Core_Model_Domain_Config_PaymentMethod */
            $configMethod = $methodConfig->toConfigPayment($this->getStoreId(), $defaultConfig);

            $payment->addMethod($configMethod);
        }

        return $payment;
    }


    /**
     *
     * @param string $path
     * @return mixed
     */
    protected function getStoreConfig($path)
    {
        return $this->helperConfig()->getStoreConfig($path, $this->getStoreId());
    }

    /**
     * @param string $key
     * @return Payone_Core_Model_Config_AreaAbstract
     */
    protected function getConfigModel($key = '')
    {
        if ($key != '') {
            $key = '_' . $key;
        }

        $className = 'payone_core/config' . $key;
        return Mage::getModel($className);
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getConfigRegistryKey($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->getStoreId();
        }

        if ($storeId === null) { // storeId = null is translated to *current store id* by magento
            $storeId = Mage::app()->getStore()->getId();
        }

        $cacheId = sprintf(self::CONIG_REGISTRY_KEY, $storeId);
        return $cacheId;
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getConfigCacheId($storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->getStoreId();
        }

        if ($storeId === null) { // storeId = null is translated to *current store id* by magento
            $storeId = Mage::app()->getStore()->getId();
        }

        $cacheId = sprintf(self::CONFIG_CACHE_ID, $storeId);
        return $cacheId;
    }

    /**
     * @todo move Mage:: calls to new Helper_Cache?
     * @return Payone_Core_Model_Config_Interface|null
     */
    protected function loadFromCache()
    {
        // Check cache and if its there, return stored config:
        if (Mage::app()->useCache('config')) {
            $cacheId = $this->getConfigCacheId();
            $data = Mage::app()->loadCache($cacheId);

            if ($data) {
                $config = unserialize($data);
                return $config;
            }
        }

        return NULL;
    }

    /**
     * @todo move Cache handling to Helper_Cache_Config
     *
     * @param Payone_Core_Model_Config_Interface $config
     */
    protected function saveToCache(Payone_Core_Model_Config_Interface $config)
    {
        // Cache Config Object
        if (Mage::app()->useCache('config')) {
            $cacheId = $this->getConfigCacheId();
            Mage::app()->saveCache(
                serialize($config),
                $cacheId,
                array(
                    self::CACHE_TAG,
                    Mage_Core_Model_Store::CACHE_TAG,
                    Mage_Core_Model_Config::CACHE_TAG
                )
            );
        }
    }

    /**
     * @param int $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

}