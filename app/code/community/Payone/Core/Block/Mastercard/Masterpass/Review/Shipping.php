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

class Payone_Core_Block_Mastercard_Masterpass_Review_Shipping extends Mage_Core_Block_Template
{
    /** @var int */
    protected $quoteId;
    /** @var string */
    protected $name = "";
    /** @var string */
    protected $phone = "";
    /** @var string */
    protected $street = "";
    /** @var string */
    protected $zip = "";
    /** @var string */
    protected $city = "";
    /** @var string */
    protected $state = "";
    /** @var string */
    protected $country = "";

    public function init()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($this->quoteId);
        if (!$quote) {
            throw new Payone_Core_Exception_OrderNotFound('Quote with ID ' . $this->quoteId . ' was not found.');
        }

        $this->setName($quote->getShippingAddress()->getName())
            ->setPhone($quote->getShippingAddress()->getPrefix() . ' ' . $quote->getShippingAddress()->getTelephone())
            ->setStreet($quote->getShippingAddress()->getStreetFull())
            ->setZip($quote->getShippingAddress()->getPostcode())
            ->setCity($quote->getShippingAddress()->getCity())
            ->setState($quote->getShippingAddress()->getRegion())
            ->setCountry($quote->getShippingAddress()->getCountry());
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
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddressAddition()
    {
        return $this->addressAddition;
    }

    /**
     * @param string $addressAddition
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setAddressAddition($addressAddition)
    {
        $this->addressAddition = $addressAddition;

        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Payone_Core_Block_Mastercard_Masterpass_Review_Shipping
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
}