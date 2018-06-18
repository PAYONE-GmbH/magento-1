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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Model_Service_Mastercard_Masterpass_Response_PlaceOrderOkResponse
    implements Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface
{
    /**
     * @var int
     */
    protected $code = Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::PLACE_ORDER_OK_RESPONSE_CODE;

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @return string
     */
    public function getType()
    {
        return Payone_Core_Model_Service_Mastercard_Masterpass_ResponseInterface::PLACE_ORDER_OK_RESPONSE_TYPE;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getData($key = null)
    {
        if ($key == null) {
            return $this->data;
        }

        if (!isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $data = array();
        $data['code'] = $this->code;
        $data['data'] = $this->data;

        return json_encode($data);
    }
}