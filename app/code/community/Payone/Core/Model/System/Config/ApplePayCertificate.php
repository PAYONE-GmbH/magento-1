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
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de>
 * @author          Fatchip GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

/**
 * Class Payone_Core_Model_System_Config_RatepayType
 */
class Payone_Core_Model_System_Config_ApplePayCertificate extends Mage_Core_Model_Config_Data
{
    public function save()
    {
        parent::save();
    }

    public function _beforeSave()
    {
        $a = $this;
        var_dump($this);
        return parent::_beforeSave();
    }

    public function _afterSave()
    {
        $a = $this;
        var_dump($this);
        return parent::_afterSave();
    }
}