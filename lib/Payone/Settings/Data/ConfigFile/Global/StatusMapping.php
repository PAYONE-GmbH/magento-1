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
class Payone_Settings_Data_ConfigFile_Global_StatusMapping
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'status_mapping';

    /** @var Payone_Settings_Data_ConfigFile_Global_StatusMapping[] */
    protected $status_mapping = array();

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $aData
     */
    public function addStatusMapping($sClearingType, $aData)
    {
        if (array_key_exists($sClearingType, $this->status_mapping) !== false && count($this->status_mapping[$sClearingType]) > 0) {
            $this->status_mapping[$sClearingType] = array_merge($this->status_mapping[$sClearingType], $aData);
        } else {
            $this->status_mapping[$sClearingType] = $aData;
        }
    }

    /**
     * @param array $status_mapping
     */
    public function setStatusMapping($status_mapping)
    {
        $this->status_mapping = $status_mapping;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Global_StatusMapping[]
     */
    public function getStatusMapping()
    {
        return $this->status_mapping;
    }
}
