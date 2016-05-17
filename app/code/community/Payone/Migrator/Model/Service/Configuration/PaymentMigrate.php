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
 * Do not edit or add to this file if you wish to upgrade Payone_Migrator to newer
 * versions in the future. If you wish to customize Payone_Migrator for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Migrator_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Migrator_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Migrator_Model_Service_Configuration_PaymentMigrate extends Payone_Migrator_Model_Service_Abstract
{
    /**
     * @var Payone_Migrator_Model_Mapper_Config_Payment
     */
    protected $mapperConfigPayment = null;

    public function createConfigurationsAndConnectToOrders()
    {
        if (!$this->hasPayoneOrders()) {
            return true;
        }

        // Create array of configs to migrate
        $orderPayments = $this->getOrderPayments();
        if ($orderPayments == null) {
            return true;
        }
        $paymentConfigs = $this->preparePaymentConfigsToMigrate($orderPayments->getItems());

        // Iterate Payment Configs
        $this->helper()->log('creating payment configuration');
        $methodStoreToConfig = $this->createPaymentConfigurationsByArray($paymentConfigs);

        // Resource Model
        $resource = $this->getFactory()->getModelCoreResource();

        // Fetch Tablenames
        $tableOrder = $resource->getTableName('sales/order');
        $tableSalesOrderGrid = $resource->getTableName('sales/order_grid');
        $tableOrderPayment = $resource->getTableName('sales/order_payment');

        // Init Write Connection
        $db = $resource->getConnection('core_write');

        foreach ($methodStoreToConfig as $method => $storeToConfig) {
            $newMethodCode = $this->getMapperConfigPayment()->mapOldMethodCodeToNew($method);

            foreach ($storeToConfig as $storeId => $configId) {
                // set NEW Payment Config Id
                $this->helper()->log($method . ' : set config id ' . $configId . ' for store ' . $storeId);
                $query = $this->sqlOrderPaymentUpdateConfigId($tableOrderPayment, $tableOrder, $configId, $method, $storeId);
                $db->query($query);
            }

            // set new method to order grid
            $this->helper()->log($method . ' : update order grid');
            $query = $this->sqlOrderGridUpdatePaymentMethod($tableSalesOrderGrid, $tableOrderPayment, $newMethodCode, $method);
            $db->query($query);
        }

        return true;
    }

    /**
     * !!! This method will exchange the old codes with new the codes !!!
     * !!! After this call there is no possibility to find old methods by code !!!
     */
    public function finishPaymentMigration()
    {
        if (!$this->hasPayoneOrders()) {
            return true;
        }

        // Resource Model
        $resource = $this->getFactory()->getModelCoreResource();
        $db = $resource->getConnection('core_write');
        $tableOrderPayment = $resource->getTableName('sales/order_payment');

        $methods = $this->getMapperConfigPayment()->getMappingMethodCode();
        foreach ($methods as $old => $new) {
            // set method to NEW payment method code
            $this->helper()->log($old . ' : set to ' . $new);
            $query = $this->sqlOrderPaymentUpdateMethod($tableOrderPayment, $new, $old);
            $db->query($query);
        }
        return true;
    }

    protected function preparePaymentConfigsToMigrate(array $orderPayments)
    {
        $paymentConfigs = array();
        foreach ($orderPayments as $paymentId => $orderPayment) {
            $methodCode = $orderPayment->getMethod();
            $storeId = $orderPayment->getStoreId();
            if (!array_key_exists($methodCode, $paymentConfigs)) {
                $paymentConfigs[$methodCode] = array();
            }
            $paymentConfigs[$methodCode][$storeId] = Mage::getStoreConfig('payment/' . $methodCode, $storeId);
        }
        return $paymentConfigs;
    }

    /**
     * @param $paymentConfigs
     * @return array
     */
    protected function createPaymentConfigurationsByArray($paymentConfigs)
        {
            $methodStoreToConfig = array();
            foreach ($paymentConfigs as $methodCode => $paymentConfigForStore) {
                $globalConfig = $this->paymentCreateGlobalConfiguration($methodCode);
                // child configs on storeView and website scope are automatically created.

                // Build array of configIds by methodcode/store
                foreach ($paymentConfigForStore as $storeId => $paymentConfig) {
                    // Load correct config:
                    /** @var $collection Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection */
                    $collection = Mage::getModel('payone_core/domain_config_paymentMethod')->getCollection();
                    $collection->addFieldToFilter('scope', 'stores');
                    $collection->addFieldToFilter('scope_id', $storeId);
                    $collection->addFieldToFilter('code', $globalConfig->getCode()); // the newly mapped code

                    $storeConfig = $collection->getFirstItem();

                    $storeConfigId = $storeConfig->getId();

                    if (!array_key_exists($methodCode, $methodStoreToConfig)) {
                        $methodStoreToConfig[$methodCode] = array();
                    }
                    $methodStoreToConfig[$methodCode][$storeId] = $storeConfigId;
                }
            }
            return $methodStoreToConfig;
        }

    protected function paymentCreateGlobalConfiguration($methodCode)
    {
        /** @var $configDataCollection Mage_Core_Model_Resource_Config_Data_Collection */
        $configDataCollection = Mage::getModel('core/config_data')->getCollection();
        $configDataCollection->addFieldToFilter('scope', 'default');
        $configDataCollection->addFieldToFilter('scope_id', 0);
        $configDataCollection->addFieldToFilter('path', array('like' => 'payment/' . $methodCode . '%'));
        $items = $configDataCollection->getItems();

        // Prepare Config
        $globalMethodConfig = array();
        foreach ($items as $key => $configData) {
            $configKey = str_replace('payment/' . $methodCode . '/', '', $configData->getPath());
            $globalMethodConfig[$configKey] = $configData->getValue();
        }

        // Create
        $config = $this->createPaymentConfiguration($methodCode, $globalMethodConfig, 'default', 0);
        return $config;
    }

    protected function createPaymentConfiguration($code, $config, $scope, $scopeId)
    {
        $config['scope'] = $scope;
        $config['scope_id'] = $scopeId;

        $configData = $this->getMapperConfigPayment()->map($code, $config);

        if (array_key_exists('parent_websites_id', $config)) {
            $configData['parent_websites_id'] = $config['parent_websites_id'];
        }
        if (array_key_exists('parent_default_id', $config)) {
            $configData['parent_default_id'] = $config['parent_default_id'];
        }

        $configMethod = $this->getModelDomainConfigPaymentMethod();
        $configMethod->setData($configData);
        $configMethod->save();

        return $configMethod;
    }

    protected function sqlOrderPaymentUpdateMethod($tableOrderPayment, $newMethodCode, $method)
    {
        $query = "
            UPDATE {$tableOrderPayment}
            SET method = '$newMethodCode'
            WHERE method = '$method' ;
            ";
        return $query;
    }

    protected function sqlOrderGridUpdatePaymentMethod($tableSalesOrderGrid, $tableOrderPayment, $newMethodCode, $method)
    {
        $query = "
            UPDATE {$tableSalesOrderGrid} as so
            INNER JOIN $tableOrderPayment as sop ON sop.parent_id = so.entity_id
            SET so.payone_payment_method = '$newMethodCode'
            WHERE sop.method = '$method' ;
            ";
        return $query;
    }

    protected function sqlOrderPaymentUpdateConfigId($tableOrderPayment, $tableOrder, $configId, $method, $storeId)
    {
        $query = "
                UPDATE $tableOrderPayment as sop
                INNER JOIN $tableOrder as so ON sop.parent_id = so.entity_id
                SET payone_config_payment_method_id = $configId
                WHERE method = '$method'
                AND store_id = $storeId ;
                ";
        return $query;
    }

    /**
     * @return Payone_Core_Model_Domain_Config_PaymentMethod
     */
    protected function getModelDomainConfigPaymentMethod()
    {
        return $this->getFactory()->getModelDomainConfigPaymentMethod();
    }

    /**
     * @param Payone_Migrator_Model_Mapper_Config_Payment $mapperPayment
     */
    public function setMapperConfigPayment($mapperPayment)
    {
        $this->mapperConfigPayment = $mapperPayment;
    }

    /**
     * @return Payone_Migrator_Model_Mapper_Config_Payment
     */
    public function getMapperConfigPayment()
    {
        return $this->mapperConfigPayment;
    }
}
