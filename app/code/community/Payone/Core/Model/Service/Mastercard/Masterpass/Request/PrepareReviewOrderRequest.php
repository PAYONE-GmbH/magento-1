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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Model_Service_Mastercard_Masterpass_Request_PrepareReviewOrderRequest
    implements Payone_Core_Model_Service_Mastercard_Masterpass_RequestInterface
{
    /** @var array */
    protected $data = array();

    /** @var string */
    protected $quoteId;

    /**
     * @return string
     */
    public function getType()
    {
        return Payone_Core_Model_Service_Mastercard_Masterpass_RequestInterface::PREPARE_REVIEW_ORDER_REQUEST_TYPE;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->_getData('firstname');
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->_getData('lastname');
    }

    /**
     * @return string
     */
    public function getBirthdate()
    {
        return $this->_getData('birthday');
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_getData('email');
    }

    /**
     * @return string
     */
    public function getContactCountry()
    {
        return $this->_getData('contactcountry');
    }

    /**
     * @return string
     */
    public function getBillingStreet()
    {
        return $this->_getData('street');
    }

    /**
     * @return string
     */
    public function getBillingAddressAddition()
    {
        return $this->_getData('addressaddition');
    }

    /**
     * @return string
     */
    public function getBillingPostcode()
    {
        return $this->_getData('zip');
    }

    /**
     * @return string
     */
    public function getBillingCity()
    {
        return $this->_getData('city');
    }

    /**
     * @return string
     */
    public function getBillingState()
    {
        return $this->_getData('state');
    }

    /**
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->_getData('country');
    }

    /**
     * @return string
     */
    public function getShippingName()
    {
        return $this->_getData('shipping_firstname');
    }

    /**
     * @return string
     */
    public function getShippingPhone()
    {
        return $this->_getData('shipping_telephonenumber');
    }

    /**
     * @return string
     */
    public function getShippingStreet()
    {
        return $this->_getData('shipping_street');
    }

    /**
     * @return string
     */
    public function getShippingAddressAddition()
    {
        return $this->_getData('shipping_addressaddition');
    }

    /**
     * @return string
     */
    public function getShippingPostcode()
    {
        return $this->_getData('shipping_zip');
    }

    /**
     * @return string
     */
    public function getShippingCity()
    {
        return $this->_getData('shipping_city');
    }

    /**
     * @return string
     */
    public function getShippingState()
    {
        return $this->_getData('shipping_state');
    }

    /**
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->_getData('shipping_country');
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->_getData('cardtype');
    }

    /**
     * @return string
     */
    public function getCardNumber()
    {
        return $this->_getData('truncatedcardpan');
    }

    /**
     * @return string
     */
    public function getCardExpiry()
    {
        return $this->_getData('cardexpiredate');
    }

    /**
     * @return string
     */
    public function getQuoteId()
    {
        return $this->quoteId;
    }

    /**
     * @param string $quoteId
     */
    public function setQuoteId($quoteId)
    {
        $this->quoteId = $quoteId;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @return string
     */
    private function _getData($key)
    {
        if (!isset($this->data[$key])) {
            return '';
        }

        return $this->data[$key];
    }
}