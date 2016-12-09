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
abstract class Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    /**
     * @abstract
     * @return array
     */
    public function toArray()
    {
        $array = array();


        foreach ($this as $key => $data)
        {
            if ($data === null || $key == 'key') {
                continue;
            }

            if ($data instanceof Payone_Settings_Data_ConfigFile_Interface) {
                /** @var Payone_Api_Request_Parameter_Interface $data */
                $array[$key] = $data->toArray();
            }
            elseif (is_array($data))
            {
                foreach ($data as $innerKey => $innerValue)
                {
                    if ($innerValue instanceof Payone_Settings_Data_ConfigFile_Interface) {
                        /** @var Payone_Api_Request_Parameter_Interface $innerValue */
                        $array[$key][$innerValue->getKey()] = $innerValue->toArray();
                    }
                    else {
                        $array[$key][$innerKey] = $innerValue;
                    }
                }
            }
            else {
                $array[$key] = $data;
            }
        }

        return $array;
    }
}
