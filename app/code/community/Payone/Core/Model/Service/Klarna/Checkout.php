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
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Service_Klarna_Checkout
{
    /**
     * @var \Mage_Checkout_Model_Session|null
     */
    protected $checkoutSession = null;

    /**
     * @var \Mage_Customer_Model_Session|null
     */
    protected $customerSession = null;

    /**
     * @var \Payone_Core_Model_Config_Payment_Method|null
     */
    protected $config = null;

    /**
     * @var \Mage_Sales_Model_Quote|null
     */
    protected $quote = null;

    /**
     * @var string|null
     */
    protected $workOrderId = null;

    /**
     * @var \Payone_Core_Model_Factory|null
     */
    protected $factory = null;

    /**
     * @param array $params
     * @throws \Exception
     */
    public function __construct($params = [])
    {
        if (isset($params['quote']) && $params['quote'] instanceof \Mage_Sales_Model_Quote) {
            $this->quote = $params['quote'];
        } else {
            throw new \Exception('Quote object is required.');
        }
        if (isset($params['config']) && $params['config'] instanceof \Payone_Core_Model_Config_Payment_Method) {
            $this->config = $params['config'];
        } else {
            throw new \Exception('Configuration object is required.');
        }
        $this->checkoutSession = Mage::getSingleton('checkout/session');
        $this->customerSession = Mage::getSingleton('customer/session');
    }

    /**
     * MAGE-438 : Start Klarna checkout session
     *
     * @return Payone_Api_Response_Error|Payone_Api_Response_Genericpayment_Approved|Payone_Api_Response_Genericpayment_Redirect
     */
    public function checkoutStartSession($dobParam)
    {
        $params = array(
            'action' => \Payone_Api_Enum_GenericpaymentAction::KLARNA_START_SESSION,
            'quote' => $this->quote,
            'method' => $this->config->getCode()
        );

        if (!empty($dobParam)) {
            $params['dob'] = (new DateTime($dobParam))->format('Ymd');
        } else {
            if (!empty($this->quote->getCustomerDob())) {
                $params['dob'] = (new DateTime($this->quote->getCustomerDob()))->format('Ymd');
            }
        }

        $service = $this->getFactory()->getServicePaymentGenericpayment($this->config);
        /** @var \Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment $mapper */
        $mapper = $service->getMapper();
        $request = $mapper->requestKlarnaStartSession($params);
        $this->checkCurrencyConversion($request);

        $response = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($request);

        if ($response instanceof \Payone_Api_Response_Genericpayment_Ok) {
            $this->workOrderId = $response->getWorkorderId();
            $paydata = $response->getPaydata()->toAssocArray();
            $this->checkoutSession->setData('klarna_client_token', $paydata['client_token']);
            $this->checkoutSession->setData('klarna_session_id', $paydata['session_id']);
            $this->checkoutSession->setData('klarna_workorderid', $response->getWorkorderId());
        }

        return $response;
    }

    /**
     * @return \Payone_Core_Model_Factory
     */
    private function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = Mage::getModel('payone_core/factory');
        }

        return $this->factory;
    }

    /**
     * @param Payone_Api_Request_Genericpayment $request
     */
    private function checkCurrencyConversion(Payone_Api_Request_Genericpayment $request)
    {
        if($this->config->getCurrencyConvert()) {
            $request->setCurrency($this->quote->getBaseCurrencyCode());
            $request->setAmount($this->quote->getBaseGrandTotal());
        }
    }
}
