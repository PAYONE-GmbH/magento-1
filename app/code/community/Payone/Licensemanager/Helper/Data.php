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
 * @package         Payone_Licensemanager_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Licensemanager_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Licensemanager_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    const CONFIG_KEY_LICENSE_KEY = 'payone_license_key';

    const PAYONE_CORE_MODULE = 'Payone_Core';
    const PAYONE_MIGRATOR_MODULE = 'Payone_Migrator';

    /**
     * Retrieve the license key from config
     *
     * @return string
     */
    public function getLicenseKey()
    {
        $licenseKey = Mage::getStoreConfig(self::CONFIG_KEY_LICENSE_KEY, null);
        if (empty($licenseKey)) {
            $licenseKey = 'NOTREGISTERED';
        }

        return $licenseKey;
    }


    /**
     * Store license key in config
     */
    public function setLicenseKey()
    {
        $payoneVersion = $this->getVersions();
        $licenseKey = md5($payoneVersion);
        Mage::getConfig()->saveConfig(self::CONFIG_KEY_LICENSE_KEY, $licenseKey);
    }

    /**
     * Check and retrieve if Payone module was registerd and license key is valid
     *
     * @return boolean
     */
    public function isPayoneRegisterd()
    {
        return $this->getLicenseKey() != 'NOTREGISTERED';
    }

    /**
     * Retrieve all versions joined by '-'
     *
     * @return string
     */
    public function getVersions()
    {
        return (string) Mage::getConfig()->getNode()->modules->Payone_Core->version
            .'-'.(string) Mage::getConfig()->getNode()->modules->Payone_Migrator->version
            .'-'.(string) Mage::getConfig()->getNode()->modules->Payone_Licensemanager->version;
    }

    /**
     * Set all Payone modules deactive
     */
    public function setPayoneModuleDeactive()
    {
        Mage::getConfig()->setNode('modules/'.self::PAYONE_CORE_MODULE.'/active', 'false', true);
        Mage::getConfig()->setNode('modules/'.self::PAYONE_MIGRATOR_MODULE.'/active', 'false', true);
        Mage::app()->getStore()->setConfig('advanced/modules_disable_output/'.self::PAYONE_CORE_MODULE, true);
        Mage::app()->getStore()->setConfig('advanced/modules_disable_output/'.self::PAYONE_MIGRATOR_MODULE, true);
        return $this;
    }
}