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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Payment_Method_Financing
    extends Payone_Core_Model_Payment_Method_Abstract
{
    protected $_canRefund = false;
    protected $_canRefundInvoicePartial = false;
    protected $_canUseInternal = false;

    protected $methodType = Payone_Core_Model_System_Config_PaymentMethodType::FINANCING;

    protected $_code = Payone_Core_Model_System_Config_PaymentMethodCode::FINANCING;

    /** @var Payone_Core_Model_Config_Payment_Method_Interface[] */
    protected $matchingConfigs = array();


    /**
     * @param Varien_Object $payment
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function cancel(Varien_Object $payment)
    {
        $status = $payment->getOrder()->getPayoneTransactionStatus();

        if(empty($status) or $status == 'REDIRECT')
            return $this; // Don´t send cancel to PAYONE on orders without TxStatus

        $this->capture($payment, 0.0000);

        return $this;
    }

    /**
     * @api
     *
     * To be used in Form_Block, which has to display all financing types
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getAllConfigsByQuote(Mage_Sales_Model_Quote $quote)
    {
        if (empty($this->matchingConfigs)) {
            $configStore = $this->getConfigStore($quote->getStoreId());

            $this->matchingConfigs = $configStore->getPayment()->getMethodsForQuote($this->methodType, $quote);
        }
        return $this->matchingConfigs;
    }

    protected $_formBlockType = 'payone_core/payment_method_form_financing';
    protected $_infoBlockType = 'payone_core/payment_method_info_financing';


}