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
class Payone_Core_Helper_Data
    extends Payone_Core_Helper_Abstract
{
    /**
     * Retrieve Payone_Core version from Magento Module Config
     * @return mixed
     */
    public function getPayoneVersion()
    {
        $module = Mage::getConfig()->getNode('modules/Payone_Core')->children();
        $moduleArray = (array)$module;

        $version = $moduleArray['version'];

        return $version;
    }

    /**
     * Retrieve Magento version
     *
     * @return mixed
     */
    public function getMagentoVersion()
    {
        return Mage::getVersion();
    }

    /**
     * Retrieve Magento edition
     *
     * @return mixed
     */
    public function getMagentoEdition()
    {
        if (method_exists('Mage', 'getEdition')) {
            // getEdition is only available after Magento CE Version 1.7.0.0
            $edition = Mage::getEdition();
            switch ($edition) {
                case Mage::EDITION_COMMUNITY :
                    $edition = 'CE';
                    break;
                case Mage::EDITION_ENTERPRISE :
                    $edition = 'EE';
                    break;
                case Mage::EDITION_PROFESSIONAL :
                    $edition = 'PE';
                    break;
                case Mage::EDITION_GO :
                    $edition = 'GO';
                    break;
            }
        }
        else {
            // Check for different Licensetypes to get Magento-Edition
            $path = Mage::getBaseDir();
            if (file_exists($path . DS . 'LICENSE_EE.txt')) {
                $edition = 'EE';
            }
            elseif (file_exists($path . DS . 'LICENSE_PRO.html')) {
                $edition = 'PE';
            }
            else {
                $edition = 'CE';
            }
        }
        return $edition;
    }

    /**
     * Determine installer style to use, by Magento version/edition
     * Pre-CE1.6 = use SQL script
     *
     * @return bool
     */
    public function mustUseSqlInstaller()
    {
        $magentoVersion = $this->getMagentoVersion();

        switch ($this->getMagentoEdition()) {
            case 'CE' :
                if (version_compare($magentoVersion, '1.6', '<')) {
                    return true;
                }
                break;
            case 'EE' : // Intentional fallthrough
            case 'PE' :
            if (version_compare($magentoVersion, '1.11', '<')) {
                return true;
            }
                break;
        }

        return false;
    }

     /**
     * Determine if Magento App Emulation is available
     *
     * @return bool
     */
    public function canUseAppEmulation()
    {
        $magentoVersion = $this->getMagentoVersion();

        switch ($this->getMagentoEdition()) {
            case 'CE' :
                if (version_compare($magentoVersion, '1.5', '<')) {
                    return false;
                }
                break;
            case 'EE' : // Intentional fallthrough
            case 'PE' :
                if (version_compare($magentoVersion, '1.10', '<')) {
                    return false;
                }
                break;
        }

        return true;
    }
    
    /**
     * @return int
     */
    public function getCurrentMagentoStoreId()
    {
        return $this->getCurrentMagentoStore()->getId();
    }

    /**
     * @return Mage_Core_Model_Store
     */
    public function getCurrentMagentoStore()
    {
        return Mage::app()->getStore();

    }

    /**
     * @return bool
     */
    public function isCronEnabled()
    {
        $model = $this->getFactory()->getModelCronSchedule();
        /** @var $collection Mage_Cron_Model_Mysql4_Schedule_Collection */
        $collection = $model->getCollection();

        if ($collection->count() < 1) {
            // No cronjobs found, we must assume they are disabled.
            return false;
        }
        return true;
    }


    /**
     * Format Magento Adress "street" into one string.
     *
     * @param $street
     * @return string
     */
    public function normalizeStreet($street)
    {
        if (!is_array($street)) {
            return $street;
        }
        return implode(' ', $street);
    }

    /**
     * @return string
     */
    public function getDefaultLanguage()
    {
        $locale = explode('_', Mage::app()->getLocale()->getLocaleCode());
        if (is_array($locale) && !empty($locale)) {
            $locale = $locale[0];
        }
        else {
            $locale = 'en';
        }

        return $locale;
    }

    /**
     * Converts timezone from "GMT" to locale timezone
     * @param $string
     * @return string|null
     */
    public function getLocaleDatetime($string)
    {
        $localeTimeZone = $this->helperConfig()->getStoreConfig('general/locale/timezone');

        if ($string == '0000-00-00 00:00:00' || $string == null) {
            return null;
        }
        else {
            $datetime = new DateTime($string, new DateTimeZone('GMT'));
            $datetime->setTimezone(new DateTimeZone($localeTimeZone));
            return $datetime->format('d.m.Y H:i:s');
        }
    }

    /**
     * @param string $date             The date to test, in a format that can be parsed via strtotime(), e.g.
     * @param int $validForSeconds     How long the date stays valid
     *
     * @return bool
     */
    public function isDateStillValid($date, $validForSeconds)
    {
        $now = strtotime(now());
        $date = strtotime($date);

        $secondsElapsed = $now - $date;

        if ($secondsElapsed > $validForSeconds) {
            return false; // Allowed time has elapsed
        }
        return true;

    }

    /**
     * Creates a hash from an addresses key data
     *
     * @param Mage_Customer_Model_Address_Abstract $address
     * @return string
     */
    public function createAddressHash(Mage_Customer_Model_Address_Abstract $address)
    {
        $values = $address->getFirstname() . $address->getLastname() . $address->getStreetFull() . $address->getPostcode() . $address->getCity() . $address->getRegionCode() . $address->getCountry();

        $hash = md5($values);

        return $hash;
    }

    /**
     * @param Mage_Customer_Model_Address_Abstract $address1
     * @param Mage_Customer_Model_Address_Abstract $address2
     * @return bool
     */
    public function addressesAreEqual(Mage_Customer_Model_Address_Abstract $address1, Mage_Customer_Model_Address_Abstract $address2)
    {
        $hash1 = $this->createAddressHash($address1);
        $hash2 = $this->createAddressHash($address2);
                
        if($hash1 == $hash2)
            return true;
        return false;
    }

    /**
     * Check if Mage-Compiler is enabled
     * @return bool
     */
    public function isCompilerEnabled()
    {
        if(defined('COMPILER_INCLUDE_PATH'))
        {
            return true;
        }
        return false;
    }
}