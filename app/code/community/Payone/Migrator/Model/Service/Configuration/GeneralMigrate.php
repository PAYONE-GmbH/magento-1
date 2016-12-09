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
class Payone_Migrator_Model_Service_Configuration_GeneralMigrate extends Payone_Migrator_Model_Service_Abstract
{
    /**
     * @var Payone_Migrator_Model_Mapper_Config_General
     */
    protected $mapperConfigGeneral = null;

    public function migrate()
    {
        // get values by first active payment method config
        $configValues = $this->getConfigValuesByFirstActiveMethod();

        if ($configValues === null) {
            $this->helper()->log('could not find an active configuration');
            return true;
        }

        //
        foreach ($configValues as $key => $configDataMethod) {
            $configData = $this->getMapperConfigGeneral()->mapConfigData($configDataMethod);
            if ($configData instanceof Mage_Core_Model_Config_Data) {
                $configData->save();
            }
        }

        return true;
    }

    protected function getConfigValuesByFirstActiveMethod()
    {
        /** @var $configsActive Mage_Core_Model_Resource_Config_Data_Collection */
        $configsActive = $this->getFactory()->getModelCoreConfigData()->getCollection();
        $configsActive->addFieldToFilter('path', array('like' => 'payment/payone_%/active'));
        // Do not filter scope, cause we want the values to be mapped 1:1

        //
        $count = $configsActive->count();
        if (!$count) {
            return null;
        }

        //
        $items = $configsActive->getItems();
        $firstItem = array_shift($items);
        $path = str_replace('/active', '', $firstItem->getPath());

        /** @var $configValues Mage_Core_Model_Resource_Config_Data_Collection */
        $configValues = $this->getFactory()->getModelCoreConfigData()->getCollection();
        $configValues->addFieldToFilter('path', array('like' => $path . '/%'));

        $configValues->load();
//
//        // Found a config and get MethodCode and Store Id from it
//        $pathParts = explode('/', $config->getPath());
//        if (count($pathParts) != 3) {
//            return null;
//        }
//
//        $methodCode = $pathParts[1];
//        $storeId = $config->getScopeId();
//
//        // Retrive Store Config
//        $config = Mage::getStoreConfig('payment/' . $methodCode, $storeId);
        return $configValues;
    }

    /**
     * @param \Payone_Migrator_Model_Mapper_Config_General $mapperConfigGeneral
     */
    public function setMapperConfigGeneral($mapperConfigGeneral)
    {
        $this->mapperConfigGeneral = $mapperConfigGeneral;
    }

    /**
     * @return \Payone_Migrator_Model_Mapper_Config_General
     */
    public function getMapperConfigGeneral()
    {
        return $this->mapperConfigGeneral;
    }
}
