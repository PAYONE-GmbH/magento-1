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
 * @package         Payone_Core_Block
 * @subpackage      Paydirekt_Express
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Paydirekt_Express_Review extends Mage_Core_Block_Template
{
    /** @var int */
    protected $quoteId;
    /** @var Payone_Core_Block_Paydirekt_Express_Review_Billing */
    protected $billing;
    /** @var Payone_Core_Block_Paydirekt_Express_Review_Shipping */
    protected $shipping;
    /** @var Payone_Core_Block_Checkout_Onepage_Shipping_Method_Available */
    protected $shippingMethods;
    /** @var Payone_Core_Block_Paydirekt_Express_Review_PaymentMethod */
    protected $paymentMethod;
    /** @var Payone_Core_Block_Paydirekt_Express_Review_Items */
    protected $itemsReview;
    /** @var Mage_Checkout_Block_Agreements */
    protected $checkoutAgreements;

    /**
     * Retrieve payment method and assign additional template values
     */
    protected function _beforeToHtml()
    {

    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($this->quoteId);

        return $quote;
    }

    /**
     * @return int
     */
    public function getQuoteId()
    {
        return $this->quoteId;
    }

    /**
     * @param int $quoteId
     */
    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;
    }

    /**
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * @param Payone_Core_Block_Paydirekt_Express_Review_Billing $billing
     */
    public function setBilling($billing)
    {
        $this->billing = $billing;
    }

    /**
     * @return Payone_Core_Block_Paydirekt_Express_Review_Shipping
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param Payone_Core_Block_Paydirekt_Express_Review_Shipping $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return Payone_Core_Block_Checkout_Onepage_Shipping_Method_Available
     */
    public function getShippingMethods()
    {
        return $this->shippingMethods;
    }

    /**
     * @param Payone_Core_Block_Checkout_Onepage_Shipping_Method_Available $shippingMethods
     */
    public function setShippingMethods($shippingMethods)
    {
        $this->shippingMethods = $shippingMethods;
    }

    /**
     * @return Payone_Core_Block_Paydirekt_Express_Review_PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param Payone_Core_Block_Paydirekt_Express_Review_PaymentMethod $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return Payone_Core_Block_Paydirekt_Express_Review_Items
     */
    public function getItemsReview()
    {
        return $this->itemsReview;
    }

    /**
     * @param Payone_Core_Block_Paydirekt_Express_Review_Items $itemsReview
     */
    public function setItemsReview($itemsReview)
    {
        $this->itemsReview = $itemsReview;
    }

    /**
     * @return Mage_Checkout_Block_Agreements
     */
    public function getCheckoutAgreements()
    {
        return $this->checkoutAgreements;
    }

    /**
     * @param Mage_Checkout_Block_Agreements $checkoutAgreements
     */
    public function setCheckoutAgreements($checkoutAgreements)
    {
        $this->checkoutAgreements = $checkoutAgreements;
    }
}