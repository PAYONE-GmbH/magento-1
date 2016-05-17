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
class Payone_Settings_Data_ConfigFile_Shop_Protect
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'protect';

    /** @var Payone_Settings_Data_ConfigFile_Protect_Consumerscore */
    protected $consumerscore = null;

    /** @var Payone_Settings_Data_ConfigFile_Protect_Addresscheck */
    protected $addresscheck = null;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Protect_Addresscheck $addresscheck
     */
    public function setAddresscheck($addresscheck)
    {
        $this->addresscheck = $addresscheck;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Protect_Addresscheck
     */
    public function getAddresscheck()
    {
        return $this->addresscheck;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Protect_Consumerscore $consumerscore
     */
    public function setConsumerscore($consumerscore)
    {
        $this->consumerscore = $consumerscore;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Protect_Consumerscore
     */
    public function getConsumerscore()
    {
        return $this->consumerscore;
    }
}
