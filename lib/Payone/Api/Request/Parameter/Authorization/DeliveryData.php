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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Request_Parameter_Authorization_DeliveryData
    extends Payone_Api_Request_Parameter_Authorization_Abstract
{
    /**
     * @var string
     */
    protected $shipping_firstname = NULL;
    /**
     * @var string
     */
    protected $shipping_lastname = NULL;
    /**
     * @var string
     */
    protected $shipping_company = NULL;
    /**
     * @var string
     */
    protected $shipping_street = NULL;
    /**
     * @var string
     */
    protected $shipping_zip = NULL;
    /**
     * @var string
     */
    protected $shipping_city = NULL;
    /**
     * ISO-3166 Subdivisions
     * only necessary for country US or CA
     *
     * @var string
     */
    protected $shipping_state = NULL;
    /**
     * Country (ISO-3166)
     *
     * @var string
     */
    protected $shipping_country = NULL;
    /**
     * @var string
     */
    protected $shipping_addressaddition = NULL;

    /**
     * @param string $shipping_addressaddition
     */
    public function setShippingAddressaddition($shipping_addressaddition)
    {
        $this->shipping_addressaddition = $shipping_addressaddition;
    }

    /**
     * @return string
     */
    public function getShippingAddressaddition()
    {
        return $this->shipping_addressaddition;
    }

    /**
     * @param string $shipping_city
     */
    public function setShippingCity($shipping_city)
    {
        $this->shipping_city = $shipping_city;
    }

    /**
     * @return string
     */
    public function getShippingCity()
    {
        return $this->shipping_city;
    }

    /**
     * @param string $shipping_company
     */
    public function setShippingCompany($shipping_company)
    {
        $this->shipping_company = $shipping_company;
    }

    /**
     * @return string
     */
    public function getShippingCompany()
    {
        return $this->shipping_company;
    }

    /**
     * @param string $shipping_country
     */
    public function setShippingCountry($shipping_country)
    {
        $this->shipping_country = $shipping_country;
    }

    /**
     * @return string
     */
    public function getShippingCountry()
    {
        return $this->shipping_country;
    }

    /**
     * @param string $shipping_firstname
     */
    public function setShippingFirstname($shipping_firstname)
    {
        $this->shipping_firstname = $shipping_firstname;
    }

    /**
     * @return string
     */
    public function getShippingFirstname()
    {
        return $this->shipping_firstname;
    }

    /**
     * @param string $shipping_lastname
     */
    public function setShippingLastname($shipping_lastname)
    {
        $this->shipping_lastname = $shipping_lastname;
    }

    /**
     * @return string
     */
    public function getShippingLastname()
    {
        return $this->shipping_lastname;
    }

    /**
     * @param string $shipping_state
     */
    public function setShippingState($shipping_state)
    {
        $this->shipping_state = $shipping_state;
    }

    /**
     * @return string
     */
    public function getShippingState()
    {
        return $this->shipping_state;
    }

    /**
     * @param string $shipping_street
     */
    public function setShippingStreet($shipping_street)
    {
        $this->shipping_street = $shipping_street;
    }

    /**
     * @return string
     */
    public function getShippingStreet()
    {
        return $this->shipping_street;
    }

    /**
     * @param string $shipping_zip
     */
    public function setShippingZip($shipping_zip)
    {
        $this->shipping_zip = $shipping_zip;
    }

    /**
     * @return string
     */
    public function getShippingZip()
    {
        return $this->shipping_zip;
    }
}
