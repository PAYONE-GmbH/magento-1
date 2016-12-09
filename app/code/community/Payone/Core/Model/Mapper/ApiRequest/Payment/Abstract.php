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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_Mapper_ApiRequest_Payment_Abstract
    extends Payone_Core_Model_Mapper_ApiRequest_Abstract
    implements Payone_Core_Model_Mapper_ApiRequest_Payment_Interface
{
    const DEFAULT_SHIPPING_SKU = 'Shipping';
    const DEFAULT_ADJUSTMENT_POSITIVE_SKU = 'Adjustment Refund';
    const DEFAULT_ADJUSTMENT_NEGATIVE_SKU = 'Adjustment Fee';

    const DEFAULT_DISCOUNT_SKU = 'Discount';
    const DEFAULT_TAX_SKU = 'Tax';

    const EVENT_PREFIX = 'payone_core_mapper_apirequest_payment';

    /** @var float */
    protected $amount = 0.00;

    /** @var Mage_Sales_Model_Order */
    protected $order = null;

    /** @var Payone_Core_Model_Payment_Method_Abstract */
    protected $paymentMethod = null;

    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $configPayment = null;

    /** @var Payone_Core_Model_Config_Misc */
    protected $configMisc = null;

    /**
     * @return string
     */
    abstract public function getEventType();

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     */
    protected function init(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->setOrder($payment->getOrder());
        $this->setPaymentMethod($payment->getMethodInstance());
    }

    /**
     * @param Payone_Api_Request_Interface $request
     */
    protected function mapDefaultParameters(Payone_Api_Request_Interface $request)
    {
        $helper = $this->helper();

        $solutionName = 'fatchip';
        $solutionVersion = $helper->getPayoneVersion();
        $integratorName = 'magento';
        $integratorVersion = $helper->getMagentoVersion();

        $request->setEncoding('UTF-8');
        $request->setMid($this->getConfigPayment()->getMid());
        $request->setPortalid($this->getConfigPayment()->getPortalid());
        $request->setMode($this->getConfigPayment()->getMode());
        $request->setKey($this->getConfigPayment()->getKey());
        $request->setIntegratorName($integratorName);
        $request->setIntegratorVersion($integratorVersion);
        $request->setSolutionName($solutionName);
        $request->setSolutionVersion($solutionVersion);
    }

    /**
     * @return Payone_Api_Request_Parameter_Invoicing_Item
     */
    protected function mapShippingFeeAsItem()
    {
        $order = $this->getOrder();

        $configMiscShipping = $this->getConfigMisc()->getShippingCosts();
        $sku = $configMiscShipping->getSku();
        if (empty($sku)) {
            $sku = $this->helper()->__(self::DEFAULT_SHIPPING_SKU);
        }

        $params['it'] = Payone_Api_Enum_InvoicingItemType::SHIPMENT;
        $params['id'] = $sku;
        $params['de'] = $order->getShippingDescription();
        $params['no'] = 1;
        $params['pr'] = $order->getShippingInclTax();
        $params['va'] = round($this->getShippingTaxRate() * 100);   // transfer vat in basis point format [#MAGE-186]

        $item = new Payone_Api_Request_Parameter_Invoicing_Item();
        $item->init($params);

        return $item;
    }

    /**
     * @param float $discountAmount
     * @return Payone_Api_Request_Parameter_Invoicing_Item
     */
    protected function mapDiscountAsItem($discountAmount)
    {
        $configMiscDiscount = $this->getConfigMisc()->getDiscount();
        $sku = $configMiscDiscount->getSku();
        $description = $configMiscDiscount->getDescription();
        if (empty($sku)) {
            $sku = $this->helper()->__(self::DEFAULT_DISCOUNT_SKU);
        }

        if (empty($description)) {
            $description = $this->helper()->__(self::DEFAULT_DISCOUNT_SKU);
        }

        $params['it'] = Payone_Api_Enum_InvoicingItemType::VOUCHER;
        $params['id'] = $sku;
        $params['de'] = $description;
        $params['no'] = 1;
        $params['pr'] = $discountAmount;
        $params['va'] = round($this->getShippingTaxRate() * 100); // assuming that it has the same tax-rate as shipping - dont know from where to get the tax
        
        $item = new Payone_Api_Request_Parameter_Invoicing_Item();
        $item->init($params);

        return $item;
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return Payone_Api_Request_Parameter_Invoicing_Item
     *
     * used by Payone_Core_Model_Mapper_ApiRequest_Payment_Debit
     */
    protected function mapRefundShippingAsItemByCreditmemo(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $this->getOrder();

        $configMiscShipping = $this->getConfigMisc()->getShippingCosts();
        $sku = $configMiscShipping->getSku();
        if (empty($sku)) {
            $sku = $this->helper()->__(self::DEFAULT_SHIPPING_SKU);
        }

        $params['it'] = Payone_Api_Enum_InvoicingItemType::SHIPMENT;
        $params['id'] = $sku;
        $params['de'] = $order->getShippingDescription();
        $params['no'] = 1;
        $params['pr'] = $creditmemo->getShippingInclTax();
        $params['va'] = round($this->getShippingTaxRate() * 100);
        
        $item = new Payone_Api_Request_Parameter_Invoicing_Item();
        $item->init($params);

        return $item;
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return Payone_Api_Request_Parameter_Invoicing_Item
     *
     * used by Payone_Core_Model_Mapper_ApiRequest_Payment_Debit
     */
    protected function mapAdjustmentPositiveAsItemByCreditmemo(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $configMiscCreditmemo = $this->getConfigMisc()->getCreditmemo();
        $sku = $configMiscCreditmemo->getAdjustmentRefundSku();
        $name = $configMiscCreditmemo->getAdjustmentRefundName();
        if (empty($sku)) {
            $sku = $this->helper()->__(self::DEFAULT_ADJUSTMENT_POSITIVE_SKU);
        }

        if (empty($name)) {
            $name = $this->helper()->__(self::DEFAULT_ADJUSTMENT_POSITIVE_SKU);
        }

        $params['it'] = Payone_Api_Enum_InvoicingItemType::VOUCHER;
        $params['id'] = $sku;
        $params['de'] = $name;
        $params['no'] = 1;
        $params['pr'] = $creditmemo->getAdjustmentPositive();
        $params['va'] = round($this->getShippingTaxRate() * 100); // assuming that it has the same tax-rate as shipping - dont know from where to get the tax
        
        $item = new Payone_Api_Request_Parameter_Invoicing_Item();
        $item->init($params);

        return $item;
    }

    /**
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return Payone_Api_Request_Parameter_Invoicing_Item
     *
     * used by Payone_Core_Model_Mapper_ApiRequest_Payment_Debit
     */

    protected function mapAdjustmentNegativeAsItemByCreditmemo(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $configMiscCreditmemo = $this->getConfigMisc()->getCreditmemo();
        $sku = $configMiscCreditmemo->getAdjustmentFeeSku();
        $name = $configMiscCreditmemo->getAdjustmentFeeName();
        if (empty($sku)) {
            $sku = $this->helper()->__(self::DEFAULT_ADJUSTMENT_NEGATIVE_SKU);
        }

        if (empty($name)) {
            $name = $this->helper()->__(self::DEFAULT_ADJUSTMENT_NEGATIVE_SKU);
        }

        $params['it'] = Payone_Api_Enum_InvoicingItemType::GOODS;
        $params['id'] = $sku;
        $params['de'] = $name;
        $params['no'] = 1;
        $params['pr'] = $creditmemo->getAdjustmentNegative() * (-1);
        $params['va'] = round($this->getShippingTaxRate() * 100); // assuming that it has the same tax-rate as shipping - dont know from where to get the tax


        $item = new Payone_Api_Request_Parameter_Invoicing_Item();
        $item->init($params);

        return $item;
    }


    protected function getShippingTaxRate()
    {
        $order = $this->getOrder();
        $storeId = $this->getStoreId();
        $factory = $this->getFactory();
        $store = $factory->getModelCoreStore()->load($storeId);

        /** @var $taxCalculationModel Mage_Tax_Model_Calculation */
        $taxCalculationModel = $factory->getSingletonTaxCalculation();

        $shippingAddress = $order->getShippingAddress();
        $billingAddress = $order->getBillingAddress();
        $quoteId = $order->getQuoteId();
        $quote = $factory->getModelSalesQuote();
        $quote->load($quoteId);

        $customerTaxClassId = $quote->getCustomerTaxClassId();
        $request = $taxCalculationModel->getRateRequest($shippingAddress, $billingAddress, $customerTaxClassId, $store);

        $shippingTaxClass = $this->helperConfig()->getShippingTaxClassId($storeId);
        if ($shippingTaxClass) {
            $request->setProductClassId($shippingTaxClass);
            return $taxCalculationModel->getRate($request);
        }
        else {
            return 0.0;
        }
    }


    /**
     * Returns the invoice appendix and substitutes the placeholders, as far as possible
     *
     * @param Mage_Sales_Model_Order_Invoice|null $invoice
     * @return mixed|string
     */
    public function getInvoiceAppendix(Mage_Sales_Model_Order_Invoice $invoice = null)
    {
        $order = $this->getOrder();

        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $this->getFactory()->getModelCustomer();
        $customer->load($order->getCustomerId());

        $invoiceIncrementId = '';
        if (!is_null($invoice)) {
            $invoiceIncrementId = $invoice->getIncrementId();
        }

        $substitutionArray = array(
            '{{order_increment_id}}' => $order->getIncrementId(),
            '{{order_id}}' => $order->getId(),
            '{{invoice_increment_id}}' => $invoiceIncrementId,
            '{{customer_increment_id}}' => $customer->getIncrementId(),
            '{{customer_id}}' => $order->getCustomerId(),
        );

        $appendix = $this->getConfigParameterInvoice()->getInvoiceAppendix();
        $appendix = str_replace(array_keys($substitutionArray), array_values($substitutionArray), $appendix);

        return $appendix;
    }

    /**
     * Returns the refund appendix and substitutes the placeholders, as far as possible
     *
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return string
     */
    public function getInvoiceAppendixRefund(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $order = $this->getOrder();

        /** @var $customer Mage_Customer_Model_Customer */
        $customer = $this->getFactory()->getModelCustomer();
        $customer->load($order->getCustomerId());

        /** @var $invoice Mage_Sales_Model_Order_Invoice */
        $invoice = $creditmemo->getInvoice();

        $substitutionArray = array(
            '{{order_increment_id}}' => $order->getIncrementId(),
            '{{order_id}}' => $order->getId(),
            '{{creditmemo_increment_id}}' => $creditmemo->getIncrementId(),
            '{{invoice_increment_id}}' => $invoice->getIncrementId(),
            '{{invoice_id}}' => $invoice->getId(),
            '{{customer_increment_id}}' => $customer->getIncrementId(),
            '{{customer_id}}' => $order->getCustomerId(),
        );

        $appendix = $this->getConfigParameterInvoice()->getInvoiceAppendixRefund();
        $appendix = str_replace(array_keys($substitutionArray), array_values($substitutionArray), $appendix);

        return $appendix;
    }


    /**
     * @return bool
     */
    protected function mustTransmitInvoiceData()
    {
        if ($this->getConfigPayment()->isInvoiceTransmitEnabled()) {
            return true;
        }

        $paymentMethod = $this->getPaymentMethod();
        if ($paymentMethod->mustTransmitInvoicingData()) { // Certain payment methods require invoicing data to be transmitted ALWAYS.
            return true;
        }

        return false;
    }

    /**
     * @param Mage_Sales_Model_Abstract $object
     * @return string
     */
    protected function fetchNewIncrementId(Mage_Sales_Model_Abstract $object)
    {
        $entityTypeModel = $this->getFactory()->getModelEavEntityType();

        $code = '';
        if ($object instanceof Mage_Sales_Model_Order_Invoice) {
            $code = 'invoice';
        }
        elseif ($object instanceof Mage_Sales_Model_Order_Creditmemo) {
            $code = 'creditmemo';
        }

        $entityType = $entityTypeModel->loadByCode($code);
        $newIncrementId = $entityType->fetchNewIncrementId($this->getStoreId());
        if ($newIncrementId !== false) {
            $object->setIncrementId($newIncrementId);
        }

        return $newIncrementId;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $configPayment
     */
    public function setConfigPayment(Payone_Core_Model_Config_Payment_Method_Interface $configPayment)
    {
        $this->configPayment = $configPayment;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfigPayment()
    {
        return $this->configPayment;
    }

    /**
     * @return Payone_Core_Model_Config_Misc
     */
    protected function getConfigMisc()
    {
        if ($this->configMisc === null) {
            $this->configMisc = $this->helperConfig()->getConfigMisc($this->getStoreId());
        }

        return $this->configMisc;
    }


    /**
     * @param Payone_Core_Model_Config_Misc $configMisc
     */
    public function setConfigMisc(Payone_Core_Model_Config_Misc $configMisc)
    {
        $this->configMisc = $configMisc;
    }

    /**
     * @return Payone_Core_Model_Config_General_ParameterInvoice
     */
    protected function getConfigParameterInvoice()
    {
        return $this->helperConfig()->getConfigGeneral($this->getStoreId())->getParameterInvoice();
    }

    /**
     * @return int
     */
    protected function getStoreId()
    {
        return $this->getPaymentMethod()->getStore();
    }

    /**
     * @param $storeId
     * @return Payone_Core_Model_Config_General
     */
    protected function getConfigGeneral($storeId = null)
    {
        if (is_null($storeId)) {
            $storeId = $this->getStoreId();
        }

        return $this->helperConfig()->getConfigGeneral($storeId);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    public function setOrder(Mage_Sales_Model_Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Payone_Core_Model_Payment_Method_Abstract $paymentMethod
     */
    public function setPaymentMethod(Payone_Core_Model_Payment_Method_Abstract $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return Payone_Core_Model_Payment_Method_Abstract
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function helperConfig()
    {
        return $this->getFactory()->helperConfig();
    }

    /**
     * @return Payone_Core_Helper_Registry
     */
    protected function helperRegistry()
    {
        return $this->getFactory()->helperRegistry();
    }

    protected function getEventPrefix()
    {
        return self::EVENT_PREFIX;
    }

    /**
     * @return string
     */
    protected function getEventName()
    {
        return $this->getEventPrefix() . '_' . $this->getEventType();
    }

    /**
     * Wrapper for Mage::dispatchEvent()
     *
     * @param $name
     * @param array $data
     *
     * @return Mage_Core_Model_App
     */
    protected function dispatchEvent($name, array $data = array())
    {
        return Mage::dispatchEvent($name, $data);
    }
}