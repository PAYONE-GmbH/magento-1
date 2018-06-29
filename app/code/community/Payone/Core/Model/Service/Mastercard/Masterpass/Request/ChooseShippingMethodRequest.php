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

class Payone_Core_Model_Service_Mastercard_Masterpass_Request_ChooseShippingMethodRequest
    implements Payone_Core_Model_Service_Mastercard_Masterpass_RequestInterface
{
    /** @var string  */
    protected $methodCode = '';

    /** @var string */
    protected $quoteId;

    /**
     * @return string
     */
    public function getType()
    {
        return Payone_Core_Model_Service_Mastercard_Masterpass_RequestInterface::CHOOSE_SHIPPING_METHOD_REQUEST_TYPE;
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
    public function getMethodCode()
    {
        return $this->methodCode;
    }

    /**
     * @param string $methodCode
     */
    public function setMethodCode($methodCode)
    {
        $this->methodCode = $methodCode;
    }
}