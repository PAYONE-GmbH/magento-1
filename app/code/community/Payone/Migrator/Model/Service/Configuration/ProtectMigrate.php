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
class Payone_Migrator_Model_Service_Configuration_ProtectMigrate
    extends Payone_Migrator_Model_Service_Abstract
{

    /** @var Payone_Migrator_Model_Mapper_Config_Protect */
    protected $mapperConfigProtect = null;

    /**
     * @return bool
     */
    public function execute()
    {
        if (!$this->helper()->hasProtectConfig()) {
            $this->log('no migration needed');
            return true;
        }

        $this->log('Start');
        $oldCollection = $this->getOldProtectConfig();

        $newConfigs = array();
        foreach ($oldCollection as $oldConfig) {
            /** @var $oldConfig Mage_Core_Model_Config_Data */
            $oldPath = $oldConfig->getPath();

            $newConfigPath = $this->getMapperConfigProtect()->mapConfigPathOldToNew($oldPath);
            if (!$newConfigPath) {
                continue; // Value does not need to be migrated.
            }

            $newConfig = $this->getFactory()->getModelCoreConfigData();

            $newValue = $this->getMapperConfigProtect()->mapConfigValueOldToNew($oldPath, $oldConfig->getValue());

            $newConfig->setPath($newConfigPath);
            $newConfig->setScope($oldConfig->getScope());
            $newConfig->setScopeId($oldConfig->getScopeId());
            $newConfig->setValue($newValue);

            array_push($newConfigs, $newConfig);
        }

        $this->log(count($newConfigs) . ' config value(s) found.');
        foreach ($newConfigs as $newConfig) {
            /** @var $newConfig Mage_Core_Model_Config_Data */
            $newConfig->save();
        }

        $this->log('Success');

        return true;
    }

    /**
     * @return Mage_Core_Model_Mysql4_Config_Data_Collection
     */
    public function getOldProtectConfig()
    {
        return $this->helper()->getOldProtectConfig();
    }

    private function log($message)
    {
        $this->helper()->log($message);
    }

    /**
     * @param Payone_Migrator_Model_Mapper_Config_Protect $mapperConfigProtect
     */
    public function setMapperConfigProtect(Payone_Migrator_Model_Mapper_Config_Protect $mapperConfigProtect)
    {
        $this->mapperConfigProtect = $mapperConfigProtect;
    }

    /**
     * @return Payone_Migrator_Model_Mapper_Config_Protect
     */
    public function getMapperConfigProtect()
    {
        return $this->mapperConfigProtect;
    }

}
