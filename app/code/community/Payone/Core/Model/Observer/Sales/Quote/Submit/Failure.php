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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Core_Model_Observer_Sales_Quote_Submit_Failure
    extends Payone_Core_Model_Observer_Abstract
{
    protected $aRestrictableMethods = array(
        Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAY => array(307),
        Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYINVOICING => array(307),
        Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYDIRECTDEBIT => array(307), // Add Ratepay-Lastschrift support (MAGE-317 23.03.2018)
        Payone_Core_Model_System_Config_PaymentMethodCode::PAYMENTGUARANTEEINVOICE => array(351)
    );

    /**
     * Retrieves api log entry from session and writes it to the db
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function protocol(Varien_Event_Observer $observer)
    {
        $oSession = Mage::getSingleton('checkout/session');

        /** @var $domainObject Payone_Core_Model_Repository_Api */
        $domainObject = $oSession->getPayoneApiLogEntry();
        if ($domainObject) {
            $domainObject->save();
            $oSession->unsPayoneApiLogEntry();
        }
    }

    /**
     * MAGE-449: function moved here from app/code/community/Payone/Core/Model/Service/Payment/Abstract.php
     * Handle payment ban for 24h or 48h depending on payment method and error code
     *
     * @param Varien_Event_Observer $observer
     */
    public function handlePaymentBan(Varien_Event_Observer $observer)
    {
        $checkoutSession = Mage::getSingleton('checkout/session');
        $errorCode = $checkoutSession->getData('payone_ban_last_error_code');

        $payment = $observer->getQuote()->getPayment();
        $customerId = !is_null($observer->getOrder()->getCustomerId()) ? $observer->getOrder()->getCustomerId() : '';

        if (isset($this->aRestrictableMethods[$payment->getMethod()])) {
            if (in_array($errorCode, $this->aRestrictableMethods[$payment->getMethod()])) {

                $restrictionDelay = '+1day';

                /**
                 * MAGE-449 increase delay to 48h for Ratepay and code 307
                 */
                if (
                    ($payment->getMethod() == Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYINVOICING ||
                        $payment->getMethod() == Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAY ||
                        $payment->getMethod() == Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYDIRECTDEBIT)
                    && $errorCode == 307
                ) {

                    /**
                     * Raise the checkout flag for guest checkout cases
                     */
                    $checkoutSession->setData('ratepay_checkout_banned', true);

                    /**
                     * Register ban for 48h for the 3 methods
                     */
                    $restrictionDelay = '+2days';
                    if (!empty($customerId)) {
                        $this->registerPaymentBan(
                            Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYINVOICING,
                            $customerId,
                            $restrictionDelay
                        );

                        $this->registerPaymentBan(
                            Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAY,
                            $customerId,
                            $restrictionDelay
                        );

                        $this->registerPaymentBan(
                            Payone_Core_Model_System_Config_PaymentMethodCode::RATEPAYDIRECTDEBIT,
                            $customerId,
                            $restrictionDelay
                        );
                    }
                } else {
                    if (!empty($customerId)) {
                        $this->registerPaymentBan($payment->getMethod(), $customerId, $restrictionDelay);
                    }
                }
            }
        }
        $checkoutSession->unsetData('payone_ban_last_error_code');
    }

    /**
     * @param string $paymentMethod
     * @param int $customerId
     * @param string $restrictionDelay
     * @throws Exception
     */
    protected function registerPaymentBan($paymentMethod, $customerId, $restrictionDelay = '+1day')
    {
        /** @var Payone_Core_Model_Domain_PaymentBan $oPaymentBan */
        $oPaymentBan = Mage::getModel('payone_core/domain_paymentBan');
        $oPaymentBan = $oPaymentBan->loadByCustomerIdPaymentMethod($customerId, $paymentMethod);
        if (empty($oPaymentBan->getId())) {

        }

        $oPaymentBan->setCustomerId($customerId);
        $oPaymentBan->setPaymentMethod($paymentMethod);
        $oPaymentBan->setFromDate((new DateTime())->format(DATE_ISO8601));
        $oPaymentBan->setToDate((new DateTime($restrictionDelay))->format(DATE_ISO8601));
        $oPaymentBan->save();
    }
}
