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
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/domain_config_paymentMethod');
    }

    public function addItem(Varien_Object $item)
    {
        /** @var $item Payone_Core_Model_Domain_Config_PaymentMethod */
        $item->afterLoadPrepareData();
        return parent::addItem($item);
    }

    /**
     * if activated, the result will only return not deleted methods
     */
    public function filterExcludeDeleted()
    {
        $this->addFilterIsDeleted(0);
    }

    /**
     * 0 => deleted methods are excluded
     * 1 => deleted methods are included
     *
     * @param int $isDeleted
     */
    protected function addFilterIsDeleted($isDeleted = 0)
    {
        $this->addFieldToFilter('is_deleted', array('eq' => $isDeleted));
    }

    /**
     * if used, all paymentmethod-configs with scope 'default' and 'websites' were returned
     */
    public function filterExcludeStoresScope()
    {
        $this->addFilterScope('websites');
    }

    /**
     * @param $scope
     */
    protected function addFilterScope($scope)
    {
        // OR-Statement
        $this->addFieldToFilter('scope',
            array(
                array('attribute' => 'scope', 'eq' => 'default'),
                array('attribute' => 'scope', 'eq' => $scope)
            ));
    }

    /**
     * @param $store Mage_Core_Model_Store
     */
    public function filterByStore(Mage_Core_Model_Store $store)
    {
        $this->filterExcludeDeleted();
        $this->addFieldToFilter('scope_id', $store->getWebsiteId());
    }

    /**
     * @param string $order
     * @param string $orderDir
     */
    public function addSortOrder($order = 'sort_order', $orderDir = self::SORT_ORDER_ASC)
    {
        $this->addOrder($order, $orderDir);
    }

    /**
     * @param int $id
     * @param bool $includeDeleted
     * @return Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection
     */
    public function getCollectionByStoreId($id, $includeDeleted = false)
    {
        $store = Mage::app()->getStore($id);
        $websiteId = $store->getWebsiteId();

        $results = array();
        $globalCollection = $this->getCollectionByScopeId(0, 'default', $includeDeleted);

        // Cycle through default configs, there is one for each configures payment type.
        foreach ($globalCollection as $globalConfigId => $globalConfig) {
            $websiteConfigs = $this->getChildConfigs($globalConfigId, $websiteId, 'websites', $includeDeleted);
            if (count($websiteConfigs) < 1) {
                // No website scope config found, use global level config
                $results[$globalConfigId] = $globalConfig;
                continue;
            }

            $websiteConfig = $websiteConfigs->getFirstItem();

            $mergedConfig = $this->mergeConfigs($globalConfig, $websiteConfig);


            $websiteConfigId = $websiteConfig->getId();
            $storeConfigs = $this->getChildConfigs($websiteConfigId, $id, 'stores', $includeDeleted);
            if (count($storeConfigs) < 1) {
                // No storeView scope config found, use website level config
                $results[$websiteConfigId] = $mergedConfig;
                continue;
            }

            $storeConfig = $storeConfigs->getFirstItem();
            $finalConfig = $this->mergeConfigs($mergedConfig, $storeConfig);

            $results[$storeConfig->getId()] = $finalConfig;
        }

        $this->resetData();
        foreach ($results as $config) {
            $this->addItem($config);
        }
        $this->_isCollectionLoaded = true;
        return $this;
    }

    /**
     * @param int $id
     * @param bool $includeDeleted
     * @return Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection
     */
    public function getCollectionByWebsiteId($id, $includeDeleted = false)
    {
        $results = array();
        $globalCollection = $this->getCollectionByScopeId(0, 'default', $includeDeleted);

        // Cycle through default configs, there is one for each configures payment type.
        foreach ($globalCollection as $globalConfigId => $globalConfig) {
            $websiteConfigs = $this->getChildConfigs($globalConfigId, $id, 'websites', $includeDeleted);
            if (count($websiteConfigs) < 1) {
                // No website scope config found, use global level config
                $results[$globalConfigId] = $globalConfig;
                continue;
            }

            /** @var $websiteConfig Payone_Core_Model_Domain_Resource_Config_PaymentMethod */
            $websiteConfig = $websiteConfigs->getFirstItem();

            $mergedConfig = $this->mergeConfigs($globalConfig, $websiteConfig);

            $results[$websiteConfig->getId()] = $mergedConfig;
        }

        $this->resetData();
        foreach ($results as $config) {
            $this->addItem($config);
        }
        $this->_isCollectionLoaded = true;
        return $this;
    }

    /**
     * Fetch a collection filtered by scope and scopeId.
     * This function will NOT modify this object, only return a NEW collection.
     *
     * @param int $scopeId
     * @param string $scope               ('default', 'websites', 'stores')
     * @param bool $includeDeleted true = collection also included configurations marked as "is_deleted = 1"
     *
     * @return Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection
     */
    protected function getCollectionByScopeId($scopeId = 0, $scope = 'default', $includeDeleted = false)
    {
        /** @var $collection Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection */
        $collection = $this->getFactory()->getModelDomainConfigPaymentMethod()->getCollection();

        $collection->addFieldToFilter('scope', $scope);
        $collection->addFieldToFilter('scope_id', $scopeId);
        if (!$includeDeleted) {
            $collection->addFilterIsDeleted(0);
        }

        return $collection;
    }

    /**
     * Fetch a collection filtered by scope and scopeId.
     *
     * @param int $scopeId
     * @param string $scope               ('default', 'websites', 'stores')
     *
     * @return Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection
     * @throws Payone_Core_Exception_InvalidScope
     */
    public function getCollectionByScopeIdMerged($scopeId = 0, $scope = 'default')
    {        if ($scope === 'default') {
            $this->addFieldToFilter('scope', $scope);
                    $this->addFieldToFilter('scope_id', $scopeId);
            $this->addFilterIsDeleted(0);

            return $this;
        }
        if ($scope === 'websites') {
            return $this->getCollectionByWebsiteId($scopeId);
        }
        if ($scope === 'stores') {
            return $this->getCollectionByStoreId($scopeId);
        }
        throw new Payone_Core_Exception_InvalidScope();
    }

    /**
     * Merge config2 onto config1, config2 values overwrite config1 values.
     *
     * @param Payone_Core_Model_Domain_Config_PaymentMethod $config1
     * @param Payone_Core_Model_Domain_Config_PaymentMethod $config2
     *
     * @return Payone_Core_Model_Domain_Config_PaymentMethod
     */
    protected function mergeConfigs(Payone_Core_Model_Domain_Config_PaymentMethod $config1,
                                    Payone_Core_Model_Domain_Config_PaymentMethod $config2)
    {
        foreach ($config2->getData() as $key => $value) {
            if (isset($value)) {
                $config1->setData($key, $config2->getData($key));
            }
        }
        return $config1;
    }

    /**
     * @param int $parentId
     * @param int $scopeId
     * @param string $scope ('default', 'websites', 'stores')
     *
     * @return Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection
     */
    public function getChildConfigs($parentId, $scopeId, $scope)
    {
        /** @var $collection Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection */
        $collection = $this->getFactory()->getModelDomainConfigPaymentMethod()->getCollection();

        $parentIdField = 'websites';
        if ($scope === 'websites') {
            $parentIdField = 'parent_default_id';
        }
        if ($scope === 'stores') {
            $parentIdField = 'parent_websites_id';
        }
        $collection = $this->getCollectionByScopeId($scopeId, $scope);
        $collection->addFieldToFilter($parentIdField, $parentId);

        return $collection;
    }


    /**
     * @param Payone_Core_Model_Domain_Config_PaymentMethod $child
     * @param Payone_Core_Model_Domain_Config_PaymentMethod $parent
     * @return Payone_Core_Model_Domain_Config_PaymentMethod
     */
    protected function mergeData(
        Payone_Core_Model_Domain_Config_PaymentMethod $child,
        Payone_Core_Model_Domain_Config_PaymentMethod $parent
    )
    {
        foreach ($child->getData() as $key => $value) {
            if ($value === null || $value === false) {
                $child->setData($key, $parent->getData($key));
            }
        }
        return $child;
    }

    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }
        return $this->factory;
    }
}