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
class Payone_Core_Model_Service_Paydirekt_Express_Request_PlaceOrderRequest
    implements Payone_Core_Model_Service_Paydirekt_Express_RequestInterface
{
    /** @var string */
    protected $quoteId;
    /** @var array */
    protected $agreements = array();

    /**
     * @return string
     */
    public function getType()
    {
        return Payone_Core_Model_Service_Paydirekt_Express_RequestInterface::PLACE_ORDER_REQUEST_TYPE;
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
     * @return array
     */
    public function getAgreements()
    {
        return $this->agreements;
    }

    /**
     * @param string $agreement
     */
    public function addAgreement($agreement)
    {
        array_push($this->agreements, $agreement);
    }

    /**
     * @param array $agreements
     */
    public function setAgreements($agreements)
    {
        $this->agreements = $agreements;
    }
}