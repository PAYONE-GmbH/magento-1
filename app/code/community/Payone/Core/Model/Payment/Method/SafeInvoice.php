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
class Payone_Core_Model_Payment_Method_SafeInvoice
    extends Payone_Core_Model_Payment_Method_Abstract
{
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canSettleAccountAuto = false;
    protected $_canUseInternal = false;
    protected $_mustTransimitInvoicingData = true;
    protected $_mustTransimitInvoicingItemTypes = true;

    protected $methodType = Payone_Core_Model_System_Config_PaymentMethodType::SAFEINVOICE;
    protected $_code = Payone_Core_Model_System_Config_PaymentMethodCode::SAFEINVOICE;


    protected $cancelRequest = false;

    protected $_formBlockType = 'payone_core/payment_method_form_safeInvoice';
    protected $_infoBlockType = 'payone_core/payment_method_info_safeInvoice';

    /** @var Payone_Core_Model_Config_Payment_Method_Interface[] */
    protected $matchingConfigs = array();

    /**
     * @override to further restrict availability of SafeInvoice by rules
     *
     * @param null|Mage_Sales_Model_Quote $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if (!empty($quote) &&
                count($this->getAllConfigsByQuote($quote)) < 1
        ) {
            return $this->dispatchPaymentMethodIsActive(false, $quote);
        }

        return parent::isAvailable($quote);
    }

    /**
     * @api
     *
     * To be used in Form_Block, which has to display all types
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

        if (!$this->isAllowedBillSafe($quote)) {
            // Special handling for BillSAFE, virtual quotes are not allowed
            foreach ($this->matchingConfigs as $configKey => $config) {
                /** @var $config Payone_Core_Model_Config_Payment_Method_Interface */
                $types = $config->getTypes();

                foreach ($types as $key => $type) {
                    // Remove BSV
                    if ($type === Payone_Api_Enum_FinancingType::BSV) {
                        unset($types[$key]);
                    }
                }

                if (count($types) === 0) {
                    unset($this->matchingConfigs[$configKey]);
                }

            }
        }
        return $this->matchingConfigs;
    }

    /**
     * BillSAFE does not allow:
     * - virtual quotes
     * - differing shipping/billing address
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return bool
     */
    protected function isAllowedBillSafe(Mage_Sales_Model_Quote $quote)
    {
        if ($quote->isVirtual()) {
            return false;
        }

        $billingAddress = $quote->getBillingAddress();
        $shippingAddress = $quote->getShippingAddress();


        if (!$shippingAddress->getSameAsBilling()) {
            // Double check, in case the customer has chosen to enter a separate shipping address, but filled in the same values as in billing address:
            if (!$this->helper()->addressesAreEqual($billingAddress, $shippingAddress)) {
                return false;
            }
        }

        return true;
    }
}