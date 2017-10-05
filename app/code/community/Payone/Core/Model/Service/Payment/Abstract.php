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
     * Existing Payone error codes mapped to their Amazon error codes
     *
     * @var array
     */
    protected $_aAmazonErrors = array(
        109 => 'AmazonRejected',
        900 => 'UnspecifiedError',
        980 => 'TransactionTimedOut',
        981 => 'InvalidPaymentMethod',
        982 => 'AmazonRejected',
        983 => 'ProcessingFailure',
        984 => 'BuyerEqualsSeller',
        985 => 'PaymentMethodNotAllowed',
        986 => 'PaymentPlanNotSet',
        987 => 'ShippingAddressNotSet'
    );

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
    public function execute(Mage_Sales_Model_Order_Payment $payment, $amount = 0.00, $isRetry = false)
    {
        $this->getMapper()->setAmount($amount);

        $request = $this->getMapper()->mapFromPayment($payment);

        $response = $this->perform($request);

        $this->getHandler()->setConfigStore($this->getConfigStore());
        $this->getHandler()->setPayment($payment);
        $this->getHandler()->setRequest($request);
        $this->getHandler()->handle($response);

        /** @var Payone_Core_Model_Payment_Method_Abstract $oMethodInstance */
        $oMethodInstance = $payment->getMethodInstance();

        // Trigger Event
        $params = array(
            'request' => $request,
            'response' => $response,
            'payment_method' => $oMethodInstance,
            'payment' => $payment,
            'order' => $payment->getOrder()
        );
        $this->dispatchEvent($this->getEventGroup(), $params);
        $this->dispatchEvent($this->getEventName(), $params);
        $this->dispatchEvent($this->getEventName() . '_' . strtolower($response->getStatus()), $params);

        /** @var Payone_Core_Model_Session $session */
        $session = Mage::getSingleton('payone_core/session');
        if ($response instanceof Payone_Api_Response_Error) {
            if (!$isRetry && $session->getData('amazon_retry_async') && $response->getErrorcode() == 980) {
                // Retry the transaction in asynchronous mode
                $response = $this->execute($payment, $amount, true);
            } elseif (array_key_exists($response->getErrorcode(), $this->_aAmazonErrors) !== false) {
                $session->unsetData('amazon_retry_async');
                throw new Payone_Api_Exception_InvalidParameters($this->_aAmazonErrors[$response->getErrorcode()], $response->getErrorcode());
            } else {
                $session->unsetData('amazon_retry_async');
                /** @var Payone_Api_Response_Error $response */
                $this->throwMageException($this->helper()->__(
                    "Sorry, your transaction with Amazon Pay was not successful. Please choose another payment method."
                    //$oMethodInstance->getApiResponseErrorMessage($response)
                ));
            }
        } elseif ($request instanceof Payone_Api_Request_Authorization_Abstract &&
            $oMethodInstance->getCode() == 'payone_amazon_pay' &&
            $request->getPayment() &&
            $request->getPayment()->getPaydata() &&
            $request->getPayment()->getPaydata()->toArray()['add_paydata[amazon_timeout]'] > 0
        ) {
            $message = $this->helper()->__(
                'Your transaction with Amazon Pay is currently being validated. ' .
                'Please be aware that we will inform you shortly as needed.'
            );
            /** @var \Mage_Checkout_Model_Session $checkoutSession */
            $checkoutSession = Mage::getSingleton('checkout/session');
            $checkoutSession->addNotice($message);
        }
        $session->unsetData('amazon_retry_async');
        $session->unsetData('amazon_reference_id');
        $session->unsetData('amazon_lock_order');

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
