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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_Config_AreaAbstract implements Payone_Core_Model_Config_AreaInterface
{
    public function __construct(array $data = array())
    {
        if (count($data)) {
            $this->init($data);
        }
    }

    /**
     * this method is used to initialize an empty / fresh instance
     * and furthermore it is used to to adjust specific parameters from an array
     *
     * if you want the instance to be cleared you should create a new instance
     *
     * @param array $data
     */
    public function init(array $data)
    {
        foreach ($data as $key => $value) {
            $this->setValue($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setValue($key, $value)
    {
        $method = 'set' . uc_words($key, '');

        if (method_exists($this, $method)) {
            $this->{$method}($value);
        }
    }
    /**
     * @param string $type
     * @return array|null
     */
    public function getValue($type)
    {
        $method = 'get' . uc_words($type, '');
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        return null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $data) {
            if ($data === null) {
                continue;
            }
            if ($data instanceof Payone_Core_Model_Config_AreaInterface) {
                /**
                 * @var Payone_Core_Model_Config_AreaInterface $data
                 */
                $result = array_merge($result, $data->toArray());
            }
            else {
                $result[$key] = $data;
            }
        }

        ksort($result);

        return $result;
    }

}