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
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Observer_Sales_Order
    extends Payone_Core_Model_Observer_Abstract
{

    /**
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function updateOrderGrid(Varien_Event_Observer $observer)
    {
        /**
         * @var $resource Mage_Sales_Model_Mysql4_Order
         */
        $resource = $observer->getEvent()->getResource();

        $resource->addVirtualGridColumn(
            'payone_payment_method',
            'order_payment',
            array('entity_id' => 'parent_id'),
            'method'
        );
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function incrementSampleCounter(Varien_Event_Observer $observer)
    {
        $storeId = $observer->getEvent()->getOrder()->getStoreId();

        $this->helperConfig()->incrementCreditratingSampleCounter($storeId);
    }

    /**
     *
     * @param Varien_Event_Observer $observer (has data 'payment' with a payment info instance (Mage_Sales_Model_Order_Payment))
     */
    public function cancelPayment(Varien_Event_Observer $observer)
    {
        /** @var $payment Mage_Sales_Model_Order_Payment */
        $payment = $observer->getPayment();

        $methodInstance = $payment->getMethodInstance();

        if ($methodInstance instanceof Payone_Core_Model_Payment_Method_Abstract) {
            $methodInstance->cancel($payment);
        }
    }

    /**
     *
     * @param Varien_Event_Observer $observer (has data 'payment' with a payment info instance (Mage_Sales_Model_Order_Payment))
     */
    public function paymentPlaceEnd(Varien_Event_Observer $observer)
    {
        /** @var $payment Mage_Sales_Model_Order_Payment */
        $payment = $observer->getEvent()->getPayment();

        if(!$payment->getOrder()->getCustomerIsGuest()) {
            $customer = $payment->getOrder()->getCustomer();
            if($customer && $customer->getId()) {
                $customer->setPayoneLastPaymentMethod($payment->getMethod());
                $customer->getResource()->saveAttribute($customer, 'payone_last_payment_method');
            }
        }

        if(($payment->getMethodInstance() instanceof Payone_Core_Model_Payment_Method_Abstract) && (!$payment->getOrder()->getCustomerIsGuest())) {
            $customerId = $payment->getOrder()->getCustomer()->getId();
            $customerSavedData = array();
            $paymentMethodCode = '';
            if($payment->getMethodInstance()->getCode() == Payone_Core_Model_System_Config_PaymentMethodCode::DEBITPAYMENT) {
                $paymentMethodCode = $payment->getMethodInstance()->getCode();
                $customerSavedData['payone_account_number'] = $payment->getPayoneAccountNumber()?$payment->getPayoneAccountNumber():'';
                $customerSavedData['payone_bank_code']      = $payment->getPayoneBankCode()?$payment->getPayoneBankCode():'';
                $customerSavedData['payone_sepa_iban']      = $payment->getPayoneSepaIban()?$payment->getPayoneSepaIban():'';
                $customerSavedData['payone_sepa_bic']       = $payment->getPayoneSepaBic()?$payment->getPayoneSepaBic():'';
                $customerSavedData['payone_bank_country']   = $payment->getPayoneBankCountry();
            }
            if($payment->getMethodInstance()->getCode() == Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFER) {
                $paymentMethodCode = $payment->getMethodInstance()->getCode();
                $customerSavedData['payone_onlinebanktransfer_type'] = $payment->getPayoneOnlinebanktransferType();
                $customerSavedData['payone_account_number'] = $payment->getPayoneAccountNumber()?$payment->getPayoneAccountNumber():'';
                $customerSavedData['payone_bank_code']      = $payment->getPayoneBankCode()?$payment->getPayoneBankCode():'';
                $customerSavedData['payone_sepa_iban']      = $payment->getPayoneSepaIban()?$payment->getPayoneSepaIban():'';
                $customerSavedData['payone_sepa_bic']       = $payment->getPayoneSepaBic()?$payment->getPayoneSepaBic():'';
                $customerSavedData['payone_bank_group']     = $payment->getPayoneBankGroup();
            }
            if($payment->getMethodInstance()->getCode() == Payone_Core_Model_System_Config_PaymentMethodCode::CREDITCARD) {
                $paymentMethodCode = $payment->getMethodInstance()->getCode();
                $customerSavedData['cc_owner'] = $payment->getCcOwner();
                $customerSavedData['cc_type'] = $payment->getCcType();
                $customerSavedData['cc_exp_year'] = $payment->getCcExpYear();
                $customerSavedData['cc_exp_month'] = $payment->getCcExpMonth();
                $customerSavedData['cc_number_enc'] = $payment->getCcNumberEnc();
                $customerSavedData['payone_pseudocardpan'] = $payment->getPayonePseudocardpan();
                $customerSavedData['payone_config_payment_method_id'] = $payment->getPayoneConfigPaymentMethodId();
            }

            if(!empty($paymentMethodCode)) {
                $paymentCustomerModel = Mage::getModel('payone_core/domain_customer')->loadByCustomerIdPaymentCode($customerId, $paymentMethodCode);
                $paymentCustomerModel->setCustomerId($customerId);
                $paymentCustomerModel->setCode($paymentMethodCode);
                $paymentCustomerModel->setCustomerData($customerSavedData);
                $paymentCustomerModel->save();
//                Mage::log($paymentMethodCode, null, 'test.log', true);
            }
        }


    }
}