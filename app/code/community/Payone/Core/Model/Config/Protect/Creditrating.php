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
class Payone_Core_Model_Config_Protect_Creditrating
    extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var int
     */
    protected $enabled = 0;
    /**
     * @var string
     */
    protected $type = '';

    /** @var string */
    protected $mode = '';

    protected $unknown_default = 'G';

    /** @var int */
    protected $payment_hint_enabled = 0;
    /** @var string */
    protected $payment_hint_text = '';

    /** @var int */
    protected $agreement_enabled = 0;

    /** @var string */
    protected $agreement_message ='';

    /**
     * @var string
     */
    protected $integration_event = '';
    /**
     * @var array
     */
    protected $enabled_for_payment_methods = array();

    /**
     * @var array
     */
    protected $allow_payment_methods_yellow = null;
    /**
     * @var array
     */
    protected $allow_payment_methods_red = null;
    /**
     * @var int
     */
    protected $result_lifetime = 0;

    /**
     * @var float
     */
    protected $min_order_total = 0.00;

    /**
     * @var float
     */
    protected $max_order_total = 0.00;

    /** @var string */
    protected $handle_response_error = '';

    /** @var string */
    protected $stop_checkout_message = '';

    /** @var int */
    protected $sample_mode_enabled = 0;

    /** @var int */
    protected $sample_mode_frequency = 0;

    public function init(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key == 'enabled_for_payment_methods'
                    or $key == 'allow_payment_methods_yellow'
                    or $key == 'allow_payment_methods_red'
            ) {
                if (is_string($value)) {
                    $value = explode(',', $value);
                }
            }

            $this->setValue($key, $value);
        }
    }

    /**
     * @return bool
     */
    public function isIntegrationEventBeforePayment()
    {
        return $this->integration_event == Payone_Core_Model_System_Config_CreditratingIntegrationEvent::BEFORE_PAYMENT;
    }

    /**
     * @return bool
     */
    public function isIntegrationEventAfterPayment()
    {
        return $this->integration_event == Payone_Core_Model_System_Config_CreditratingIntegrationEvent::AFTER_PAYMENT;
    }

    /**
     * @param string $code
     * @return bool
     */
    public function isEnabledForMethod($code)
    {
        return in_array($code, $this->enabled_for_payment_methods);
    }

    /**
     * @param array $allow_payment_methods_red
     */
    public function setAllowPaymentMethodsRed($allow_payment_methods_red)
    {
        $this->allow_payment_methods_red = $allow_payment_methods_red;
    }

    /**
     * @return array
     */
    public function getAllowPaymentMethodsRed()
    {
        return $this->allow_payment_methods_red;
    }

    /**
     * @param array $allow_payment_methods_yellow
     */
    public function setAllowPaymentMethodsYellow($allow_payment_methods_yellow)
    {
        $this->allow_payment_methods_yellow = $allow_payment_methods_yellow;
    }

    /**
     * @return array
     */
    public function getAllowPaymentMethodsYellow()
    {
        return $this->allow_payment_methods_yellow;
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
     * @return bool
     */
    public function isEnabled()
    {
        if($this->getEnabled())
        {
            return true;
        }

        return false;
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
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * @param array $enabled_for_payment_methods
     */
    public function setEnabledForPaymentMethods($enabled_for_payment_methods)
    {
        $this->enabled_for_payment_methods = $enabled_for_payment_methods;
    }

    /**
     * @return array
     */
    public function getEnabledForPaymentMethods()
    {
        return $this->enabled_for_payment_methods;
    }

    /**
     * @param string $integration_event
     */
    public function setIntegrationEvent($integration_event)
    {
        $this->integration_event = $integration_event;
    }

    /**
     * @return string
     */
    public function getIntegrationEvent()
    {
        return $this->integration_event;
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
     * @param int $sample_mode_enabled
     */
    public function setSampleModeEnabled($sample_mode_enabled)
    {
        $this->sample_mode_enabled = $sample_mode_enabled;
    }

    /**
     * @return int
     */
    public function getSampleModeEnabled()
    {
        return $this->sample_mode_enabled;
    }

    /**
     * @return bool
     */
    public function isSampleModeEnabled()
    {
        if ($this->getSampleModeEnabled()) {
            return true;
        }

        return false;
    }

    /**
     * @param int $sample_mode_frequency
     */
    public function setSampleModeFrequency($sample_mode_frequency)
    {
        $this->sample_mode_frequency = $sample_mode_frequency;
    }

    /**
     * How often a sample must be taken, 1 out of X
     *
     * @return int
     */
    public function getSampleModeFrequency()
    {
        return $this->sample_mode_frequency;
    }

    /**
     * @param int $agreement_enabled
     */
    public function setAgreementEnabled($agreement_enabled)
    {
        $this->agreement_enabled = $agreement_enabled;
    }

    /**
     * @return int
     */
    public function getAgreementEnabled()
    {
        return $this->agreement_enabled;
    }

    /**
     * @return bool
     */
    public function isAgreementEnabled()
    {
        if($this->getAgreementEnabled())
        {
            return true;
        }

        return false;
    }

    /**
     * @param string $agreement_message
     */
    public function setAgreementMessage($agreement_message)
    {
        $this->agreement_message = $agreement_message;
    }

    /**
     * @return string
     */
    public function getAgreementMessage()
    {
        return $this->agreement_message;
    }

    /**
     * @param int $payment_hint_enabled
     */
    public function setPaymentHintEnabled($payment_hint_enabled)
    {
        $this->payment_hint_enabled = $payment_hint_enabled;
    }

    /**
     * @return int
     */
    public function getPaymentHintEnabled()
    {
        return $this->payment_hint_enabled;
    }

    /**
     * @return bool
     */
    public function isPaymentHintEnabled()
    {
        if($this->getPaymentHintEnabled())
        {
            return true;
        }

        return false;
    }

    /**
     * @param string $payment_hint_text
     */
    public function setPaymentHintText($payment_hint_text)
    {
        $this->payment_hint_text = $payment_hint_text;
    }

    /**
     * @return string
     */
    public function getPaymentHintText()
    {
        return $this->payment_hint_text;
    }

    /**
     * @param string $unknown_default
     */
    public function setUnknownDefault($unknown_default)
    {
        $this->unknown_default = $unknown_default;
    }

    /**
     * @return string
     */
    public function getUnknownDefault()
    {
        return $this->unknown_default;
    }
}
