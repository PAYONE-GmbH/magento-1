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
class Payone_Core_Model_Config_General extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var Payone_Core_Model_Config_General_Global
     */
    protected $global;
    /**
     * @var Payone_Core_Model_Config_General_ParameterInvoice
     */
    protected $parameter_invoice;
    /**
     * @var Payone_Core_Model_Config_General_StatusMapping
     */
    protected $status_mapping;
    /**
     * @var Payone_Core_Model_Config_General_PaymentCreditcard
     */
    protected $payment_creditcard;
    /**
     * @var Payone_Core_Model_Config_General_PaymentPaypalExpressCheckout
     */
    protected $payment_paypal_express_checkout;

    /**
     * @var Payone_Core_Model_Config_General_ParameterNarrativeText
     */
    protected $parameter_narrative_text;

    /**
     * @param Payone_Core_Model_Config_General_Global $global
     */
    public function setGlobal(Payone_Core_Model_Config_General_Global $global)
    {
        $this->global = $global;
    }

    /**
     * @return Payone_Core_Model_Config_General_Global
     */
    public function getGlobal()
    {
        return $this->global;
    }

    /**
     * @param Payone_Core_Model_Config_General_ParameterInvoice $parameter_invoice
     */
    public function setParameterInvoice(Payone_Core_Model_Config_General_ParameterInvoice $parameter_invoice)
    {
        $this->parameter_invoice = $parameter_invoice;
    }

    /**
     * @return Payone_Core_Model_Config_General_ParameterInvoice
     */
    public function getParameterInvoice()
    {
        return $this->parameter_invoice;
    }


    /**
     * @param Payone_Core_Model_Config_General_PaymentCreditcard $payment_creditcard
     */
    public function setPaymentCreditcard(Payone_Core_Model_Config_General_PaymentCreditcard $payment_creditcard)
    {
        $this->payment_creditcard = $payment_creditcard;
    }

    /**
     * @return Payone_Core_Model_Config_General_PaymentCreditcard
     */
    public function getPaymentCreditcard()
    {
        return $this->payment_creditcard;
    }

    /**
     * @param Payone_Core_Model_Config_General_PaymentPaypalExpressCheckout $payment_paypal_express_checkout
     */
    public function setPaymentPaypalExpressCheckout(Payone_Core_Model_Config_General_PaymentPaypalExpressCheckout $payment_paypal_express_checkout)
    {
        $this->payment_paypal_express_checkout = $payment_paypal_express_checkout;
    }

    /**
     * @return Payone_Core_Model_Config_General_PaymentPaypalExpressCheckout
     */
    public function getPaymentPaypalExpressCheckout()
    {
        return $this->payment_paypal_express_checkout;
    }

    /**
     * @param Payone_Core_Model_Config_General_StatusMapping $status_mapping
     */
    public function setStatusMapping(Payone_Core_Model_Config_General_StatusMapping $status_mapping)
    {
        $this->status_mapping = $status_mapping;
    }

    /**
     * @return Payone_Core_Model_Config_General_StatusMapping
     */
    public function getStatusMapping()
    {
        return $this->status_mapping;
    }

    /**
     * @param Payone_Core_Model_Config_General_ParameterNarrativeText $parameter_narrative_text
     */
    public function setParameterNarrativeText($parameter_narrative_text)
    {
        $this->parameter_narrative_text = $parameter_narrative_text;
    }

    /**
     * @return Payone_Core_Model_Config_General_ParameterNarrativeText
     */
    public function getParameterNarrativeText()
    {
        return $this->parameter_narrative_text;
    }
}
