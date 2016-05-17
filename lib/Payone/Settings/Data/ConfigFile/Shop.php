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
class Payone_Settings_Data_ConfigFile_Shop
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'shop';

    /** @var string */
    protected $code = '';

    /** @var string */
    protected $name = '';

    /** @var Payone_Settings_Data_ConfigFile_Shop_System */
    protected $system = null;

    /** @var Payone_Settings_Data_ConfigFile_Shop_Global */
    protected $global = null;

    /** @var Payone_Settings_Data_ConfigFile_Shop_ClearingTypes */
    protected $clearingtypes = null;

    /** @var Payone_Settings_Data_ConfigFile_Shop_Protect */
    protected $protect = null;

    /** @var Payone_Settings_Data_ConfigFile_Shop_Misc */
    protected $misc = null;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_System $system
     */
    public function setSystem($system)
    {
        $this->system = $system;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Shop_System
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Global $global
     */
    public function setGlobal($global)
    {
        $this->global = $global;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Shop_Global
     */
    public function getGlobal()
    {
        return $this->global;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Protect $protect
     */
    public function setProtect($protect)
    {
        $this->protect = $protect;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Shop_Protect
     */
    public function getProtect()
    {
        return $this->protect;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Misc $misc
     */
    public function setMisc($misc)
    {
        $this->misc = $misc;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Shop_Misc
     */
    public function getMisc()
    {
        return $this->misc;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_ClearingTypes $clearingtypes
     */
    public function setClearingtypes($clearingtypes)
    {
        $this->clearingtypes = $clearingtypes;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Shop_ClearingTypes
     */
    public function getClearingtypes()
    {
        return $this->clearingtypes;
    }
}
