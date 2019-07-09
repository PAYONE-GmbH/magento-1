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
 * @subpackage      Paydirekt_Express_Review
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Block_Paydirekt_Express_Review_Billing extends Mage_Core_Block_Template
{
    /** @var int */
    protected $quoteId;
    /** @var string */
    protected $firstname = "";
    /** @var string */
    protected $lastname = "";
    /** @var string */
    protected $email = "";
    /** @var string */
    protected $street = "";
    /** @var string */
    protected $zip = "";
    /** @var string */
    protected $state = "";
    /** @var string */
    protected $city = "";
    /** @var string */
    protected $country = "";

    public function init()
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = Mage::getModel('sales/quote')->load($this->quoteId);
        if (!$quote) {
            throw new Payone_Core_Exception_OrderNotFound('Quote with ID ' . $this->quoteId . ' was not found.');
        }

        $this->setFirstname($quote->getBillingAddress()->getFirstname())
            ->setLastname($quote->getBillingAddress()->getLastname())
            ->setEmail($quote->getBillingAddress()->getEmail())
            ->setStreet($quote->getBillingAddress()->getStreetFull())
            ->setZip($quote->getBillingAddress()->getPostcode())
            ->setCity($quote->getBillingAddress()->getCity())
            ->setState($quote->getBillingAddress()->getRegion())
            ->setCountry($quote->getBillingAddress()->getCountry());
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
     */
    public function setEmail($email)
    {
        $this->email = $email;

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
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
     */
    public function setStreet($street)
    {
        $this->street = $street;

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
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
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
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
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
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
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
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param int $quoteId
     * @return Payone_Core_Block_Paydirekt_Express_Review_Billing
     */
    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;

        return $this;
    }
}