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
class Payone_Core_Block_Payment_Method_Form_OnlineBankTransfer
    extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    /**
     * @var bool
     */
    protected $hasTypes = true;
    /**
     * @var null
     */
    protected $config = null;
    /**
     * @var string
     */
    protected $formattedFeePrice = '';
    /**
     * @var bool
     */
    protected $isCvc = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/onlinebanktransfer.phtml');
    }

    /**
     * Returns quote country
     * @return string
     */
    public function getCountry()
    {
        $quote = $this->getQuote();
        return $quote->getBillingAddress()->getCountry();
    }

    /**
     * Returns currency
     * @return string
     */
    public function getCurrency()
    {
        $quote = $this->getQuote();
        return $quote->getQuoteCurrencyCode();
    }

    /**
     * @return string
     */
    public function getFormattedFeeConfigForOnlineBankTransfer()
    {
        $this->config = $this->getPaymentConfig();
        if($this->config) {

            $quote = $this->getQuote();

            $feeConfig = $this->config->getFeeConfigForQuote($quote);

            if (is_array($feeConfig) and array_key_exists('fee_config', $feeConfig) and !empty($feeConfig['fee_config']))
            {
                $this->formattedFeePrice = $this->getFormattedFeePriceLabel($this->_calcFeePrice());
            }
        }
        return $this->formattedFeePrice;
    }

    /**
     * @return string
     */
    public function getCvcConfig()
    {
        $this->config = $this->getPaymentConfig();

        if($this->config){
            $this->isCvc = $this->config->getCheckCvc();
        }

        return $this->isCvc;
    }

    /**
     * @return array
     */
    public function onlineBankTransferTypeMapping()
    {
        return $onlineBankTransferPayment = array(
            Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERSOFORT => Payone_Api_Enum_OnlinebanktransferType::INSTANT_MONEY_TRANSFER,
            Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERGIROPAY => Payone_Api_Enum_OnlinebanktransferType::GIROPAY,
            Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERPFF => Payone_Api_Enum_OnlinebanktransferType::POSTFINANCE_EFINANCE,
            Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERPFC => Payone_Api_Enum_OnlinebanktransferType::POSTFINANCE_CARD,
            Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERP24 => Payone_Api_Enum_OnlinebanktransferType::P24
        );
    }

    /**
     * @return array
     */
    protected function getSystemConfigMethodTypes()
    {
        return $this->getFactory()->getModelSystemConfigOnlinebanktransferType()->toSelectArray();
    }

    /**
     * Retrieve the payment config method id from Quote.
     * If it matches payment method, return it, otherwise 0
     * @return int|mixed
     */
    public function getPaymentMethodConfigId()
    {
        $preselectedConfigId = $this->getInfoData('payone_config_payment_method_id');

        $preselectPossible = false;
        foreach ($this->getTypes() as $type) {
            if ($type['config_id'] == $preselectedConfigId) {
                $preselectPossible = true;
            }
        }

        if ($preselectPossible) {
            return $preselectedConfigId;
        }
        else {
            return 0;
        }
    }
    
    /**
     * Return if iban+bic have to be shown for SofortÜberweisung
     * 
     * @return bool
     */
    public function showSofortUeberweisungBankDataFields()
    {
        return $this->getMethod()->getConfig()->getSofortueberweisungShowIban();
    }
}