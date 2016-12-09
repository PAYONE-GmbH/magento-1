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
class Payone_Settings_Data_ConfigFile_Root
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'config';

    /** @var Payone_Settings_Data_ConfigFile_Shop[] */
    protected $shop = array();



    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $shop
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
    }

    /**
     * @return array|Payone_Settings_Data_ConfigFile_Shop[]
     */
    public function getShop()
    {
        return $this->shop;
    }

    public function addShop($shop)
    {
        return $this->shop[]=$shop;
    }
}
