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
 * @package         Payone_Api
 * @subpackage      Response
 * @author          Ronny Schröder
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 */
abstract class Payone_Api_Response_Parameter_Abstract implements Payone_Api_Response_Parameter_Interface
{

    /**
     * @param array $data
     */
    public function __construct(array $data = array()) 
    {
        if (count($data) > 0) {
            $this->init($data);
        }
    }

    /**
     * @param array $data
     */
    public function init(array $data = array()) 
    {
        foreach ($data as $key => $value) {
            $key = ucwords(str_replace('_', ' ', $key));
            $method = 'set' . str_replace(' ', '', $key);

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    /**
     * @return array
     */
    public function toArray() 
    {
        $result = array();
        foreach ($this as $key => $data) {
            if (!is_array($data) and ! is_object($data)) {
                $result[$key] = $data;
            } else if ($data instanceof Payone_Api_Response_Parameter_Interface) {
                /**
                 * @var Payone_Api_Request_Parameter_Interface $data
                 */
                $result = array_merge($result, $data->toArray());
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString() 
    {
        $stringArray = array();
        foreach ($this->toArray() as $key => $value) {
            if ($key instanceof Payone_Api_Response_Parameter_Interface) {
                $stringArray[] = $key->__toString();
            } else {
                $stringArray[] = $key . '=' . $value;
            }
        }

        $result = implode('|', $stringArray);
        return $result;
    }

}
