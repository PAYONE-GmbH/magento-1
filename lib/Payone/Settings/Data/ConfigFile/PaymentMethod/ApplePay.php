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
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @author          Fatchip GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */
class Payone_Settings_Data_ConfigFile_PaymentMethod_ApplePay
    extends Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    /** @var string */
    protected $key = Payone_Enum_ClearingType::APPLEPAY;

    public function getKey()
    {
        return $this->key;
    }
}