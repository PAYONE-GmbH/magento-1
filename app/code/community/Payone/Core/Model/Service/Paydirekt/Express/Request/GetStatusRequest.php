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
class Payone_Core_Model_Service_Paydirekt_Express_Request_GetStatusRequest
    implements Payone_Core_Model_Service_Paydirekt_Express_RequestInterface
{
    /** @var Mage_Sales_Model_Quote */
    protected $quote;
    /** @var string */
    protected $workorderId;

    /**
     * @param string $quoteId
     * @param string $workorderId
     */
    public function __construct($quoteId, $workorderId = null)
    {
        if (!empty($quoteId)) {
            $this->quote = Mage::getModel('sales/quote')
                ->load($quoteId);
        } else {
            throw new InvalidArgumentException('Quote instance is required.');
        }

        $this->workorderId = $workorderId;
    }
    
    /**
     * @return string
     */
    public function getType()
    {
        return Payone_Core_Model_Service_Paydirekt_Express_RequestInterface::INIT_CHECKOUT_REQUEST_TYPE;
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @param string $workorderId
     */
    public function setWorkorderId($workorderId)
    {
        $this->workorderId = $workorderId;
    }

    /**
     * @return string
     */
    public function getWorkorderId()
    {
        return $this->workorderId;
    }
}
