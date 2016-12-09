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
class Payone_Core_Model_Service_InitializePayment
    extends Payone_Core_Model_Service_Abstract
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * @var Payone_Core_Model_Config_Payment_Method_Interface
     */
    protected $configPaymentMethod = null;

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Payone_Api_Response_Authorization_Approved|Payone_Api_Response_Authorization_Redirect|Payone_Api_Response_Error
     * @throws Payone_Core_Exception_InvalidRequestType
     */
    public function execute(Mage_Sales_Model_Order_Payment $payment)
    {
        $config = $this->getConfigPaymentMethod();

        /** @var $service Payone_Core_Model_Service_Payment_Interface */
        $service = null;

        if ($config->isRequestAuthorization()) {
            $service = $this->getFactory()->getServicePaymentAuthorize($config);
        }
        elseif ($config->isRequestPreauthorization()) {
            $service = $this->getFactory()->getServicePaymentPreauthorize($config);
        }
        else {
            $msg = 'Invalid request type configured: "' . $config->getRequestType() . '"';
            throw new Payone_Core_Exception_InvalidRequestType($msg);
        }

        $service->setConfigStore($this->getConfigStore());

        $response = $service->execute($payment);
        return $response;
    }

    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $configPaymentMethod
     */
    public function setConfigPaymentMethod(Payone_Core_Model_Config_Payment_Method_Interface $configPaymentMethod)
    {
        $this->configPaymentMethod = $configPaymentMethod;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfigPaymentMethod()
    {
        return $this->configPaymentMethod;
    }


}