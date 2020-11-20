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
 * @subpackage      System
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */
class Payone_Core_Model_System_Config_TrustlySepaCountry extends Payone_Core_Model_System_Config_Abstract
{
    /**
     * @return array
     */
    public function toArray()
    {
        $sepaCountryCodes = array(
            "DE", "DK", "EE", "ES",
            "FI", "IT", "MT", "NL",
            "NO", "PL", "SE"
        );

        /** @var Mage_Directory_Model_Resource_Country_Collection $countryCollection */
        $countryCollection = Mage::getResourceModel('directory/country_collection');
        $allCountries = $countryCollection->loadData()->toOptionArray(false);

        $resultArr = array();
        foreach ($allCountries as $country) {
            if (in_array($country['value'], $sepaCountryCodes)) {
                $resultArr[$country['value']] = $this->helper()->__($country['label']);
            }
        }

        return $resultArr;
    }
}