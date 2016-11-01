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
 * @package         Payone_Core_Model_Service_Abstract
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@payone.de> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model_Service_Abstract
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 *
 */
class Payone_Core_Model_Service_TransactionStatus_StoreClearingParameters
    extends Payone_Core_Model_Service_Abstract
{
    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     * @param Mage_Sales_Model_Order $order
     * @return void
     */
    public function execute(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus, Mage_Sales_Model_Order $order = null)
    {

        if(!$transactionStatus->isAppointed())
            return;

        if(is_null($order))
        {
            $order = $this->getFactory()->getModelSalesOrder();
            $order->load($transactionStatus->getOrderId());
        }

        $payment = $order->getPayment();
        $methodInstance = $payment->getMethodInstance();

        // Clearing params need only to be mapped on SafeInvoice with request mode "authorization" abort otherwise.
        if (!($methodInstance instanceof Payone_Core_Model_Payment_Method_SafeInvoice)) {
            return;
        }


        $config = $this->helperConfig()->getConfigPaymentMethodByOrder($order);


        if (!$config->isRequestAuthorization()) {
            return;
        }

        $payment->setPayoneClearingBankAccountholder($transactionStatus->getClearingBankaccountholder());
        $payment->setPayoneClearingBankCountry($transactionStatus->getClearingBankcountry());
        $payment->setPayoneClearingBankAccount($transactionStatus->getClearingBankaccount());
        $payment->setPayoneClearingBankCode($transactionStatus->getClearingBankcode());
        $payment->setPayoneClearingBankIban($transactionStatus->getClearingBankiban());
        $payment->setPayoneClearingBankBic($transactionStatus->getClearingBankbic());
        $payment->setPayoneClearingBankCity($transactionStatus->getClearingBankcity());
        $payment->setPayoneClearingBankName($transactionStatus->getClearingBankname());
        $payment->setPayoneClearingReference($transactionStatus->getClearingReference());
        $payment->setPayoneClearingInstructionnote($transactionStatus->getClearingInstructionnote());
        $payment->setPayoneClearingLegalnote($transactionStatus->getClearingLegalnote());
        $payment->setPayoneClearingDuedate($transactionStatus->getClearingDuedate());

        $payment->save();

    }
}