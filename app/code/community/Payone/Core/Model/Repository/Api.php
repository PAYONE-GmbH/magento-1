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
 * @subpackage      Repository
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Repository
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Repository_Api
    implements Payone_Api_Persistence_Interface
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /** @var Payone_Core_Helper_Data */
    protected $helper = null;

    const KEY = 'p1_magento_api';

    /**
     * @return string
     */
    public function getKey()
    {
        return self::KEY;
    }

    /**
     * @param Payone_Api_Request_Interface $request
     * @param Payone_Api_Response_Interface $response
     * @return boolean
     */
    public function save(
        Payone_Api_Request_Interface $request,
        Payone_Api_Response_Interface $response)
    {
        $domainObject = $this->getFactory()->getModelApi();
        $domainObject->setData($request->toArray());
        $domainObject->setRawRequest($request->__toString());
        $domainObject->setRawResponse($response->getRawResponseToString());
        $domainObject->setResponse($response->getStatus());
        $domainObject->save();
    }

    /**
     * @param Payone_Api_Request_Interface $request
     * @param Exception
     * @return boolean
     */
    public function saveException(Payone_Api_Request_Interface $request, Exception $ex)
    {
        $domainObject = $this->getFactory()->getModelApi();
        $domainObject->setData($request->toArray());
        $domainObject->setRawRequest($request->__toString());
        $domainObject->setStacktrace($ex->getTraceAsString());
        $domainObject->setResponse(Payone_Core_Model_System_Config_ResponseType::EXCEPTION);
        $domainObject->save();
    }

    /**
     * @return Payone_Core_Helper_Data
     */
    protected function helper()
    {
        if ($this->helper === null) {
            $this->helper = Mage::helper('payone_core');
        }
        return $this->helper;
    }

    /**
     * @param Payone_Core_Helper_Data $helper
     */
    public function setHelper(Payone_Core_Helper_Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }
        return $this->factory;
    }

}