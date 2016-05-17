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
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Core_Model_System_Config_KlarnaCountry extends Payone_Core_Model_System_Config_Abstract
{
    /** @var Mage_Adminhtml_Model_System_Config_Source_Country_Full */
    protected $sourceModelCountry = null;
    /**
     * @return array
     */
    public function toArray()
    {
        $fullOptionArray = $this->getSourceModelCountry()->toOptionArray(true);
        $klarnaIsoCodes = $this->getKlarnaCountriesIsoCodes();
        $result = array();
        foreach ($fullOptionArray as $countryOption) {
            if (in_array($countryOption['value'], $klarnaIsoCodes)) {
                $result[$countryOption['value']] = $countryOption['label'];
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    protected function getKlarnaCountriesIsoCodes()
    {
        return array('AT', 'DE', 'DK', 'FI', 'NL', 'NO', 'SE');
    }

    /**
     * @param \Mage_Adminhtml_Model_System_Config_Source_Country_Full $sourceModelCountry
     */
    public function setSourceModelCountry(Mage_Adminhtml_Model_System_Config_Source_Country_Full $sourceModelCountry)
    {
        $this->sourceModelCountry = $sourceModelCountry;
    }

    /**
     * @return \Mage_Adminhtml_Model_System_Config_Source_Country_Full
     */
    public function getSourceModelCountry()
    {
        if ($this->sourceModelCountry === null) {
            $this->sourceModelCountry = $this->getFactory()->getModelSystemConfigCountryFull();
        }
        return $this->sourceModelCountry;
    }
}
 