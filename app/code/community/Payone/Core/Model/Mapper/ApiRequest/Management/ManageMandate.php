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
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Core_Model_Mapper_ApiRequest_Management_ManageMandate
    extends Payone_Core_Model_Mapper_ApiRequest_Abstract
{
    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $paymentConfig;

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @param $bankcountry
     * @param $bankaccount
     * @param $bankcode
     * @param $bic
     * @param $iban
     * @return \Payone_Api_Request_ManageMandate
     */
    public function mapByQuote(Mage_Sales_Model_Quote $quote, $bankcountry, $bankaccount, $bankcode, $bic, $iban)
    {
        $paymentConfig = $this->getPaymentConfig();
        $helper = $this->helper();

        $request = $this->getFactory()->getRequestManagementManageMandate();

        // common parameters
        $request->setMid($paymentConfig->getMid());
        $request->setPortalid($paymentConfig->getPortalid());
        $request->setKey($paymentConfig->getKey());
        $request->setMode($paymentConfig->getMode());
        $request->setRequest(Payone_Api_Enum_RequestType::MANAGEMANDATE);
        $request->setEncoding('UTF-8');
        $request->setIntegratorName('Magento');
        $request->setIntegratorVersion($helper->getMagentoVersion());
        $request->setSolutionName('fatchip');
        $request->setSolutionVersion($helper->getPayoneVersion());

        // special parameters
        $request->setAid($paymentConfig->getAid());
        $request->setClearingtype(Payone_Enum_ClearingType::DEBITPAYMENT); // only allowed for debit_payment
        $request->setCurrency($quote->getQuoteCurrencyCode());

        $request->setPersonalData($this->mapPersonalData($quote));
        $request->setPayment($this->mapBankData($bankcountry, $bankaccount, $bankcode, $bic, $iban));

        if($paymentConfig->getCurrencyConvert()) {
            $request->setCurrency($quote->getBaseCurrencyCode());
        }

        return $request;
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return Payone_Api_Request_Parameter_ManageMandate_PersonalData
     */
    protected function mapPersonalData(Mage_Sales_Model_Quote $quote)
    {
        $billingAddress = $quote->getBillingAddress();
        $helper = $this->helper();

        $personalData = new Payone_Api_Request_Parameter_ManageMandate_PersonalData();

        if ($quote->getCustomerId()) {
            $personalData->setCustomerid($quote->getCustomerId());
        }

        $personalData->setLastname($billingAddress->getLastname());
        $personalData->setFirstname($billingAddress->getFirstname());
        if ($billingAddress->getCompany()) {
            $personalData->setCompany($billingAddress->getCompany());
        }

        $personalData->setStreet($helper->normalizeStreet($billingAddress->getStreet()));
        $personalData->setZip($billingAddress->getPostcode());
        $personalData->setCity($billingAddress->getCity());
        $personalData->setCountry($billingAddress->getCountry());
        $personalData->setEmail($billingAddress->getEmail());
        $personalData->setLanguage($helper->getDefaultLanguage());

        return $personalData;
    }

    /**
     * @param $bankcountry
     * @param $bankaccount
     * @param $bankcode
     * @param $bic
     * @param $iban
     * @return Payone_Api_Request_Parameter_ManageMandate_PaymentMethod_BankAccount
     */
    protected function mapBankData($bankcountry, $bankaccount, $bankcode, $bic, $iban)
    {
        $bankData = new Payone_Api_Request_Parameter_ManageMandate_PaymentMethod_BankAccount();
        $bankData->setBankcountry($bankcountry);
        $bankData->setBankaccount($bankaccount);
        $bankData->setBankcode($bankcode);
        $bankData->setBic(strtoupper($bic));
        $bankData->setIban(strtoupper($iban)); // ensure bic & iban are sent uppercase

        return $bankData;
    }

    /**
     * @param \Payone_Core_Model_Config_Payment_Method_Interface $paymentConfig
     */
    public function setPaymentConfig($paymentConfig)
    {
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @return \Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getPaymentConfig()
    {
        return $this->paymentConfig;
    }
}