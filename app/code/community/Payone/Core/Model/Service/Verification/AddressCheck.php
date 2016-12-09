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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Service_Verification_AddressCheck
    extends Payone_Core_Model_Service_Verification_Abstract
{
    protected $prefix = 'payone_addresscheck';

    /** @var Payone_Api_Service_Verification_AddressCheck */
    protected $serviceApiAddressCheck = null;

    /** @var Payone_Core_Model_Mapper_ApiRequest_Verification_AddressCheck */
    protected $mapper = null;

    /** @var Payone_Core_Model_Handler_Verification_AddressCheck */
    protected $handler = null;

    /** @var Payone_Core_Model_Config_Protect_AddressCheck */
    protected $config = null;

    /**
     * @param Mage_Customer_Model_Address_Abstract $address
     * @param Varien_Object $errors
     * @param Mage_Sales_Model_Quote $quote
     *
     * @return Payone_Api_Response_AddressCheck_Invalid|Payone_Api_Response_AddressCheck_Valid|Payone_Api_Response_Error
     */
    public function execute(Mage_Customer_Model_Address_Abstract $address, Varien_Object $errors, Mage_Sales_Model_Quote $quote)
    {
        $handler = $this->getHandler();
        $handler->setAddress($address);
        $handler->setErrors($errors);

        if ($quote && $this->isRequiredForQuote($quote) === false) {
            return true;
        }

        if ($this->getSavedScore($address, $this->getConfig()->getResultLifetimeInSeconds())) {
            // Valid, saved score exists, we can skip the API request.
            return;
        }

        $request = $this->getMapper()->mapFromAddress($address);

        try {
            $response = $this->getServiceApiAddressCheck()->check($request);
            $handler->handle($response);
        }
        catch (Exception $ex) {
            $handler->handleException($ex);
        }
    }


    /**
     * @param Payone_Api_Service_Verification_AddressCheck $serviceApiAddressCheck
     */
    public function setServiceApiAddressCheck(Payone_Api_Service_Verification_AddressCheck $serviceApiAddressCheck)
    {
        $this->serviceApiAddressCheck = $serviceApiAddressCheck;
    }

    /**
     * @return Payone_Api_Service_Verification_AddressCheck
     */
    public function getServiceApiAddressCheck()
    {
        return $this->serviceApiAddressCheck;
    }

    /**
     * @param Payone_Core_Model_Mapper_ApiRequest_Verification_AddressCheck $mapper
     */
    public function setMapper(Payone_Core_Model_Mapper_ApiRequest_Verification_AddressCheck $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Payone_Core_Model_Mapper_ApiRequest_Verification_AddressCheck
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param Payone_Core_Model_Handler_Verification_AddressCheck $handler
     */
    public function setHandler(Payone_Core_Model_Handler_Verification_AddressCheck $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Payone_Core_Model_Handler_Verification_AddressCheck
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_AddressCheck $config
     */
    public function setConfig(Payone_Core_Model_Config_Protect_AddressCheck $config)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_Core_Model_Config_Protect_AddressCheck
     */
    public function getConfig()
    {
        return $this->config;
    }

}