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
 * @subpackage      Config
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 *
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @link            http://www.noovias.com
 *
 * @copyright       Copyright (c) 2017 <service@e3n.de> - e3n.de
 * @author          Tobias Niebergall <service@e3n.de>
 * @link            https://e3n.de
 */
class Payone_Core_Model_Config_General_StatusMapping extends Payone_Core_Model_Config_AreaAbstract
{
    /*
     * Sortorder adapted from Payone_Core_Model_System_Config_PaymentMethodType
     */

    /**
     * Payment method advance_payment
     *
     * @var null
     */
    protected $advancePayment = null;
    /**
     * Payment method cash_on_delivery
     *
     * @var null
     */
    protected $cashOnDelivery = null;
    /**
     * Payment method creditcard
     *
     * @var null
     */
    protected $creditcard = null;
    /**
     * Payment method sebit_payment
     *
     * @var null
     */
    protected $debitPayment = null;
    /**
     * Payment method safe_invoice
     *
     * @var null
     */
    protected $safeInvoice = null;
    /**
     * Payment method invoice
     *
     * @var null
     */
    protected $invoice = null;
    /**
     * Payment method online_bank_transfer_pfc
     *
     * @var null
     */
    protected $onlineBankTransferPfc = null;
    /**
     * Payment method online_bank_transfer_giropay
     *
     * @var null
     */
    protected $onlineBankTransferGiropay = null;
    /**
     * Payment method online_bank_transfer_pff
     *
     * @var null
     */
    protected $onlineBankTransferPff = null;
    /**
     * Payment method online_bank_transfer_eps
     *
     * @var null
     */
    protected $onlineBankTransferEps = null;
    /**
     * Payment method online_bank_transfer_p24
     *
     * @var null
     */
    protected $onlineBankTransferP24 = null;
    /**
     * Payment method online_bank_transfer_idl
     *
     * @var null
     */
    protected $onlineBankTransferIdl = null;
    /**
     * Payment method online_bank_transfer_sofortueberweisung
     *
     * @var null
     */
    protected $onlineBankTransferSofortueberweisung = null;
    /**
     * Payment method online_bank_transfer
     *
     * @var null
     */
    protected $onlineBankTransfer = null;
    /**
     * Payment method wallet
     *
     * @var null
     */
    protected $wallet = null;
    /**
     * Payment method barzahlen
     *
     * @var null
     */
    protected $barzahlen = null;
    /**
     * Payment method ratepay
     *
     * @var null
     */
    protected $ratepay = null;
    /**
     * Payment method payolution
     *
     * @var null
     */
    protected $payolution = null;
    /**
     * Payment method payolution_invoicing
     *
     * @var null
     */
    protected $payolutionInvoicing = null;
    /**
     * Payment method payolution_debit
     *
     * @var null
     */
    protected $payolutionDebit = null;
    /**
     * Payment method payolution_installment
     *
     * @var null
     */
    protected $payolutionInstallment = null;
    /**
     * Payment method wallet_paydirekt
     *
     * @var null
     */
    protected $walletPaydirekt = null;
    /**
     * Payment method wallet_paypal_express
     *
     * @var null
     */
    protected $walletPaypalExpress = null;
    /**
     * Payment method wallet_alipay
     *
     * @var null
     */
    protected $walletAlipay = null;
    /**
     * Payment method financing
     *
     * @var null
     */
    protected $financing = null;

    /**
     * @param array $data
     */
    public function init(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $value = $this->initValue($value);
            }

            $this->setValue($key, $value);
        }
    }

    /**
     * @param string $value
     * @return array|null
     */
    protected function initValue($value)
    {
        $return = array();
        $raw = unserialize($value);
        if (!is_array($raw)) {
            return null;
        }

        foreach ($raw as $key => $data) {
            $txaction = $orderStatus = null;
            if (array_key_exists('txaction', $data)) {
                $txaction = array_shift($data['txaction']);
            }

            // State_Status Mapping @since 3.1.0
            if (array_key_exists('state_status', $data)) {
                $orderStateStatus = array_shift($data['state_status']);
                $orderStateStatusArray = explode('|', $orderStateStatus);
                if (count($orderStateStatusArray) !== 2) {
                    continue;
                }

                $orderStatus = array(
                    'state' => $orderStateStatusArray[0],
                    'status' => $orderStateStatusArray[1]
                );
            }
            // Fallback to old Configs < 3.1.0
            elseif (array_key_exists('status', $data)) {
                $orderStatusCode = array_shift($data['status']);
                $orderStatus = array(
                    'state' => '',
                    'status' => $orderStatusCode
                );
            }

            if ($txaction === null and $orderStatus === null) {
                continue;
            }

            $return[$txaction] = $orderStatus;
        }

        return $return;
    }

    /**
     * @param string $type
     * @return array|null
     */
    public function getByType($type)
    {
        return $this->getValue($type);
    }

    /**
     * @param $advancePayment
     */
    public function setAdvancePayment($advancePayment)
    {
        if (is_string($advancePayment)) {
            $advancePayment = $this->initValue($advancePayment);
        }

        $this->advancePayment = $advancePayment;
    }

    /**
     * @return $advancePayment|null
     */
    public function getAdvancePayment()
    {
        return $this->advancePayment;
    }

    /**
     * @param $cashOnDelivery
     */
    public function setCashOnDelivery($cashOnDelivery)
    {
        if (is_string($cashOnDelivery)) {
            $cashOnDelivery = $this->initValue($cashOnDelivery);
        }

        $this->cashOnDelivery = $cashOnDelivery;
    }

    /**
     * @return $cashOnDelivery|null
     */
    public function getCashOnDelivery()
    {
        return $this->cashOnDelivery;
    }

    /**
     * @param $creditcard
     */
    public function setCreditcard($creditcard)
    {
        if (is_string($creditcard)) {
            $creditcard = $this->initValue($creditcard);
        }

        $this->creditcard = $creditcard;
    }

    /**
     * @return $creditcard|null
     */
    public function getCreditcard()
    {
        return $this->creditcard;
    }

    /**
     * @param $debitPayment
     */
    public function setDebitPayment($debitPayment)
    {
        if (is_string($debitPayment)) {
            $debitPayment = $this->initValue($debitPayment);
        }

        $this->debitPayment = $debitPayment;
    }

    /**
     * @return $debitPayment|null
     */
    public function getDebitPayment()
    {
        return $this->debitPayment;
    }

    /**
     * @param $safeInvoice
     */
    public function setSafeInvoice($safeInvoice)
    {
        $this->safeInvoice = $safeInvoice;
    }

    /**
     * @return $safeInvoice|null
     */
    public function getSafeInvoice()
    {
        return $this->safeInvoice;
    }

    /**
     * @param $invoice
     */
    public function setInvoice($invoice)
    {
        if (is_string($invoice)) {
            $invoice = $this->initValue($invoice);
        }

        $this->invoice = $invoice;
    }

    /**
     * @return $invoice|null
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param $onlineBankTransferPfc
     */
    public function setOnlineBankTransferPfc($onlineBankTransferPfc)
    {
        if (is_string($onlineBankTransferPfc)) {
            $onlineBankTransferPfc = $this->initValue($onlineBankTransferPfc);
        }

        $this->onlineBankTransferPfc = $onlineBankTransferPfc;
    }

    /**
     * @return $onlineBankTransferPfc|null
     */
    public function getOnlineBankTransferPfc()
    {
        return $this->onlineBankTransferPfc;
    }

    /**
     * @param $onlineBankTransferGiropay
     */
    public function setOnlineBankTransferGiropay($onlineBankTransferGiropay)
    {
        if (is_string($onlineBankTransferGiropay)) {
            $onlineBankTransferGiropay = $this->initValue($onlineBankTransferGiropay);
        }

        $this->onlineBankTransferGiropay = $onlineBankTransferGiropay;
    }

    /**
     * @return $onlineBankTransferGiropay|null
     */
    public function getOnlineBankTransferGiropay()
    {
        return $this->onlineBankTransferGiropay;
    }

    /**
     * @param $onlineBankTransferPff
     */
    public function setOnlineBankTransferPff($onlineBankTransferPff)
    {
        if (is_string($onlineBankTransferPff)) {
            $onlineBankTransferPff = $this->initValue($onlineBankTransferPff);
        }

        $this->onlineBankTransferPff = $onlineBankTransferPff;
    }

    /**
     * @return $onlineBankTransferPff|null
     */
    public function getOnlineBankTransferPff()
    {
        return $this->onlineBankTransferPff;
    }

    /**
     * @param $onlineBankTransferEps
     */
    public function setOnlineBankTransferEps($onlineBankTransferEps)
    {
        if (is_string($onlineBankTransferEps)) {
            $onlineBankTransferEps = $this->initValue($onlineBankTransferEps);
        }

        $this->onlineBankTransferEps = $onlineBankTransferEps;
    }

    /**
     * @return $onlineBankTransferEps|null
     */
    public function getOnlineBankTransferEps()
    {
        return $this->onlineBankTransferEps;
    }

    /**
     * @param $onlineBankTransferP24
     */
    public function setOnlineBankTransferP24($onlineBankTransferP24)
    {
        if (is_string($onlineBankTransferP24)) {
            $onlineBankTransferP24 = $this->initValue($onlineBankTransferP24);
        }

        $this->onlineBankTransferP24 = $onlineBankTransferP24;
    }

    /**
     * @return $onlineBankTransferP24|null
     */
    public function getOnlineBankTransferP24()
    {
        return $this->onlineBankTransferP24;
    }

    /**
     * @param $onlineBankTransferIdl
     */
    public function setOnlineBankTransferIdl($onlineBankTransferIdl)
    {
        if (is_string($onlineBankTransferIdl)) {
            $onlineBankTransferIdl = $this->initValue($onlineBankTransferIdl);
        }

        $this->onlineBankTransferIdl = $onlineBankTransferIdl;
    }

    /**
     * @return $onlineBankTransferIdl|null
     */
    public function getOnlineBankTransferIdl()
    {
        return $this->onlineBankTransferIdl;
    }

    /**
     * @param $onlineBankTransferSofortueberweisung
     */
    public function setOnlineBankTransferSofortueberweisung($onlineBankTransferSofortueberweisung)
    {
        if (is_string($onlineBankTransferSofortueberweisung)) {
            $onlineBankTransferSofortueberweisung = $this->initValue($onlineBankTransferSofortueberweisung);
        }

        $this->onlineBankTransferSofortueberweisung = $onlineBankTransferSofortueberweisung;
    }

    /**
     * @return $onlineBankTransferSofortueberweisung|null
     */
    public function getOnlineBankTransferSofortueberweisung()
    {
        return $this->onlineBankTransferSofortueberweisung;
    }


    /**
     * @param $onlineBankTransfer
     */
    public function setOnlineBankTransfer($onlineBankTransfer)
    {
        if (is_string($onlineBankTransfer)) {
            $onlineBankTransfer = $this->initValue($onlineBankTransfer);
        }

        $this->onlineBankTransfer = $onlineBankTransfer;
    }

    /**
     * @return $onlineBankTransfer|null
     */
    public function getOnlineBankTransfer()
    {
        return $this->onlineBankTransfer;
    }

    /**
     * @param $wallet
     */
    public function setWallet($wallet)
    {
        if (is_string($wallet)) {
            $wallet = $this->initValue($wallet);
        }

        $this->wallet = $wallet;
    }

    /**
     * @return $wallet|null
     */
    public function getWallet()
    {
        return $this->wallet;
    }

    /**
     * @param $barzahlen
     */
    public function setBarzahlen($barzahlen)
    {
        if (is_string($barzahlen)) {
            $barzahlen = $this->initValue($barzahlen);
        }

        $this->barzahlen = $barzahlen;
    }

    /**
     * @return $barzahlen|null
     */
    public function getBarzahlen()
    {
        return $this->barzahlen;
    }

    /**
     * @param $ratepay
     */
    public function setRatepay($ratepay)
    {
        if (is_string($ratepay)) {
            $ratepay = $this->initValue($ratepay);
        }

        $this->ratepay = $ratepay;
    }

    /**
     * @return $ratepay|null
     */
    public function getRatepay()
    {
        return $this->ratepay;
    }

    /**
     * @param $payolution
     */
    public function setPayolution($payolution)
    {
        if (is_string($payolution)) {
            $payolution = $this->initValue($payolution);
        }

        $this->payolution = $payolution;
    }

    /**
     * @return $payolution|null
     */
    public function getPayolution()
    {
        return $this->payolution;
    }

    /**
     * @param $payolutionInvoicing
     */
    public function setPayolutionInvoicing($payolutionInvoicing)
    {
        $this->payolutionInvoicing = $payolutionInvoicing;
    }

    /**
     * @return $payolutionInvoicing|null
     */
    public function getPayolutionInvoicing()
    {
        return $this->payolutionInvoicing;
    }

    /**
     * @param $payolutionDebit
     */
    public function setPayolutionDebit($payolutionDebit)
    {
        $this->payolutionDebit = $payolutionDebit;
    }

    /**
     * @return $payolutionDebit|null
     */
    public function getPayolutionDebit()
    {
        return $this->payolutionDebit;
    }

    /**
     * @param $payolutionInstallment
     */
    public function setPayolutionInstallment($payolutionInstallment)
    {
        $this->payolutionInstallment = $payolutionInstallment;
    }

    /**
     * @return $payolutionInstallment|null
     */
    public function getPayolutionInstallment()
    {
        return $this->payolutionInstallment;
    }

    /**
     * @param $walletPaydirekt
     */
    public function setWalletPaydirekt($walletPaydirekt)
    {
        if (is_string($walletPaydirekt)) {
            $walletPaydirekt = $this->initValue($walletPaydirekt);
        }

        $this->walletPaydirekt = $walletPaydirekt;
    }

    /**
     * @return $walletPaydirekt|null
     */
    public function getWalletPaydirekt()
    {
        return $this->walletPaydirekt;
    }

    /**
     * @param $walletPaypalExpress
     */
    public function setWalletPaypalExpress($walletPaypalExpress)
    {
        if (is_string($walletPaypalExpress)) {
            $walletPaypalExpress = $this->initValue($walletPaypalExpress);
        }

        $this->walletPaypalExpress = $walletPaypalExpress;
    }

    /**
     * @return $walletPaypalExpress|null
     */
    public function getWalletPaypalExpress()
    {
        return $this->walletPaypalExpress;
    }

    /**
     * @param $walletAlipay
     */
    public function setWalletAlipay($walletAlipay)
    {
        if (is_string($walletAlipay)) {
            $walletAlipay = $this->initValue($walletAlipay);
        }

        $this->walletAlipay = $walletAlipay;
    }

    /**
     * @return $walletAlipay|null
     */
    public function getWalletAlipay()
    {
        return $this->walletAlipay;
    }

    /**
     * @param $financing
     */
    public function setFinancing($financing)
    {
        $this->financing = $financing;
    }

    /**
     * @return $financing|null
     */
    public function getFinancing()
    {
        return $this->financing;
    }
}
