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
class Payone_Core_Model_Service_Config_PaymentMethod_Create
    extends Payone_Core_Model_Service_Abstract
{
    /**
     * Create inherited child configurations for a Store
     *
     * @param Mage_Core_Model_Store $store
     *
     * @return Payone_Core_Model_Domain_Config_PaymentMethod[]
     */
    public function executeByStore(Mage_Core_Model_Store $store)
    {
        $scope = 'stores';
        $scopeId = $store->getStoreId();
        $parentId = $store->getWebsiteId();

        $this->savePaymentConfigs($scope, $scopeId, $parentId);
    }


    /**
     * Create inherited child configurations for a Store
     *
     * @param Mage_Core_Model_Website $website
     *
     * @return Payone_Core_Model_Domain_Config_PaymentMethod[]
     */
    public function executeByWebsite(Mage_Core_Model_Website $website)
    {
        $scope = 'websites';
        $scopeId = $website->getWebsiteId();

        $this->savePaymentConfigs($scope, $scopeId);
    }


    /**
     *
     * @param string $scope
     * @param int $scopeId
     * @param int $parentId
     */
    protected function savePaymentConfigs($scope = 'websites', $scopeId, $parentId = 0)
    {
        $parentField = 'parent_default_id';
        $parentScope = 'default';
        if ($scope == 'stores') {
            $parentField = 'parent_websites_id';
            $parentScope = 'websites';
        }

        /** @var $collection Payone_Core_Model_Domain_Resource_Config_PaymentMethod_Collection */
        $collection = $this->getFactory()->getModelDomainConfigPaymentMethod()->getCollection();
        $collection->addFieldToFilter('scope', $parentScope);
        $collection->addFieldToFilter('scope_id', $parentId);
        // @todo hs: include deleted configs?

        foreach ($collection->getItems() as $parentConfig) {
            $childPaymentConfig = new Payone_Core_Model_Domain_Config_PaymentMethod();
            $childPaymentConfig->setScope($scope);
            $childPaymentConfig->setScopeId($scopeId);
            $childPaymentConfig->setCode($parentConfig->getCode());
            $childPaymentConfig->setData($parentField, $parentConfig->getId());
            $childPaymentConfig->save();
        }


    }
}