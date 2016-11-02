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
class Payone_Core_Model_Config_Protect_AddressCheck
    extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var int
     */
    protected $enabled = 0;

    /** @var int */
    protected $check_billing = '';

    /** @var string */
    protected $check_shipping = '';

    /**
     * @var float
     */
    protected $min_order_total = 0.00;
    /**
     * @var float
     */
    protected $max_order_total = 0.00;

    /** @var string */
    protected $mode = '';

    /** @var bool */
    protected $confirm_address_correction = 0;

    /** @var int */
    protected $result_lifetime = 0;

    /** @var string */
    protected $handle_response_error = '';

    /** @var string */
    protected $stop_checkout_message = '';

    /** @var string */
    protected $message_response_invalid = '';

    /**
     * @var null
     */
    protected $mapping_personstatus = null;

    /** @var int */
    protected $check_billing_for_virtual_order = 0;

    public function init(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key == 'mapping_personstatus' and is_string($value)) {
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
            $personStatus = $score = '';
            if (array_key_exists('personstatus', $data)) {
                $personStatus = array_shift($data['personstatus']);
            }

            if (array_key_exists('score', $data)) {
                $score = array_shift($data['score']);
            }

            if ($personStatus == '' and $score == '') {
                continue;
            }

            $return[$personStatus] = $score;
        }

        return $return;
    }

    /**
     * @param string $check_billing
     */
    public function setCheckBilling($check_billing)
    {
        $this->check_billing = $check_billing;
    }

    /**
     * @return string
     */
    public function getCheckBilling()
    {
        return $this->check_billing;
    }

    /**
     * @return bool
     */
    public function mustCheckBilling()
    {
        if ($this->check_billing === Payone_Api_Enum_AddressCheckType::NONE) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function mustCheckShipping()
    {
        if ($this->check_shipping === Payone_Api_Enum_AddressCheckType::NONE) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function mustCheckBillingForVirtualOrder()
    {
        if ($this->getCheckBillingForVirtualOrder()) {
            return true;
        }

        return false;
    }

    /**
     * @param int $check_shipping
     */
    public function setCheckShipping($check_shipping)
    {
        $this->check_shipping = $check_shipping;
    }

    /**
     * @return int
     */
    public function getCheckShipping()
    {
        return $this->check_shipping;
    }

    /**
     * @param int $check_billing_for_virtual_order
     */
    public function setCheckBillingForVirtualOrder($check_billing_for_virtual_order)
    {
        $this->check_billing_for_virtual_order = $check_billing_for_virtual_order;
    }

    /**
     * @return int
     */
    public function getCheckBillingForVirtualOrder()
    {
        return $this->check_billing_for_virtual_order;
    }

    /**
     * @param int $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return int
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param null $mapping_personstatus
     */
    public function setMappingPersonstatus($mapping_personstatus)
    {
        $this->mapping_personstatus = $mapping_personstatus;
    }

    /**
     * @return null
     */
    public function getMappingPersonstatus()
    {
        return $this->mapping_personstatus;
    }

    /**
     * @param float $max_order_total
     */
    public function setMaxOrderTotal($max_order_total)
    {
        $this->max_order_total = $max_order_total;
    }

    /**
     * @return float
     */
    public function getMaxOrderTotal()
    {
        return $this->max_order_total;
    }

    /**
     * @param float $min_order_total
     */
    public function setMinOrderTotal($min_order_total)
    {
        $this->min_order_total = $min_order_total;
    }

    /**
     * @return float
     */
    public function getMinOrderTotal()
    {
        return $this->min_order_total;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param int $result_lifetime
     */
    public function setResultLifetime($result_lifetime)
    {
        $this->result_lifetime = $result_lifetime;
    }

    /**
     * @return int
     */
    public function getResultLifetime()
    {
        return $this->result_lifetime;
    }

    /**
     * @return int
     */
    public function getResultLifetimeInSeconds()
    {
        return $this->result_lifetime * 24 * 3600;
    }

    /**
     * @param boolean $confirm_address_correction
     */
    public function setConfirmAddressCorrection($confirm_address_correction)
    {
        $this->confirm_address_correction = $confirm_address_correction;
    }

    /**
     * @return boolean
     */
    public function getConfirmAddressCorrection()
    {
        return $this->confirm_address_correction;
    }

    /**
     * @param string $handle_response_error
     */
    public function setHandleResponseError($handle_response_error)
    {
        $this->handle_response_error = $handle_response_error;
    }

    /**
     * @return string
     */
    public function getHandleResponseError()
    {
        return $this->handle_response_error;
    }

    /**
     * @return bool
     */
    public function onErrorContinueCheckout()
    {
        if ($this->handle_response_error === Payone_Core_Model_System_Config_HandleResponseError::CONTINUE_CHECKOUT) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function onErrorStopCheckout()
    {
        if ($this->handle_response_error === Payone_Core_Model_System_Config_HandleResponseError::STOP_CHECKOUT) {
            return true;
        }

        return false;
    }

    /**
     * @param string $stop_checkout_message
     */
    public function setStopCheckoutMessage($stop_checkout_message)
    {
        $this->stop_checkout_message = $stop_checkout_message;
    }

    /**
     * @return string
     */
    public function getStopCheckoutMessage()
    {
        return $this->stop_checkout_message;
    }

    /**
     * @param string $message_response_invalid
     */
    public function setMessageResponseInvalid($message_response_invalid)
    {
        $this->message_response_invalid = $message_response_invalid;
    }

    /**
     * @return string
     */
    public function getMessageResponseInvalid()
    {
        return $this->message_response_invalid;
    }


}