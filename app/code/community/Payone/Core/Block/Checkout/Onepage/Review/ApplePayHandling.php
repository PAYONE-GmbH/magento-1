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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Checkout_Onepage_Review_ApplePayHandling extends Mage_Core_Block_Template
{
    /** @var Mage_Sales_Model_Quote */
    protected $quote;

    /** @var array */
    protected $cardTypesMapping = array(
        Payone_Api_Enum_CreditcardType::VISA => 'visa',
        Payone_Api_Enum_CreditcardType::MASTERCARD => 'masterCard',
        Payone_Api_Enum_CreditcardType::AMEX => 'amex',
        Payone_Api_Enum_CreditcardType::DINERS => 'discover'
    );

    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $checkoutSession = $this->_getCheckoutSession();
        $this->quote = $checkoutSession->getQuote();
    }

    /**
     * @return bool
     */
    public function isApplePayPayment()
    {
        $paymentMethod = $this->quote->getPayment()->getMethod();

        return $paymentMethod === Payone_Core_Model_System_Config_PaymentMethodCode::APPLEPAY;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->quote->getGrandTotal();
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->quote->getBillingAddress()->getCountry();
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->quote->getQuoteCurrencyCode();
    }

    /**
     * @return array
     */
    public function getSupportedNetworks()
    {
        try {
            $paymentMethod = $this->quote->getPayment()->getMethodInstance();
            $paymentConfig = $paymentMethod->getConfigForQuote($this->quote);
            $supportedNetworks = $paymentConfig->getTypes();
            $mappedNetwork = [];
            foreach ($supportedNetworks as $cardType) {
                if (isset($this->cardTypesMapping[$cardType])) {
                    $mappedNetwork[] = $this->cardTypesMapping[$cardType];
                }
            }

            return $mappedNetwork;
        } catch (Exception $e) {
            Mage::logException($e);
            return array();
        }
    }

    /**
     * @return string
     */
    public function getCreateSessionUrl()
    {
        return Mage::getUrl('payone_core/applepay/createApplePaySession', []);
    }

    /**
     * @return string
     */
    public function getCreatePaymentUrl()
    {
        return Mage::getUrl('payone_core/applepay/createApplePayPayment', []);
    }

    /**
     * @return string
     */
    public static function getRegisterDeviceUrl()
    {
        return Mage::getUrl('payone_core/applepay/registerDevice', []);
    }

    /**
     * @return \Mage_Checkout_Model_Session
     */
    private function _getCheckoutSession()
    {
        /** @var \Mage_Checkout_Model_Session $session */
        $session = Mage::getSingleton('checkout/session');
        return $session;
    }
}