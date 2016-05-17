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
 * @package         Payone_Core
 * @subpackage      sql
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

$tableConfigPaymentMethod = $this->getTable('payone_core/config_payment_method');
$tableWebsite = $this->getTable('core/website');
$tableStore = $this->getTable('core/store');


$connection = $installer->getConnection();
$time = date('Y-m-d H:i:s');

// Retrieve all default scope configs:
$selectConfigDefault = $connection->select();
$selectConfigDefault->from($tableConfigPaymentMethod, array('id', 'code'));
$selectConfigDefault->where('scope = "default"');
$defaultScopeConfigs = $selectConfigDefault->query()->fetchAll();

// Retrieve all websites:
$selectWebsites = $connection->select();
$selectWebsites->from($tableWebsite, 'website_id');
$selectWebsites->where('code != "admin"');


$websites = $selectWebsites->query()->fetchAll();
foreach ($defaultScopeConfigs as $config) {
    $config_id = $config['id'];

    // Check if all websites have inherited a config for the default scope config:
    foreach ($websites as $website) {
        $website_id = $website['website_id'];

        $selectConfigChild = $connection->select();
        $selectConfigChild->from($tableConfigPaymentMethod, 'id');
        $selectConfigChild->where('scope = "websites"');
        $selectConfigChild->where('parent_default_id = "' . $config_id . '"');
        $selectConfigChild->where('scope_id = "' . $website_id . '"');

        $websiteConfig = $selectConfigChild->query()->fetch();

        if ($websiteConfig) {
            continue;
        }

        // No config found, create a new one on website scope
        $newConfigData = array(
            'scope' => 'websites',
            'scope_id' => $website_id,
            'code' => $config['code'],
            'parent_default_id' => $config_id,
            'created_at' => $time,
        );

        $connection->insert($tableConfigPaymentMethod, $newConfigData);
    }
}

// Finished with default->website, now go through website scope (including the new ones) configs and add children on storeView config
foreach ($websites as $website) {
    $website_id = $website['website_id'];
    // Retrieve all website scope configs fore selected website:
    $selectConfigWebsite = $connection->select();
    $selectConfigWebsite->from($tableConfigPaymentMethod, array('id', 'code'));
    $selectConfigWebsite->where('scope = "websites"');
    $selectConfigWebsite->where('scope_id = "' . $website_id . '"');

    $websiteScopeConfigs = $selectConfigWebsite->query()->fetchAll();


    foreach ($websiteScopeConfigs as $config) {
        $config_id = $config['id'];

        // Retrieve all stores for this website:
        $selectStores = $connection->select();
        $selectStores->from($tableStore, array('store_id', 'website_id'));
        $selectStores->where('website_id = "' . $website_id . '"');
        $stores = $selectStores->query()->fetchAll();

        foreach ($stores as $store) {
            $store_id = $store['store_id'];

            // Check if all stores have inherited configs
            $selectConfigChild = $connection->select();
            $selectConfigChild->from($tableConfigPaymentMethod, 'id');
            $selectConfigChild->where('scope = "stores"');
            $selectConfigChild->where('parent_websites_id = "' . $config_id . '"');
            $selectConfigChild->where('scope_id = "' . $store_id . '"');

            $storeViewConfig = $selectConfigChild->query()->fetch();

            if ($storeViewConfig) {
                continue;
            }
            // No config found, create a new one on storeView scope
            $newConfigData = array(
                'scope' => 'stores',
                'scope_id' => $store_id,
                'code' => $config['code'],
                'parent_websites_id' => $config_id,
                'created_at' => $time,
            );

            $connection->insert($tableConfigPaymentMethod, $newConfigData);

        }

    }
}
$installer->endSetup();