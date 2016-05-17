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
 * @package         Payone_Migrator_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Migrator_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Migrator_Helper_Config
    extends Mage_Core_Helper_Abstract
{
    /**
     * Check if we need to migrate Data
     *
     * @return bool
     */
    public function haveToMigrate()
    {
        // no need to migrate if we already did successfully
        if ($this->isStatusSuccess()) {
            return false;
        }

        // Migration is need if there are payone orders or a protect config
        if ($this->helper()->hasPayoneOrders()
                or $this->helper()->hasProtectConfig()
        ) {
            return true;
        }
        else {
            // Mark migration success, it is not needed
            $this->setMigrationStatus();
            return false;
        }
    }

    public function areAllMigrationPartsSuccess()
    {
        $parts = $this->helper()->getParts();

        foreach ($parts as $part) {
            if (!$this->isPartStatusSuccess($part)) {
                return false;
            }
        }
        return true;
    }

    public function haveToMigratePart($part)
    {
        if ($this->isStatusSuccess()) {
            $this->helper()->log($part . ': migration already completed');
            return false;
        }
        if ($this->isPartStatusSuccess($part)
                or $this->isPartStatusError($part)
        ) {
            $this->helper()->log($part . ': status already success or error');
            return false;
        }
        return true;
    }

    public function isStatusSuccess()
    {
        $status = $this->getConfigValue('payone_migration/check/status');
        if ($status == 'success') {
            return true;
        }
        return false;
    }

    public function isStatusError()
    {
        $status = $this->getConfigValue('payone_migration/check/status');
        if ($status == 'error') {
            return true;
        }
        return false;
    }

    public function isPartStatusSuccess($part)
    {
        $status = $this->getConfigValue('payone_migration/check/' . $part);
        if ($status == 'success') {
            return true;
        }
        return false;
    }

    public function isPartStatusError($part)
    {
        $status = $this->getConfigValue('payone_migration/check/' . $part);
        if ($status == 'error') {
            return true;
        }
        return false;
    }

    public function setMigrationStatus($status = 'success')
    {
        $this->setConfigValue('payone_migration/check/status', $status);
        return true;
    }

    public function setMigrationPartStatus($part, $status = 'success')
    {
        $this->setConfigValue('payone_migration/check/' . $part, $status);
        return true;
    }

    public function getConfigValue($path)
    {
        // return Mage::getStoreConfig($path, 0);
        // we need to use Model, otherwise config value gets cached and is not loaded properly
        $configData = Mage::getModel('core/config_data');
        $configData->load($path, 'path');

        return $configData->getValue();
    }

    public function setConfigValue($path, $value)
    {
        $configData = Mage::getModel('core/config_data');
        $configData->setPath($path);
        $configData->setScopeId(0);
        $configData->setScope('default');
        $configData->setValue($value);
        $configData->save();
        return true;
    }

    /**
     * @return Payone_Migrator_Helper_Data
     */
    protected function helper()
    {
        return Mage::helper('payone_migrator');
    }
}