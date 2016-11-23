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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Config_General_StatusMapping extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var null
     */
    protected $creditcard = null;
    /**
     * @var null
     */
    protected $safe_invoice = null;
    /**
     * @var null
     */
    protected $financing = null;
    /**
     * @var null
     */
    protected $invoice = null;
    /**
     * @var null
     */
    protected $debit_payment = null;
    /**
     * @var null
     */
    protected $advance_payment = null;
    /**
     * @var null
     */
    protected $online_bank_transfer = null;
    /**
     * @var null
     */
    protected $cash_on_delivery = null;
    /**
     * @var null
     */
    protected $wallet = null;
    
    protected $ratepay = null;
    protected $payolution = null;

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
     * @param null $banktransfer
     */
    public function setOnlinebanktransfer($banktransfer)
    {
        if (is_string($banktransfer)) {
            $banktransfer = $this->initValue($banktransfer);
        }

        $this->online_bank_transfer = $banktransfer;
    }

    /**
     * @return null
     */
    public function getOnlinebanktransfer()
    {
        return $this->online_bank_transfer;
    }

    /**
     * @param null $cash_on_delivery
     */
    public function setCashOnDelivery($cash_on_delivery)
    {
        if (is_string($cash_on_delivery)) {
            $cash_on_delivery = $this->initValue($cash_on_delivery);
        }

        $this->cash_on_delivery = $cash_on_delivery;
    }

    /**
     * @return null
     */
    public function getCashOnDelivery()
    {
        return $this->cash_on_delivery;
    }

    /**
     * @param null $creditcard
     */
    public function setCreditcard($creditcard)
    {
        if (is_string($creditcard)) {
            $creditcard = $this->initValue($creditcard);
        }

        $this->creditcard = $creditcard;
    }

    /**
     * @return null
     */
    public function getCreditcard()
    {
        return $this->creditcard;
    }

    /**
     * @param null $debit
     */
    public function setDebitPayment($debit)
    {
        if (is_string($debit)) {
            $debit = $this->initValue($debit);
        }

        $this->debit_payment = $debit;
    }

    /**
     * @return null
     */
    public function getDebitPayment()
    {
        return $this->debit_payment;
    }

    /**
     * @param null $invoice
     */
    public function setInvoice($invoice)
    {
        if (is_string($invoice)) {
            $invoice = $this->initValue($invoice);
        }

        $this->invoice = $invoice;
    }

    /**
     * @return null
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param null $prepayment
     */
    public function setAdvancepayment($prepayment)
    {
        if (is_string($prepayment)) {
            $prepayment = $this->initValue($prepayment);
        }

        $this->advance_payment = $prepayment;
    }

    /**
     * @return null
     */
    public function getAdvancepayment()
    {
        return $this->advance_payment;
    }

    /**
     * @param null $wallet
     */
    public function setWallet($wallet)
    {
        if (is_string($wallet)) {
            $wallet = $this->initValue($wallet);
        }

        $this->wallet = $wallet;
    }
    
    public function setRatepay($ratepay)
    {
        if (is_string($ratepay)) {
            $ratepay = $this->initValue($ratepay);
        }

        $this->ratepay = $ratepay;
    }
    
    public function setPayolution($payolution)
    {
        if (is_string($payolution)) {
            $payolution = $this->initValue($payolution);
        }

        $this->payolution = $payolution;
    }

    /**
     * @return null
     */
    public function getWallet()
    {
        return $this->wallet;
    }
    
    public function getRatepay()
    {
        return $this->ratepay;
    }
    
    public function getPayolution()
    {
        return $this->payolution;
    }

    /**
     * @param null $financing
     */
    public function setFinancing($financing)
    {
        $this->financing = $financing;
    }

    /**
     * @return null
     */
    public function getFinancing()
    {
        return $this->financing;
    }

    /**
     * @param null $safe_invoice
     */
    public function setSafeInvoice($safe_invoice)
    {
        $this->safe_invoice = $safe_invoice;
    }

    /**
     * @return null
     */
    public function getSafeInvoice()
    {
        return $this->safe_invoice;
    }
}