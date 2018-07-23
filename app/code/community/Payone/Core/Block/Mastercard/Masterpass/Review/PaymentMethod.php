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
 * @subpackage      Mastercard_Masterpass
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Block_Mastercard_Masterpass_Review_PaymentMethod extends Mage_Core_Block_Template
{
    /** @var int */
    protected $quoteId;
    /** @var string */
    protected $cardType = "";
    /** @var string */
    protected $cardTruncatedPan = "";
    /** @var string */
    protected $cardExpireDate = "";

    protected $cardTypeMap = array(
        Payone_Api_Enum_CreditcardType::MASTERCARD => 'Mastercard',
        Payone_Api_Enum_CreditcardType::VISA => 'Visa',
        Payone_Api_Enum_CreditcardType::MASTERCARD => 'MasterCard',
        Payone_Api_Enum_CreditcardType::AMEX => 'Amex',
        Payone_Api_Enum_CreditcardType::DINERS => 'Diners',
        Payone_Api_Enum_CreditcardType::JCB => 'JCB',
        Payone_Api_Enum_CreditcardType::MAESTRO_INTERNATIONAL => 'Maestro International',
        Payone_Api_Enum_CreditcardType::MAESTRO_UK => 'Maestro UK',
        Payone_Api_Enum_CreditcardType::DISCOVER => 'Discover',
        Payone_Api_Enum_CreditcardType::CARTE_BLEUE => 'Carte Bleue',
        Payone_Api_Enum_CreditcardType::CHINA_UNION_PAY => 'China Union Pay',
    );

    public function init()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($this->quoteId);
        if (!$quote) {
            throw new Payone_Core_Exception_OrderNotFound('Quote with ID ' . $this->quoteId . ' was not found.');
        }

        $this->setCardType($quote->getPayment()->getCcType())
            ->setCardTruncatedPan($quote->getPayment()->getCcNumberEnc())
            ->setCardExpireDate($quote->getPayment()->getCcExpMonth() . '/' . $quote->getPayment()->getCcExpYear());
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
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_PaymentMethod
     */
    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param string $cardType
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_PaymentMethod
     */
    public function setCardType($cardType)
    {
        if (!empty($this->cardTypeMap[$cardType])) {
            $cardType = $this->cardTypeMap[$cardType];
        }
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardTruncatedPan()
    {
        return $this->cardTruncatedPan;
    }

    /**
     * @param string $cardTruncatedPan
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_PaymentMethod
     */
    public function setCardTruncatedPan($cardTruncatedPan)
    {
        $this->cardTruncatedPan = $cardTruncatedPan;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardExpireDate()
    {
        return $this->cardExpireDate;
    }

    /**
     * @param string $cardExpireDate
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_PaymentMethod
     */
    public function setCardExpireDate($cardExpireDate)
    {
        $this->cardExpireDate = $cardExpireDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return ucfirst(Payone_Core_Model_System_Config_PaymentMethodType::MASTERPASS);
    }
}