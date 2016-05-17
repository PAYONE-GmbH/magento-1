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
 * @subpackage      Request
 * @author          Ronny Schröder
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2014 
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 */
class Payone_Api_Request_Parameter_Paydata_DataItem
    extends Payone_Api_Request_Parameter_Abstract
{
    /**
     * @var string
     */
    protected $key = NULL;
    /**
     * @var string
     */
    protected $data = NULL;

    /**
     * @param int $key
     * @return array
     */
    public function toArray()
    {
        $data = array();
        $data['add_paydata[' . $this->key . ']'] = $this->getData();
        return $data;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

}
