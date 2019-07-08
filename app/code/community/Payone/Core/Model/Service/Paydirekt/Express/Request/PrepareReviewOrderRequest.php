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
 * @subpackage      Service_Paydirekt_Express_Request
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Service_Paydirekt_Express_Request_PrepareReviewOrderRequest
    implements Payone_Core_Model_Service_Paydirekt_Express_RequestInterface
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
        return Payone_Core_Model_Service_Paydirekt_Express_RequestInterface::PREPARE_REVIEW_ORDER_REQUEST_TYPE;
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
     * @return string
     */
    public function getBuyerEmail()
    {
        return $this->_getData('buyer_email');
    }

    /**
     * @return string
     */
    public function getBillingFirstname()
    {
        return $this->_getData('billing_firstname');
    }

    /**
     * @return string
     */
    public function getBillingLastname()
    {
        return $this->_getData('billing_lastname');
    }

    /**
     * @return string
     */
    public function getBillingStreetname()
    {
        return $this->_getData('billing_streetname');
    }

    /**
     * @return string
     */
    public function getBillingStreetnumber()
    {
        return $this->_getData('billing_streetnumber');
    }

    /**
     * @return string
     */
    public function getBillingCity()
    {
        return $this->_getData('billing_city');
    }

    /**
     * @return string
     */
    public function getBillingZip()
    {
        return $this->_getData('billing_zip');
    }

    /**
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->_getData('billing_country');
    }

    /**
     * @return string
     */
    public function getBillingCompany()
    {
        return $this->_getData('billing_company');
    }

    /**
     * @return string
     */
    public function getBillingAdditionaladdressinformation()
    {
        return $this->_getData('billing_additionaladdressinformation');
    }

    /**
     * @return string
     */
    public function getBillingEmail()
    {
        return $this->_getData('billing_email');
    }

    /**
     * @return string
     */
    public function getShippingFirstname()
    {
        return $this->_getData('shipping_firstname');
    }

    /**
     * @return string
     */
    public function getShippingLastname()
    {
        return $this->_getData('shipping_lastname');
    }

    /**
     * @return string
     */
    public function getShippingStreetname()
    {
        return $this->_getData('shipping_streetname');
    }

    /**
     * @return string
     */
    public function getShippingStreetnumber()
    {
        return $this->_getData('shipping_streetnumber');
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
    public function getShippingZip()
    {
        return $this->_getData('shipping_zip');
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
    public function getShippingCompany()
    {
        return $this->_getData('shipping_company');
    }

    /**
     * @return string
     */
    public function getShippingAdditionaladdressinformation()
    {
        return $this->_getData('shipping_additionaladdressinformation');
    }

    /**
     * @return string
     */
    public function getShippingEmail()
    {
        return $this->_getData('shipping_email');
    }

    /**
     * @return string
     */
    public function getWorkorderid()
    {
        return $this->_getData('workorderid');
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
