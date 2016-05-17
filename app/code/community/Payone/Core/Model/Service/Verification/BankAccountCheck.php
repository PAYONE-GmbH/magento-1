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
class Payone_Core_Model_Service_Verification_BankAccountCheck
    extends Payone_Core_Model_Service_Verification_Abstract
{
    /** @var Payone_Api_Service_Verification_BankAccountCheck */
    protected $serviceApiBankAccountCheck = null;

    /** @var Payone_Core_Model_Mapper_ApiRequest_Verification_BankAccountCheck */
    protected $mapper = null;

    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $configPayment = null;

    /**
     * @param $bankaccount
     * @param $bankcode
     * @param $bankcountry
     * @param $iban
     * @param $bic
     *
     * @return void
     * @throws Mage_Core_Exception
     */
    public function execute($bankaccount, $bankcode, $bankcountry, $iban = '', $bic = '')
    {
        $request = $this->getMapper()->map($bankaccount, $bankcode, $bankcountry, $iban, $bic);

        $response = $this->getServiceApiBankAccountCheck()->check($request);

        // Response handling:
        if ($response instanceof Payone_Api_Response_BankAccountCheck_Valid) {
            return;
        }
        elseif ($response instanceof Payone_Api_Response_BankAccountCheck_Blocked) {
            throw new Mage_Core_Exception($this->getMessageForInvalidData());
        }
        elseif ($response instanceof Payone_Api_Response_BankAccountCheck_Invalid) {
            throw new Mage_Core_Exception($response->getCustomermessage());
        }
        elseif ($response instanceof Payone_Api_Response_Error) {
            throw new Mage_Core_Exception($response->getCustomermessage());
        }
    }

    /**
     * @param Mage_Sales_Model_Quote_Payment $payment
     * @return void
     */
    public function executeByPayment(Mage_Sales_Model_Quote_Payment $payment)
    {
        $bankaccount = $payment->getPayoneAccountNumber();
        $bankcode = $payment->getPayoneBankCode();
        $bankcountry = $payment->getPayoneBankCountry();
        $iban = $payment->getPayoneSepaIban();
        $bic = $payment->getPayoneSepaBic();
        if (empty($bankcountry)) {
            $bankcountry = $payment->getQuote()->getBillingAddress()->getCountry();
        }

        $this->execute($bankaccount, $bankcode, $bankcountry, $iban, $bic);
    }

    /**
     * @return string
     */
    protected function getMessageForInvalidData()
    {

        $message = $this->getConfigPayment()->getMessageResponseBlocked();

        if (empty($message)) {
            $message = $this->helper()->__('There has been an error procesing your payment.');
        }

        return $message;
    }


    /**
     * @param Payone_Api_Service_Verification_BankAccountCheck $serviceApiBankAccountCheck
     */
    public function setServiceApiBankAccountCheck(Payone_Api_Service_Verification_BankAccountCheck $serviceApiBankAccountCheck)
    {
        $this->serviceApiBankAccountCheck = $serviceApiBankAccountCheck;
    }

    /**
     * @return Payone_Api_Service_Verification_BankAccountCheck
     */
    public function getServiceApiBankAccountCheck()
    {
        return $this->serviceApiBankAccountCheck;
    }

    /**
     * @param Payone_Core_Model_Mapper_ApiRequest_Verification_BankAccountCheck $mapper
     */
    public function setMapper(Payone_Core_Model_Mapper_ApiRequest_Verification_BankAccountCheck $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Payone_Core_Model_Mapper_ApiRequest_Verification_BankAccountCheck
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $configPayment (config for debit_payment)
     */
    public function setConfigPayment(Payone_Core_Model_Config_Payment_Method_Interface $configPayment)
    {
        $this->configPayment = $configPayment;
    }

    /**
     * @return \Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfigPayment()
    {
        return $this->configPayment;
    }
}