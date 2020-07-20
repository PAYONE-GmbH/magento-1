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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com, Copyright (c) 2017 <support@e3n.de> - www.e3n.de
 * @author          Matthias Walter <info@noovias.com>,  Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com, http://www.e3n.de
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
abstract class Payone_Core_Model_Mapper_ApiRequest_Payment_Authorize_Abstract
    extends Payone_Core_Model_Mapper_ApiRequest_Payment_Abstract
{
    /**
     * @return Payone_Api_Request_Authorization_Abstract
     */
    abstract protected function getRequest();

    /**
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Payone_Api_Request_Preauthorization|Payone_Api_Request_Authorization
     */
    public function mapFromPayment(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->init($payment);

        $request = $this->getRequest();

        $this->beforeMapFromPayment($request);

        // Add Default Api Parameters
        $this->mapDefaultParameters($request);

        // Add Default Authorize Parameters
        $this->mapDefaultAuthorizeParameters($request);

        // PersonalData
        $personalData = $this->mapPersonalParameters();
        $request->setPersonalData($personalData);

        // ShippingData, only for non-virtual orders.
        if ($payment->getOrder()->getIsNotVirtual()) {
            $deliveryData = $this->mapDeliveryParameters();
            $request->setDeliveryData($deliveryData);
        }

        // Only add Invoiceing Parameters if enabled / required by payment method
        if ($this->mustTransmitInvoiceData()) {
            $invoicing = $this->mapInvoicingParameters();

            if ($this->mustAdaptCalculation()) {
                /** @var Payone_Api_Request_Parameter_Invoicing_Item $item */
                foreach ($invoicing->getItems() as $item) {
                    $item->setPr($item->getNo() * $item->getPr());
                    $item->setDe('Menge: ' . $item->getNo() . ' ' . $item->getDe());
                    $item->setNo(1);
                }
            }

            $request->setInvoicing($invoicing);
        }

        //Add workorderid when wollet and checkout express
        if($workOrderId = $payment->getAdditionalInformation(Payone_Core_Model_Service_Paypal_Express_Checkout::PAYONE_EXPRESS_CHECKOUT_WORKORDERID)) {
            $request->setWorkorderId($workOrderId);
        }
        //Add workorderid when paydirekt express checkout is used
        if($workOrderId = $payment->getAdditionalInformation(Payone_Core_Model_Service_Paydirekt_Express_Checkout::PAYONE_PAYDIREKT_CHECKOUT_WORKORDERID)) {
            $request->setWorkorderId($workOrderId);
        }

        $payment = $this->mapPaymentParameters();

        // Not every Paymentmethod has an extra Parameter Set
        if ($payment !== null) {
            $request->setPayment($payment);
        }

        $this->afterMapFromPayment($request);

        $this->dispatchEvent($this->getEventName(), array('request' => $request, 'order' => $this->getOrder()));
        $this->dispatchEvent($this->getEventPrefix() . '_all', array('request' => $request));
        return $request;
    }

    /**
     * @param Payone_Api_Request_Authorization_Abstract $request
     */
    public function beforeMapFromPayment(Payone_Api_Request_Authorization_Abstract $request)
    {

    }

    /**
     * @param Payone_Api_Request_Authorization_Abstract $request
     */
    public function afterMapFromPayment(Payone_Api_Request_Authorization_Abstract $request)
    {

    }

    /**
     * @param $oPayment
     * @param $oOrder
     * @return mixed
     */
    protected function _getReferenceNumber($oPayment, $oOrder)
    {
        $sRefNr = $oOrder->getIncrementId();
        if (
            $oPayment instanceof Payone_Core_Model_Payment_Method_WalletPaydirektExpress
            ||
            (
                ($oPayment instanceof Payone_Core_Model_Payment_Method_Wallet || $oPayment instanceof Payone_Core_Model_Payment_Method_WalletPaydirekt)
                && ($this->_getWalletType() == 'PDT')
            )
        ) {
            $sRefNr = str_replace('_', '-', $sRefNr);
        }
        return $sRefNr;
    }


    /**
     * @param Payone_Api_Request_Authorization_Abstract $request
     */
    protected function mapDefaultAuthorizeParameters(Payone_Api_Request_Authorization_Abstract $request)
    {
        $order = $this->getOrder();
        $paymentMethod = $this->getPaymentMethod();

        $requestType = $this->configPayment->getRequestType();
        // Always use PREAUTHORIZATION for Safe Invoice of type "Klarna"
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice) {
            $safeInvoiceType = $paymentMethod->getInfoInstance()->getPayoneSafeInvoiceType();
            if ($safeInvoiceType == Payone_Api_Enum_FinancingType::KLV) {
                $requestType = Payone_Api_Enum_RequestType::PREAUTHORIZATION;
            }
        }

        // Always use PREAUTHORIZATION for Barzahlen
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Barzahlen) {
            $requestType = Payone_Api_Enum_RequestType::PREAUTHORIZATION;
        }
        
        // Always use PREAUTHORIZATION for Payolution Debit and Invoicing
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionDebit ||
            $paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInvoicing ||
            $paymentMethod instanceof Payone_Core_Model_Payment_Method_Payolution)
        {
            $requestType = Payone_Api_Enum_RequestType::PREAUTHORIZATION;
        }

        // Always use AUTHORIZATION for Payolution Installment
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInstallment)
        {
            $requestType = Payone_Api_Enum_RequestType::AUTHORIZATION;
        }

        $request->setRequest($requestType);
        $request->setAid($this->configPayment->getAid());
        $request->setClearingtype($this->mapClearingType($paymentMethod));
        $request->setCurrency($order->getOrderCurrencyCode());
        $request->setReference($this->_getReferenceNumber($paymentMethod, $order));
        $request->setParam(''); // @comment currently empty

        $narrativeText = $this->getNarrativeText();
        $request->setNarrativeText($narrativeText);

        $request->setAmount($order->getGrandTotal());

        if($this->configPayment->getCurrencyConvert()) {
            $request->setCurrency($order->getBaseCurrencyCode());
            $request->setAmount($order->getBaseGrandTotal());
        }

    }


    /**
     * @return Payone_Api_Request_Parameter_Authorization_PersonalData
     */
    protected function mapPersonalParameters()
    {
        $helper = $this->helper();
        $order = $this->getOrder();
        $billingAddress = $order->getBillingAddress();
        $billingCountry = $billingAddress->getCountry();
        $customer = $order->getCustomer();
        $email = !empty($billingAddress->getEmail()) ? $billingAddress->getEmail() : $customer->getEmail();
        $paymentMethod = $this->getPaymentMethod();

        $personalData = new Payone_Api_Request_Parameter_Authorization_PersonalData();
        $personalData->setCustomerid($customer->getIncrementId());
        $personalData->setTitle($billingAddress->getPrefix());
        $personalData->setFirstname($billingAddress->getFirstname());
        $personalData->setLastname($billingAddress->getLastname());
        $personalData->setCompany($billingAddress->getCompany());

        $street = $helper->normalizeStreet($billingAddress->getStreet());
        $personalData->setStreet($street);
        $personalData->setAddressaddition($paymentMethod->getInfoInstance()->getPayoneBillingAddressaddition());
        $personalData->setZip($billingAddress->getPostcode());
        $personalData->setCity($billingAddress->getCity());
        $personalData->setCountry($billingCountry);
        $personalData->setEmail($email);
        $personalData->setTelephonenumber($billingAddress->getTelephone());

        $birthday = $this->formatBirthday($order->getCustomerDob());
        $personalData->setBirthday($birthday);

        $language = $helper->getDefaultLanguage();
        $personalData->setLanguage($language);
        $personalData->setVatid($order->getCustomerTaxvat());

        $global = $this->getConfigGeneral()->getGlobal();
        // Send Ip when enabled
        if ($global->getTransmitIp()) {
            $ip = $this->getCustomerIp();
            if(!$ip && $paymentMethod->getIsIpMandatory() === true) {
                $ip = Mage::helper('core/http')->getRemoteAddr();
            }

            $personalData->setIp($ip);
        }

        // US, CA, CN, JP, MX, BR, AR, ID, TH, IN always need state and shipping_state paramters
        if ($billingCountry == 'US' or $billingCountry == 'CA' or $billingCountry == 'CN' or $billingCountry == 'JP' or $billingCountry == 'MX' or
            $billingCountry == 'BR' or $billingCountry == 'AR' or $billingCountry == 'ID' or $billingCountry == 'TH' or $billingCountry == 'IN') {
            $regionCode = $billingAddress->getRegionCode();
            if(empty($regionCode)) {
                $regionCode = $billingAddress->getRegion();
            }

            $personalData->setState($regionCode);
        }

        // Safe Invoice "Klarna" specific personal parameters mapping
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice
                and $paymentMethod->getInfoInstance()->getPayoneSafeInvoiceType() == Payone_Api_Enum_FinancingType::KLV
        ) {
            $personalData = $this->mapPersonalParametersSafeInvoiceKlarna($personalData);
        }

        return $personalData;
    }

    /**
     * @param Payone_Api_Request_Parameter_Authorization_PersonalData $personalData
     * @return \Payone_Api_Request_Parameter_Authorization_PersonalData
     */
    protected function mapPersonalParametersSafeInvoiceKlarna($personalData)
    {
        $order = $this->getOrder();
        $billingAddress = $order->getBillingAddress();
        $billingCountry = $billingAddress->getCountry();
        $info = $this->getPaymentMethod()->getInfoInstance();

        // telephonenumber mandatory
        $telephone = $info->getPayoneCustomerTelephone();
        if (empty($telephone)) {
            $telephone = $billingAddress->getTelephone();
        }

        $personalData->setTelephonenumber($telephone);

        // birthday mandatory
        $birthdayDate = $info->getPayoneCustomerDob();
        if (empty($birthdayDate)) {
            $birthdayDate = $order->getCustomerDob();
        }

        $personalData->setBirthday($this->formatBirthday($birthdayDate));

        // IP Address is mandatory in case of "Klarna", even if not configured
        if ($personalData->getIp() == null) {
            $personalData->setIp($this->getCustomerIp());
        }

        if ($billingCountry == 'NL') {
            // addressaddition mandatory
            $addressAddition = $info->getPayoneBillingAddressaddition();
            if (empty($addressAddition)) {
                $addressAddition = $billingAddress->getStreet(2);
            }

            $personalData->setAddressaddition($addressAddition);
        }

        // Gender information and birthday Mandatory for Germany (DE), Netherlands (NL) and Austria (AT)
        if ($billingCountry == 'DE' or $billingCountry == 'NL' or $billingCountry == 'AT') {
            // gender
            $genderValue = $info->getPayoneCustomerGender();
            if (empty($genderValue)) {
                $genderValue = $order->getCustomerGender();
            }

            $customerResource = $this->getFactory()->getSingletonCustomerResource();
            /** @var Mage_Eav_Model_Entity_Attribute_Source_Table $genderSource */
            $genderSource = $customerResource->getAttribute('gender')->getSource();
            $genderLabel = $genderSource->getOptionText($genderValue);
            if ($genderLabel == 'Female') {
                $personalData->setGender(Payone_Api_Enum_Gender::FEMALE);
            }
            elseif ($genderLabel == 'Male') {
                $personalData->setGender(Payone_Api_Enum_Gender::MALE);
            }
        }

        // personalid mandatory for Denmark (DK), Finland (FI), Norway (NO) and Sweden (SE)
        if ($billingCountry == 'DK' or $billingCountry == 'FI' or $billingCountry == 'NO'
                or $billingCountry == 'SE'
        ) {
            $personalData->setPersonalid($info->getPayoneCustomerPersonalid());
        }

        return $personalData;
    }

    /**
     * @return string
     */
    protected function getCustomerIp()
    {
        $global = $this->getConfigGeneral()->getGlobal();
        $order = $this->getOrder();
        if ($global->getProxyMode()) {
            // Use X-Forwarded-For when in Proxy-Mode
            $remoteIp = $order->getXForwardedFor();
        }
        else {
            $remoteIp = $order->getRemoteIp();
        }

	// RFC 7239 suggests, we should take the first IP as the client's real IP
	// see: https://tools.ietf.org/html/rfc7239#section-7.5
        $remoteIps = explode(',', $remoteIp);
	$ip = array_shift($remoteIps);
        // remove leading Whitespace for Muliple IPs e.g. "0.0.0.0, 1.1.1.1"
        $ip = trim($ip);
        return $ip;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_DeliveryData
     */
    protected function mapDeliveryParameters()
    {
        $helper = $this->helper();
        $paymentMethod = $this->getPaymentMethod();
        $info = $paymentMethod->getInfoInstance();
        $order = $this->getOrder();
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice
                and $info->getPayoneSafeInvoiceType() === Payone_Api_Enum_FinancingType::BSV
        ) {
            $address = $this->getOrder()->getBillingAddress();
        } // Always use same address for BillSAFE
        else {
            $address = $this->getOrder()->getShippingAddress();
        }

        $deliveryData = new Payone_Api_Request_Parameter_Authorization_DeliveryData();

        $shippingCountry = $address->getCountry();

        $deliveryData->setShippingFirstname($address->getFirstname());
        $deliveryData->setShippingLastname($address->getLastname());
        $deliveryData->setShippingCompany($address->getCompany());
        $street = $helper->normalizeStreet($address->getStreet());
        $deliveryData->setShippingStreet($street);
        $deliveryData->setShippingZip($address->getPostcode());
        $deliveryData->setShippingCity($address->getCity());
        $deliveryData->setShippingCountry($shippingCountry);
        $deliveryData->setShippingAddressaddition($info->getPayoneShippingAddressaddition());

        // US, CA, CN, JP, MX, BR, AR, ID, TH, IN always need shipping_state paramters
        if ($shippingCountry == 'US' or $shippingCountry == 'CA' or $shippingCountry == 'CN' or $shippingCountry == 'JP' or $shippingCountry == 'MX' or
            $shippingCountry == 'BR' or $shippingCountry == 'AR' or $shippingCountry == 'ID' or $shippingCountry == 'TH' or $shippingCountry == 'IN') {
            $regionCode = $address->getRegionCode();
            if(empty($regionCode)) {
                $regionCode = $address->getRegion();
            }

            $deliveryData->setShippingState($regionCode);
        }

        // Safe Invoice type "Klarna"
        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice
                and $info->getPayoneSafeInvoiceType() == Payone_Api_Enum_FinancingType::KLV
        ) {
            // shipping addressaddition mandatory for Netherlands (NL)
            if ($shippingCountry == 'NL') {
                $shippingAddressAddition = $info->getPayoneShippingAddressaddition();
                if (empty($shippingAddressAddition)) {
                    $shippingAddressAddition = $address->getStreet(2);
                }

                $deliveryData->setShippingAddressaddition($shippingAddressAddition);
            }
        }

        return $deliveryData;
    }

    /**
     * @return Payone_Api_Request_Parameter_Invoicing_Transaction
     */
    protected function mapInvoicingParameters()
    {
        $order = $this->getOrder();

        $invoiceAppendix = $this->getInvoiceAppendix();

        $invoicing = new Payone_Api_Request_Parameter_Invoicing_Transaction();
        $invoicing->setInvoiceappendix($invoiceAppendix);
        
        // Order items:
        foreach ($order->getItemsCollection() as $key => $itemData) {
            /** @var $itemData Mage_Sales_Model_Order_Item */
            if ($itemData->isDummy()) {
                continue; // Do not map dummy items
            }

            $number = $itemData->getQtyToInvoice();
            if ($number <= 0) {
                continue; // Do not map items with zero quanity
            }

            $params['it'] = Payone_Api_Enum_InvoicingItemType::GOODS;
            $params['id'] = $itemData->getSku();
            $params['pr'] = $this->getItemPrice($itemData);
            $params['no'] = $number;
            $params['de'] = $itemData->getName();
//            $params['va'] = number_format($itemData->getTaxPercent(), 0, '.', '');
            $params['va'] = round($itemData->getTaxPercent() * 100);   // transfer vat in basis point format [#MAGE-186]

            $item = new Payone_Api_Request_Parameter_Invoicing_Item();
            $item->init($params);
            $invoicing->addItem($item);
        }

        // Shipping / Fees:
        if ($order->getShippingInclTax() > 0) {
            $invoicing->addItem($this->mapShippingFeeAsItem());
        }

        // Discounts:
        $discountAmount = $this->getOrderDiscountAmount($order); // Discount Amount is negative on order.
        if ($discountAmount > 0 || $discountAmount < 0) {
            $invoicing->addItem($this->mapDiscountAsItem($discountAmount));
        }

        return $invoicing;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_3dsecure
     */
    protected function map3dSecureParameters()
    {
        $secure3d = new Payone_Api_Request_Parameter_Authorization_3dsecure();
        // @comment 3D Secure is currently not available in Magento
        return $secure3d;
    }

    /**
     * @return Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
     */
    protected function mapPaymentParameters()
    {
        $payment = null;
        $paymentMethod = $this->getPaymentMethod();
        $info = $paymentMethod->getInfoInstance();
        $isRedirect = false;

        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_CashOnDelivery) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_CashOnDelivery();
            $payment->setShippingprovider(Payone_Api_Enum_Shippingprovider::DHL);
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Creditcard) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_CreditCard();

            // check if it is an adminorder and set ecommercemode to moto
            if ($this->getIsAdmin()) {
                $payment->setEcommercemode('moto');
            }

            $payment->setPseudocardpan($info->getPayonePseudocardpan());
            $isRedirect = true;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransfer ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferSofortueberweisung ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferGiropay ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferEps ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferIdl ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferPostFinanceEfinance ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferPostFinanceCard ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferP24 ||
                  $paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferBct
        ) {
            $country = $this->getOrder()->getBillingAddress()->getCountry();
            $payoneOnlinebanktransferType = $info->getPayoneOnlinebanktransferType();
            $iban = $info->getPayoneSepaIban();
            $bic = $info->getPayoneSepaBic();
            $bankaccount = $info->getPayoneAccountNumber();
            $bankcode = $info->getPayoneBankCode();

            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_OnlineBankTransfer();
            $payment->setBankcountry($country);
            $payment->setOnlinebanktransfertype($payoneOnlinebanktransferType);

            switch ($payoneOnlinebanktransferType) {
                case Payone_Api_Enum_OnlinebanktransferType::INSTANT_MONEY_TRANSFER:
                case Payone_Api_Enum_OnlinebanktransferType::GIROPAY:
                    if (!empty($iban) && !empty($bic)) {
                        $payment->setIban(strtoupper($iban));
                        $payment->setBic(strtoupper($bic)); // ensure bic and iban are sent uppercase
                    } elseif (!empty($bankaccount) && !empty($bankcode)) {
                        $payment->setBankaccount($bankaccount);
                        $payment->setBankcode($bankcode);
                    }
                    break;
                case Payone_Api_Enum_OnlinebanktransferType::IDEAL:
                    $payment->setBankgrouptype($info->getPayoneBankGroup());
                    break;
                case Payone_Api_Enum_OnlinebanktransferType::EPS_ONLINE_BANK_TRANSFER:
                    $payment->setBankgrouptype($info->getPayoneBankGroup());
                    break;
                case Payone_Api_Enum_OnlinebanktransferType::POSTFINANCE_EFINANCE:
                    break;
                case Payone_Api_Enum_OnlinebanktransferType::POSTFINANCE_CARD:
                    break;
            }

            $isRedirect = true;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Financing();
            $payment->setFinancingtype($info->getPayoneSafeInvoiceType());

            if ($info->getPayoneSafeInvoiceType() == Payone_Api_Enum_FinancingType::BSV) {
                // BillSAFE is a redirect payment method, Klarna not
                $isRedirect = true;
            } elseif ($info->getPayoneSafeInvoiceType() == Payone_Api_Enum_FinancingType::KLV) {
                $payment->setApiVersion();
                $birthdayDate = $this->getOrder()->getCustomerDob();
                if (empty($birthdayDate)) {
                    $birthdayDate = $info->getPayoneCustomerDob();
                    if($birthdayDate) {
                        $this->getOrder()->setCustomerDob($birthdayDate);
                    }
                }
            }
        }
        //Wallet PayDirekt
        elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletPaydirekt ||
            $paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletPaydirektExpress
        ) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet();
            $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();

            if ($paymentMethod->getConfig()->getWalletPaydirektEnableOvercapture()) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'over_capture', 'data' => 'yes')
                    )
                );
            }

            $payment->setWallettype(Payone_Api_Enum_WalletType::PAYDIREKT);
            $isRedirect = true;

            if ($this->_getWalletType() == 'PDT' && $this->getOrder()->getIsVirtual()) { // is Paydirekt and virtual/download?
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'shopping_cart_type', 'data' => 'DIGITAL')
                    )
                );
            }

            if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletPaydirektExpress) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'terms_accepted_timestamp', 'data' => (new DateTimeImmutable())->format(DATE_ISO8601))
                    )
                );
            }

            $payment->setPaydata($payData);
        }
        //Wallet PayPal Express
        elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletPaypalExpress) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet();

            $payment->setWallettype(Payone_Api_Enum_WalletType::PAYPAL_EXPRESS);
            $isRedirect = true;
        }
        //Wallet AliPay
        elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletAliPay) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet();

            $payment->setWallettype(Payone_Api_Enum_WalletType::ALIPAY);
            $isRedirect = true;
        }
        //Old Wallet Payment
        elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Wallet) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet();
            $payment->setWallettype($this->_getWalletType());
            $isRedirect = true;
            
            if ($this->_getWalletType() == 'PDT' && $this->getOrder()->getIsVirtual()) {// is Paydirekt and virtual/download?
                $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'shopping_cart_type', 'data' => 'DIGITAL')
                    )
                );
                $payment->setPaydata($payData);
            }
        }
        elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_DebitPayment) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_DebitPayment();
            $payment->setBankcountry($info->getPayoneBankCountry());
            $iban = $info->getPayoneSepaIban();
            $bic = $info->getPayoneSepaBic();

            if (!empty($iban)) {
                $payment->setIban(strtoupper($iban));
                if(!empty($bic)) {
                    $payment->setBic(strtoupper($bic)); // ensure bic and iban are sent uppercase
                }
            } else {
                $payment->setBankaccount($info->getPayoneAccountNumber());
                $payment->setBankcode($info->getPayoneBankCode());
            }

            $payment->setBankaccountholder($info->getPayoneAccountOwner());
            // for frontend orders set mandate identification if data provided in checkout session:
            if (!$this->getIsAdmin()) {
                $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
                $mandateStatus = $checkoutSession->getPayoneSepaMandateStatus();
                $mandateIdentification = $checkoutSession->getPayoneSepaMandateIdentification();
                if ($mandateStatus == Payone_Core_Model_Service_Management_ManageMandate::STATUS_PENDING
                        and !empty($mandateIdentification)
                ) {
                    $payment->setMandateIdentification($mandateIdentification);
                }
            }
        }
        elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_Barzahlen) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Barzahlen();
            $payment->setApiVersion();
            $payment->setCashtype();
        }
        elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_Ratepay
            || $paymentMethod instanceof Payone_Core_Model_Payment_Method_RatepayInvoicing
        ) {
            if($paymentMethod instanceof Payone_Core_Model_Payment_Method_RatepayInvoicing){
                $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePayInvoicing();
                $payment->setFinancingtype(Payone_Api_Enum_RatepayInvoicingType::RPV);
            }
            else {
                $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePay();
                $payment->setFinancingtype(Payone_Api_Enum_RatepayType::RPS);
            }
            $payment->setApiVersion();

            $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
            $mandateStatus = $checkoutSession->getRatePayFingerprint();
            /**
             * if RatePay Type is Installment map Installmentplan Data
             * from payone session
             */
            if ($payment->getFinancingtype() == Payone_Api_Enum_RatepayInvoicingType::RPV) {
                $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
            } else {
                $ratePayInstallmentData = $this->_getResult($paymentMethod->getCode());

                $postPayment = Mage::app()->getRequest()->getPost('payment');
                $ratePayInstallmentData['payone_ratepay_debit-paytype'] = $this->getInstallmentDebitType(
                    $postPayment['payone_ratepay_debit_type'],
                    $paymentMethod->getConfig()
                );

                /*@var $payData Payone_Api_Request_Parameter_Paydata_Paydata*/
                $payData = $this->mapRatePayInstallmentParameters($ratePayInstallmentData);

                $payment->setBankcountry($info->getPayoneBankCountry());
                $iban = $info->getPayoneSepaIban();
                $bic = $info->getPayoneSepaBic();
                if (!empty($iban)) {
                    $payment->setIban(strtoupper($iban));
                    if(!empty($bic) && strtoupper(substr($iban,0,2)) !== 'DE') {
                        $payment->setBic(strtoupper($bic)); // ensure bic and iban are sent uppercase
                    }
                }
                $payment->setBankaccountholder($info->getPayoneAccountOwner());
            }

            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'customer_allow_credit_inquiry', 'data' => 'yes') // hardcoded by concept
                )
            );
            if ($checkoutSession->getRatePayFingerprint()) { // MAGE-347 only in frontend
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'device_token', 'data' => $checkoutSession->getRatePayFingerprint())
                    )
                );
            }
            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'shop_id', 'data' => $info->getPayoneRatepayShopId())
                )
            );

            if ((bool)$info->getPayoneIsb2b() === true) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'b2b', 'data' => 'yes')
                    )
                );
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'company_id', 'data' => $info->getPayoneTradeRegistryNumber())
                    )
                );
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'vat_id', 'data' => $info->getPayoneVatId())
                    )
                );
            } else {
                $birthdayDate = $info->getPayoneCustomerDob();
                if (empty($birthdayDate)) {
                    $birthdayDate = $this->getOrder()->getCustomerDob();
                }
                if ($birthdayDate) {
                    $payment->setBirthday($this->formatBirthday($birthdayDate));
                }
            }

            // MAGE-448 : add customerId to transmitted data
            $customerId = $this->getOrder()->getCustomerId();
            if (!empty($customerId)) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'merchant_consumer_id', 'data' => $customerId)
                    )
                );
            }

            $payment->setPaydata($payData);

            $telephone = $info->getPayoneCustomerTelephone();
            if (empty($telephone)) {
                $telephone = $this->getOrder()->getBillingAddress()->getTelephone();
            }

            $payment->setTelephonenumber($telephone);
        }
        elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_RatepayDirectDebit) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_RatePayDirectDebit();
            $payment->setFinancingtype($this->_getRatePayType());
            $payment->setApiVersion();

            $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();

            $mandateStatus = $checkoutSession->getRatePayFingerprint();

            $payment->setBankcountry($info->getPayoneBankCountry());
            $iban = $info->getPayoneSepaIban();
            $bic = $info->getPayoneSepaBic();

            if (!empty($iban)) {
                $payment->setIban(strtoupper($iban));
                if(!empty($bic) && strtoupper(substr($iban,0,2)) !== 'DE') {
                    $payment->setBic(strtoupper($bic)); // ensure bic and iban are sent uppercase
                }
            }

            $payment->setBankaccountholder($info->getPayoneAccountOwner());

            $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'customer_allow_credit_inquiry', 'data' => 'yes') // hardcoded by concept
                )
            );
            if ($checkoutSession->getRatePayFingerprint()) { // MAGE-347 only in frontend
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'device_token', 'data' => $checkoutSession->getRatePayFingerprint())
                    )
                );
            }
            $payData->addItem(
                new Payone_Api_Request_Parameter_Paydata_DataItem(
                    array('key' => 'shop_id', 'data' => $info->getPayoneRatepayShopId())
                )
            );

            if ((bool)$info->getPayoneIsb2b() === true) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'b2b', 'data' => 'yes')
                    )
                );

                if ($this->getOrder()->getBillingAddress()->getCountryId() !== 'AT') {
                    $payData->addItem(
                        new Payone_Api_Request_Parameter_Paydata_DataItem(
                            array('key' => 'company_id', 'data' => $info->getPayoneTradeRegistryNumber())
                        )
                    );
                    $payData->addItem(
                        new Payone_Api_Request_Parameter_Paydata_DataItem(
                            array('key' => 'vat_id', 'data' => $info->getPayoneVatId())
                        )
                    );
                }
            } else {
                $birthdayDate = $info->getPayoneCustomerDob();
                if (empty($birthdayDate)) {
                    $birthdayDate = $this->getOrder()->getCustomerDob();
                }
                if ($birthdayDate) {
                    $payment->setBirthday($this->formatBirthday($birthdayDate));
                }
            }

            // MAGE-448 : add customerId to transmitted data
            $customerId = $this->getOrder()->getCustomerId();
            if (!empty($customerId)) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'merchant_consumer_id', 'data' => $customerId)
                    )
                );
            }

            $payment->setPaydata($payData);

            $telephone = $info->getPayoneCustomerTelephone();
            if (empty($telephone)) {
                $telephone = $this->getOrder()->getBillingAddress()->getTelephone();
            }

            $payment->setTelephonenumber($telephone);
        }
        elseif($paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionDebit ||
                 $paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInvoicing ||
                 $paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInstallment
                || $paymentMethod instanceof Payone_Core_Model_Payment_Method_Payolution )
        {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Payolution();
            $payment->setApiVersion();
            $payment->setFinancingtype($info->getPayonePayolutionType());

            $payment->setWorkorderid($info->getPayoneWorkorderid());
            $payment->setIban(strtoupper($info->getPayonePayolutionIban()));
            $payment->setBic(strtoupper($info->getPayonePayolutionBic()));

            $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
            $payment->setWorkorderid($checkoutSession->getPayoneWorkorderId());
            $info->setPayoneWorkorderId($checkoutSession->getPayoneWorkorderId());
            
            $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
            
            if ($info->getPayonePayolutionType() == Payone_Api_Enum_PayolutionType::PYS) {
                $checkoutSession->unsInstallmentDraftLinks();// unset previously set draft links
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'installment_duration', 'data' => $info->getPayonePayolutionInstallmentDuration())
                    )
                );
            }

            $payonePayolutionType = $paymentMethod->getInfoInstance()->getPayonePayolutionType();
            if((bool)$info->getPayoneIsb2b() === true && $payonePayolutionType == Payone_Api_Enum_PayolutionType::PYV) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'b2b', 'data' => 'yes')
                    )
                );
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        array('key' => 'company_uid', 'data' => $info->getPayoneVatId())
                    )
                );
            } else {
                $birthdayDate = $info->getPayoneCustomerDob();
                if (empty($birthdayDate)) {
                    $birthdayDate = $this->getOrder()->getCustomerDob();
                }

                if($birthdayDate) {
                    $payment->setBirthday($this->formatBirthday($birthdayDate));
                }
            }

            /** @var Payone_Core_Helper_Payolution $payolutionHelper */
            $payolutionHelper = Mage::helper('payone_core/payolution');
            if($analysisSessionId = $payolutionHelper->getAnalysisSessionId($paymentMethod, true)){
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem([
                        'key'  => 'analysis_session_id',
                        'data' => $analysisSessionId
                    ])
                );
            }

            $payment->setPaydata($payData);

            if ($this->getOrder()->getBillingAddress()->getCountryId() == 'NL') {
                $telephone = $this->getOrder()->getBillingAddress()->getTelephone();
                if (empty($telephone)) {
                    $telephone = $info->getPayoneCustomerTelephone();
                }

                $payment->setTelephonenumber($telephone);
            }
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_AmazonPay) {
            /** @var \Payone_Core_Model_Session $session */
            $session = Mage::getSingleton('payone_core/session');
            /** @var array $paydata */
            $paydata = $session->getData('amazon_add_paydata');
            /** @var \Payone_Core_Model_Config_Payment_Method $config */
            $config = $paymentMethod->getConfig();

            switch ($config->getAmzSyncMode()) {
                case Payone_Core_Model_System_Config_AmazonSyncMode::ASYNCHRONOUS_ON_FAILURE:
                    if ($session->getData('amazon_retry_async') === true) {
                        $paydata['amazon_timeout'] = 1440;
                    } else {
                        $session->setData('amazon_retry_async', true);
                        $paydata['amazon_timeout'] = 0;
                    }
                    break;
                case Payone_Core_Model_System_Config_AmazonSyncMode::ALWAYS_ASYNCHRONOUS:
                    $paydata['amazon_timeout'] = 1440;
                    break;
                case Payone_Core_Model_System_Config_AmazonSyncMode::ALWAYS_SYNCHRONOUS:
                    $paydata['amazon_timeout'] = 0;
                    $paydata['cancel_on_timeout'] = 'yes';
                    break;
            }

            // Comment out to test 'InvalidPaymentMethod' scenario with the Payone API
            /*
            $paydata['authorization_note_for_testing'] = '{
                "SandboxSimulation": {
                    "State": "Declined",
                    "ReasonCode": "InvalidPaymentMethod",
                    "SoftDecline": "false"
                }
            }';
            */

            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_AmazonPay([
                'successurl' => $this->helperUrl()->getSuccessUrl(),
                'errorurl'   => $this->helperUrl()->getErrorUrl(),
            ], $paydata);
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_PaymentGuaranteeInvoice) {
            $payment = new Payone_Api_Request_Parameter_Authorization_PaymentMethod_PaymentGuaranteeInvoice();
            $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();
            if ((bool)$info->getPayoneIsb2b() === true) {
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        ['key' => 'b2b', 'data' => 'yes']
                    )
                );
                $payData->addItem(
                    new Payone_Api_Request_Parameter_Paydata_DataItem(
                        ['key' => 'company_trade_registry_number', 'data' => $info->getPayoneTradeRegistryNumber()]
                    )
                );
            } else {
                $birthdayDate = $info->getPayoneCustomerDob();
                if (empty($birthdayDate)) {
                    $birthdayDate = $this->getOrder()->getCustomerDob();
                }
                if ($birthdayDate) {
                    $payment->setBirthday($this->formatBirthday($birthdayDate));
                }
            }

            if((bool)$info->getPayoneIsb2b() === true) {
                $payment->setBusinessRelation(Payone_Api_Enum_BusinessRelationType::B2B);
            }
            else{
                $payment->setBusinessRelation(Payone_Api_Enum_BusinessRelationType::B2C);
            }

            $payment->setPaydata($payData);
            $payment->setClearingsubtype();
        }

        if ($isRedirect === true) {
            $encodedOrderId = base64_encode($this->getOrder()->getEntityId());
            $successurl = $this->helperUrl()->getSuccessUrl() . "reference/{$encodedOrderId}/";
            $errorurl = $this->helperUrl()->getErrorUrl() . "reference/{$encodedOrderId}/";
            $backurl = $this->helperUrl()->getBackUrl() . "reference/{$encodedOrderId}/";

            $payment->setSuccessurl($successurl);
            $payment->setErrorurl($errorurl);
            $payment->setBackurl($backurl);
        }

        return $payment;
    }
    
    protected function _getWalletType() 
    {
        $sType = false;

        $aPostPayment = Mage::app()->getRequest()->getPost('payment');

        if($aPostPayment && array_key_exists('payone_wallet_type', $aPostPayment)) {
            $sType = $aPostPayment['payone_wallet_type'];
        } else {
            if($aPostPayment && array_key_exists('payone_wallet_paydirekt_type', $aPostPayment)) {
                $sType = $aPostPayment['payone_wallet_paydirekt_type'];
            } elseif($aPostPayment && array_key_exists('payone_wallet_paydirekt_express_type', $aPostPayment)) {
                $sType = $aPostPayment['payone_wallet_paydirekt_express_type'];
            }
            else {
                $sType = Payone_Api_Enum_WalletType::PAYPAL_EXPRESS;
            }
        }

        return $sType;
    }

    /**
     * @return bool|string
     */
    protected function _getRatePayType()
    {
        $sType = false;

        $aPostPayment = Mage::app()->getRequest()->getPost('payment');

        if ($aPostPayment && array_key_exists('payone_ratepay_type', $aPostPayment)) {
            $sType = $aPostPayment['payone_ratepay_type'];
        } else {
            $sType = Payone_Api_Enum_RatepayInvoicingType::RPV;
        }

        return $sType;
    }

    /**
     * @param Payone_Core_Model_Payment_Method_Abstract $paymentMethod
     * @return string
     */
    protected function mapClearingType(Payone_Core_Model_Payment_Method_Abstract $paymentMethod)
    {
        $clearingType = '';

        if ($paymentMethod instanceof Payone_Core_Model_Payment_Method_CashOnDelivery) {
            $clearingType = Payone_Enum_ClearingType::CASHONDELIVERY;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Creditcard) {
            $clearingType = Payone_Enum_ClearingType::CREDITCARD;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransfer) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFER;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferSofortueberweisung) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFERSOFORT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferIdl) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFERIDL;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferPostFinanceCard) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFERPFC;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferp24) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFERP24;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferPostFinanceEfinance) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFERPFF;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferGiropay) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFERGIROPAY;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferEps) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFEREPS;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_OnlineBankTransferBct) {
            $clearingType = Payone_Enum_ClearingType::ONLINEBANKTRANSFERBCT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Wallet) {
            $clearingType = Payone_Enum_ClearingType::WALLET;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletPaydirekt) {
            $clearingType = Payone_Enum_ClearingType::WALLETPAYDIREKT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletPaydirektExpress) {
            $clearingType = Payone_Enum_ClearingType::WALLETPAYDIREKTEXPRESS;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletPaypalExpress) {
            $clearingType = Payone_Enum_ClearingType::WALLETPAYPALEXPRESS;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_WalletAliPay) {
            $clearingType = Payone_Enum_ClearingType::WALLETALIPAY;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Invoice) {
            $clearingType = Payone_Enum_ClearingType::INVOICE;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_AdvancePayment) {
            $clearingType = Payone_Enum_ClearingType::ADVANCEPAYMENT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_DebitPayment) {
            $clearingType = Payone_Enum_ClearingType::DEBITPAYMENT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_SafeInvoice) {
            $clearingType = Payone_Enum_ClearingType::FINANCING;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Barzahlen) {
            $clearingType = Payone_Enum_ClearingType::BARZAHLEN;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Ratepay) {
            $clearingType = Payone_Enum_ClearingType::RATEPAY;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_RatepayInvoicing) {
            $clearingType = Payone_Enum_ClearingType::RATEPAYINVOICING;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_RatepayDirectDebit) {
            $clearingType = Payone_Enum_ClearingType::RATEPAYDIRECTDEBIT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInvoicing) {
            $clearingType = Payone_Enum_ClearingType::PAYOLUTIONINVOICING;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionDebit) {
            $clearingType = Payone_Enum_ClearingType::PAYOLUTIONDEBIT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_PayolutionInstallment) {
            $clearingType = Payone_Enum_ClearingType::PAYOLUTIONINSTALLMENT;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_Payolution) {
            $clearingType = Payone_Enum_ClearingType::PAYOLUTION;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_AmazonPay) {
            $clearingType = Payone_Enum_ClearingType::AMAZONPAY;
        } elseif ($paymentMethod instanceof Payone_Core_Model_Payment_Method_PaymentGuaranteeInvoice) {
            $clearingType = Payone_Enum_ClearingType::PAYMENTGUARANTEEINVOICE;
        }

        return $clearingType;
    }

    /**
     * @param $date
     * @return string
     */
    public function formatBirthday($date)
    {
        if (strlen($date) > 0) {
            $date = substr($date, 0, 4) . substr($date, 5, 2) . substr($date, 8, 2);
        }

        return $date;
    }

    /**
     * @param $installmentData
     * @return Payone_Api_Request_Parameter_Paydata_Paydata
     */
    private function mapRatePayInstallmentParameters($installmentData)
    {
        $payData = new Payone_Api_Request_Parameter_Paydata_Paydata();

        // MAGE-363: Convert amounts into EUR-cents (integer)
        $installmentAmount = $installmentData['payone_ratepay_rate'] * 100;
        $lastInstallmentAmount = $installmentData['payone_ratepay_last-rate'] * 100;
        $amount = $installmentData['payone_ratepay_total-amount'] * 100;
        $interestRate = $installmentData['payone_ratepay_interest-rate'] * 100;

        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'debit_paytype', 'data' => $installmentData['payone_ratepay_debit-paytype'])
            )
        );

        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'installment_amount', 'data' => $installmentAmount)
            )
        );
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'last_installment_amount', 'data' => $lastInstallmentAmount)
            )
        );
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'installment_number', 'data' => $installmentData['payone_ratepay_number-of-rates'])
            )
        );
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'amount', 'data' => $amount)
            )
        );
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'interest_rate', 'data' => $interestRate)
            )
        );
        $payData->addItem(
            new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'payment_firstday', 'data' => $installmentData['payone_ratepay_payment-firstday'])
            )
        );

        return $payData;
    }

    /**
     * Returns the session saved installment plan
     * @return array
     */
    private function _getResult($paymentMethod) 
    {
        $data = array();

        // In case of Admin order
        if (!empty($_POST['payone_ratepay_installment'])) {
            foreach ($_POST['payone_ratepay_installment'] as $key => $value) {
                $data[$key] = $value;
            }

            return $data;
        }

        // Normal frontend order
        foreach (Mage::getSingleton('payone_core/session')->getData() as $key => $value) {
            if (!is_array($value)) {
                $sessionNameBeginning = substr($key, 0, strlen($paymentMethod)); // session variable name prefix = payment method
                if ($sessionNameBeginning == $paymentMethod && $key[strlen($paymentMethod)] == "_") { // if session variable belongs to current payment method
                    $shortKey = lcfirst($key); // use postfix as array key
                    $data[$shortKey] = $value;
                }
            }
        }

        return $data;
    }

    /**
     * @param Mage_Sales_Model_Order_Item $itemData
     * @return float
     */
    private function getItemPrice(Mage_Sales_Model_Order_Item $itemData)
    {
        if($this->configPayment->getCurrencyConvert()) {
            return $itemData->getBasePriceInclTax();
        }

        return $itemData->getPriceInclTax();
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return float
     */
    private function getOrderDiscountAmount(Mage_Sales_Model_Order $order)
    {
        if($this->configPayment->getCurrencyConvert()) {
            return $order->getBaseDiscountAmount();
        }

        return $order->getDiscountAmount();
    }

    /**
     * @param string $paramDebitType
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return string
     */
    private function getInstallmentDebitType($paramDebitType, $config)
    {
        if ($paramDebitType == Payone_Api_Enum_RatepayDebitType::BANK_TRANSFER) {
            return $paramDebitType;
        }

        // If Switzerland, debit-type is forced to Bank-Transfer
        $country = $this->getOrder()->getBillingAddress()->getCountry();
        if ($country == 'CH') {
            return Payone_Api_Enum_RatepayDebitType::BANK_TRANSFER;
        }

        // If RPS Lastschrift is not allowed for the country,
        // debit-type is forced to Bank-Transfer
        $ratepayDirectDebitAllowSpecific = $config->getRatepayDirectdebitAllowspecific();
        if ($ratepayDirectDebitAllowSpecific == 1) {
            $allowedCountries = explode(',', $config->getRatepayDirectDebitSpecificCountry());
            if (!in_array($country, $allowedCountries)) {
                return Payone_Api_Enum_RatepayDebitType::BANK_TRANSFER;
            }
        }

        return $paramDebitType;
    }
}
