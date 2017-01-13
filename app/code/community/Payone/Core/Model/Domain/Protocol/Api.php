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
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 *
 * @method setCreatedAt(string $dateTime)
 * @method string getCreatedAt()
 * @method setUpdatedAt(string $dateTime)
 * @method string getUpdatedAt()
 * @method int getId()
 * @method setId(int $id)
 * @method int getId()
 * @method setRawResponse(string $rawResponse)
 * @method string getRawResponse()
 * @method setRawRequest(string $rawRequest)
 * @method string getRawRequest()
 * @method setResponse(string $response)
 * @method string getResponse()
 * @method setRequest(string $Request)
 * @method string getRequest()
 * @method setStacktrace(string $message)
 * @method setStoreId(int $storeid)
 * @method int getStoreId()
 * @method string getStacktrace()
 * @method setId(int $id)
 * @method setOrderId(int $orderid)
 * @method int getOrderId()
 */
class Payone_Core_Model_Domain_Protocol_Api extends Mage_Core_Model_Abstract
{

    protected $_eventPrefix = 'payone_core_api_protocol';

    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/protocol_api');
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_Api
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        if ($this->isObjectNew()) {
            $this->setCreatedAt(date('Y-m-d H:i:s'));
        }
        else {
            $this->setUpdatedAt(date('Y-m-d H:i:s'));
        }

        return $this;
    }

    /**
     * Returns the raw request to Payone as an array
     * @return array
     */
    public function getRawRequestAsArray()
    {
        $rawRequest = $this->getRawRequest();
        $preparedRawRequest = $this->prepareData($rawRequest);

        ksort($preparedRawRequest);

        return $preparedRawRequest;
    }

    /**
     * Returns the raw request to Payone as an array
     * @return array
     */
    public function getRawResponseAsArray()
    {
        $rawResponse = $this->getRawResponse();
        $preparedRawResponse = $this->prepareData($rawResponse);
        ksort($preparedRawResponse);

        return $preparedRawResponse;
    }

    /**
     * @param $data
     * @return array
     */
    protected function prepareData($data)
    {
        if (!is_array($data)) {
            $data = explode('|', $data);
        }

        $preparedData = array();
        foreach ($data as $key => $value) {
            $valuearr = explode('=', $value);
            if (isset($valuearr[1]) && $valuearr[1] !=='') {
                $preparedData[$valuearr[0]] = $valuearr[1];
            }
        }

        return $preparedData;
    }
}