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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Settings_Data_ConfigFile_PaymentMethod_Financing
    extends Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    /** @var string */
    protected $key = Payone_Enum_ClearingType::FINANCING;

    /** @var string */
    protected $financingType = '';
    protected $klarna_config = array();

    /**
     * @return string
     */
    public function getClearingType()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $financingType
     */
    public function setFinancingType($financingType)
    {
        $this->financingType = $financingType;
    }

    /**
     * @return string
     */
    public function getFinancingType()
    {
        return $this->financingType;
    }

    /**
     * @param array $klarna_config
     */
    public function setKlarnaConfig($klarna_config)
    {
        $this->klarna_config = $klarna_config;
    }

    /**
     * @return array
     */
    public function getKlarnaConfig()
    {
        return $this->klarna_config;
    }

    /**
     * @param $value
     */
    public function addKlarnaConfig($value)
    {
        $this->klarna_config[] = $value;
    }
}
 
