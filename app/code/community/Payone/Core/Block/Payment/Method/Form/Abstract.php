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
class Payone_Core_Block_Payment_Method_Form_Abstract
    extends Mage_Payment_Block_Form
{
    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $paymentConfig = null;

    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * @var array
     */
    protected $types = null;
    /**
     * @var bool
     */
    protected $hasTypes = false;

    public function getMethodTitle()
    {
        return $this->getPaymentConfig()->getName();
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->getMethod()->getInfoInstance()->getQuote();
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getPaymentConfig()
    {
        if ($this->paymentConfig === null) {
            /** @var $method Payone_Core_Model_Payment_Method_Abstract */
            $method = $this->getMethod();
            $this->paymentConfig = $method->getConfigForQuote($this->getQuote());
        }
        return $this->paymentConfig;
    }

    /**
     * @return Payone_Core_Model_Config_General
     */
    public function getConfigGeneral()
    {
        $storeId = $this->getQuote()->getStoreId();
        return $this->helperConfig()->getConfigGeneral($storeId);
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface[]
     */
    public function getPaymentConfigs()
    {
        $quote = $this->getQuote();
        /** @var $method Payone_Core_Model_Payment_Method_Creditcard */
        $method = $this->getMethod();
        $configs = $method->getAllConfigsByQuote($quote);
        return $configs;
    }

    /**
     * @return array|bool
     */
    protected function getFeeConfig()
    {
        return $this->getPaymentConfig()->getFeeConfigForQuote($this->getQuote());
    }

    protected function _calcFeePrice() {
        $oQuote = $this->getQuote();
        
        $feeConfig = $this->getFeeConfig();

        $price = 0.0;
        if (is_array($feeConfig) and array_key_exists('fee_config', $feeConfig) and !empty($feeConfig['fee_config'])) {
            $price = $feeConfig['fee_config'];
            if(isset($feeConfig['fee_type'][0]) && $feeConfig['fee_type'][0] == 'percent') {
                $price = $oQuote->getSubtotal() * $price / 100;
            }
            #$oQuote->getSubtotal();
            #$oQuote->getGrandTotal();
        }
        return $price;
    }
    
    /**
     * Formatted Fee price e.g. '2,50 €' or '$11.50'
     * @return string
     */
    public function getFeePrice()
    {
        $price = $this->_calcFeePrice();
        
        $formattedPrice = $this->getQuote()->getStore()->formatPrice($price);
        return $formattedPrice;
    }

    /**
     * @param float $price
     * @return string
     */
    protected function getFormattedFeePriceLabel($price)
    {
        $formattedFeePrice = $this->getQuote()->getStore()->formatPrice($price);
        $text = $this->__('(+ %s)', $formattedFeePrice);
        return $text;
    }

    /**
     * Provide an array of credit card types for the template
     *
     * Each value is an array, with the keys:
     * code - type code, e.g. 'V', PNC', 'BSV'
     * name - name for display, e.g. "Visa", "Sofortueberweisung" "BillSafe'
     * fee - Formatted fee price for this type, empty if no price is configured.
     * check_cvc - no, only_first, always - depends on the used configuration. only set for Creditcard
     * config_id - Id of the payment method configuration for this card type
     *
     * @api
     * @return array
     */
    public function getTypes()
    {
        if($this->hasTypes === false){
            return null;
        }
        if ($this->types === null) {
            $quote = $this->getQuote();
            $return = array();

            $systemTypes = $this->getSystemConfigMethodTypes();

            /** @var $config Payone_Core_Model_Config_Payment_Method */
            foreach ($this->getPaymentConfigs() as $key => $config) {
                $feeConfig = $config->getFeeConfigForQuote($quote);

                if (is_array($feeConfig) and array_key_exists('fee_config', $feeConfig) and !empty($feeConfig['fee_config'])) {
                    $formattedFeePrice = $this->getFormattedFeePriceLabel($this->_calcFeePrice());
                }
                else {
                    $formattedFeePrice = '';
                }

                $checkCvc = $config->getCheckCvc();
                $configId = $config->getId();

                $configTypes = $config->getTypes();
                if (!is_array($configTypes)) {
                    continue;
                }

                foreach ($configTypes as $keyType => $typeCode) {
                    $configTypeKey = $configId . '_' . $typeCode; // key to correctly identify this config and type
                    $return[$configTypeKey]['code'] = $typeCode;

                    if (array_key_exists($typeCode, $systemTypes)) {
                        $typeName = $this->__($systemTypes[$typeCode]);
                    }
                    else {
                        $typeName = $this->__($this->getMethodCode() . '_type_'. $typeCode);
                    }

                    $return[$configTypeKey]['name'] = $typeName;
                    $return[$configTypeKey]['fee'] = $formattedFeePrice;
                    $return[$configTypeKey]['check_cvc'] = $checkCvc;
                    $return[$configTypeKey]['config_id'] = $configId;
                }
            }
            $this->types = $return;
        }
        return $this->types;
    }

    /**
     * @return array
     */
    protected function getSystemConfigMethodTypes()
    {
    }


    /**
     * Get formatted additional fee string
     *
     * @override Overrides magic method that is used in magento template (checkout/onepage/payment/methods.phtml)
     * @return string
     */
    public function getMethodLabelAfterHtml()
    {
        if (false == $this->getFeeConfig()) {
            return '';
        }
        $text = '(+ %s)';
        $text = $this->__($text, $this->getFeePrice());

        $id = 'payone_payment_fee_' . $this->getMethodCode();

        $formatting = ' <span id=' . $id . '>' . $text . '</span>';
        
        return $formatting;
    }

    /**
     * @return bool
     */
    public function hasMethodTitle()
    {
        return true;
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function helperConfig()
    {
        return $this->getFactory()->helperConfig();
    }

    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }
        return $this->factory;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getSavedCustomerData($key)
    {
        $paymentConfig = $this->getPaymentConfig();
        if(Mage::getSingleton('customer/session')->isLoggedIn() && $paymentConfig->getCustomerFormDataSave()) {
            $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
            $paymentCustomerModel = Mage::getModel('payone_core/domain_customer')->loadByCustomerIdPaymentCode($customerId, $this->getMethodCode());
            if($keyData = $paymentCustomerModel->getCustomerData($key)) {
                return $keyData;
            }
        }
        return '';
    }

    /**
     * @param string $text
     * @return string
     */
    public function strToXXX($text) {
        if(!empty($text)) {
            $result = str_repeat('x', strlen($text) - 8);
            $result = substr($text, 0, 4).$result.substr($text, -4);
        } else {
            $result = $text;
        }
        return $result;
    }
}