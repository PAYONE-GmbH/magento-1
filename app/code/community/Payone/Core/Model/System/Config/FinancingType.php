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
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */
class Payone_Core_Model_System_Config_FinancingType extends Payone_Core_Model_System_Config_Abstract
{
    /**
     * @return array
     */
    public function toArray()
    {
        $types = array(
            Payone_Api_Enum_FinancingType::CFR => Payone_Api_Enum_FinancingType::CFR,
            Payone_Api_Enum_FinancingType::KLS => Payone_Api_Enum_FinancingType::KLS,
        );

        return $types;

//        $settings = new Payone_Settings_Configuration_PaymentMethod_Financing();
//
//        $types = $settings->getTypes();
//        if(array_key_exists(Payone_Api_Enum_FinancingType::BSV, $types))
//            unset($types[Payone_Api_Enum_FinancingType::BSV]); // BSV has a separate Payment method.
//        if(array_key_exists(Payone_Api_Enum_FinancingType::KLV, $types))
//            unset($types[Payone_Api_Enum_FinancingType::KLV]); // KLV has a separate Payment method.
//        return $types;
    }
}