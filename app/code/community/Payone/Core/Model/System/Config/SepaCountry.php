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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_System_Config_SepaCountry extends Payone_Core_Model_System_Config_Abstract
{
    /**
     * @return array
     */
    public function toArray()
    {
        $sepaCountryCodes = array(
            "AX", "AT", "BE", "BG", "HR", "CY", "CZ", "DK", "EE", "FI", "FR", "GF", "DE", "GI",
            "GR", "GP", "HU", "IS", "IE", "IT", "LV", "LI", "LT", "LU", "MT", "MQ", "YT", "MC",
            "NL", "NO", "PL", "PT", "RE", "RO", "BL", "MF", "PM", "SK", "SI", "ES", "SE", "CH", "GB"
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