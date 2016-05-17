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
abstract class Payone_Core_Model_Service_Payment_Abstract
    extends Payone_Core_Model_Service_Abstract
    implements Payone_Core_Model_Service_Payment_Interface
{
    const EVENT_GROUP = 'payone_core_service_payment';

    /** @var Payone_Core_Model_Mapper_ApiRequest_Payment_Interface */
    protected $mapper = null;

    /**
     * @var Payone_Core_Model_Handler_Payment_Interface
     */
    protected $handler = null;

    /**
     * @param Payone_Api_Request_Interface $request
     * @return mixed
     */
    abstract protected function perform(Payone_Api_Request_Interface $request);

    /**
     * @return string
     */
    abstract public function getEventType();

    /**
     * @inheritdoc
     */
    public function execute(Mage_Sales_Model_Order_Payment $payment, $amount = 0.00)
    {
        $this->getMapper()->setAmount($amount);

        $request = $this->getMapper()->mapFromPayment($payment);

        $response = $this->perform($request);

        $this->getHandler()->setConfigStore($this->getConfigStore());
        $this->getHandler()->setPayment($payment);
        $this->getHandler()->setRequest($request);
        $this->getHandler()->handle($response);

        // Trigger Event
        $params = array(
            'request' => $request,
            'response' => $response,
            'payment_method' => $payment->getMethodInstance(),
            'payment' => $payment,
            'order' => $payment->getOrder()
        );
        $this->dispatchEvent($this->getEventGroup(), $params);
        $this->dispatchEvent($this->getEventName(), $params);
        $this->dispatchEvent($this->getEventName() . '_' . strtolower($response->getStatus()), $params);

        if ($response instanceof Payone_Api_Response_Error) {
            /** @var $response Payone_Api_Response_Error */
            $this->throwMageException($this->helper()->__('There has been an error processing your payment'));
        }

        return $response;
    }

    protected function getEventName()
    {
        return $this->getEventGroup() . '_' . $this->getEventType();
    }

    protected function getEventGroup()
    {
        return self::EVENT_GROUP;
    }

    /**
     * @param $message
     * @throws Mage_Core_Exception
     */
    protected function throwMageException($message)
    {
        Mage::throwException($message);
    }

    /**
     * @param $name
     * @param array $data
     *
     * @return Mage_Core_Model_App
     */
    protected function dispatchEvent($name, array $data = array())
    {
        return Mage::dispatchEvent($name, $data);
    }

    /**
     * @param Payone_Core_Model_Mapper_ApiRequest_Payment_Interface $mapper
     */
    public function setMapper(Payone_Core_Model_Mapper_ApiRequest_Payment_Interface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Payone_Core_Model_Mapper_ApiRequest_Payment_Interface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param Payone_Core_Model_Handler_Payment_Interface $handler
     */
    public function setHandler(Payone_Core_Model_Handler_Payment_Interface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Payone_Core_Model_Handler_Payment_Interface
     */
    public function getHandler()
    {
        return $this->handler;
    }
}