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
class Payone_Settings_Data_ConfigFile_Shop_System
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'system';

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $version = '';

    /** @var string */
    protected $edition = '';

    /** @var array */
    protected $modules = array();


    /**
     * @param string $edition
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;
    }

    /**
     * @return string
     */
    public function getEdition()
    {
        return $this->edition;
    }

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
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param array $modules
     */
    public function setModules(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * Array with
     * Key = Module Name
     * Value = Module Version
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @param $name
     * @param $version
     */
    public function addModule($name, $version)
    {
        $this->modules[$name] = $version;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

}
