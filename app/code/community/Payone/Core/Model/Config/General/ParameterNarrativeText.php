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
class Payone_Core_Model_Config_General_ParameterNarrativeText
    extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var string
     */
    protected $creditcard = '';
    /**
     * @var string
     */
    protected $debit_payment = '';
    /**
     * @var string
     */
    protected $paydirekt = '';
    
    /**
     * @var string
     */
    protected $paypal_express = '';

    /**
     * @param string $creditcard
     */
    public function setCreditcard($creditcard)
    {
        $this->creditcard = $creditcard;
    }

    /**
     * @return string
     */
    public function getCreditcard()
    {
        return $this->creditcard;
    }

    /**
     * @param string $debit_payment
     */
    public function setDebitPayment($debit_payment)
    {
        $this->debit_payment = $debit_payment;
    }

    /**
     * @return string
     */
    public function getDebitPayment()
    {
        return $this->debit_payment;
    }
    
    /**
     * @param string $paydirekt
     */
    public function setPaydirekt($paydirekt)
    {
        $this->paydirekt = $paydirekt;
    }

    /**
     * @return string
     */
    public function getPaydirekt()
    {
        return $this->paydirekt;
    }
    
    /**
     * @param string $paydirekt
     */
    public function setPaypalExpress($paypal_express)
    {
        $this->paypal_express = $paypal_express;
    }

    /**
     * @return string
     */
    public function getPaypalExpress()
    {
        return $this->paypal_express;
    }
}