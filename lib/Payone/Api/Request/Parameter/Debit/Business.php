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
class Payone_Api_Request_Parameter_Debit_Business
    extends Payone_Api_Request_Parameter_Debit_Abstract
{
    /**
     * @var string
     */
    protected $settleaccount = NULL;
    /**
     * @var string
     */
    protected $transactiontype = NULL;
    /**
     * @var string
     */
    protected $booking_date = NULL;
    /**
     * @var string
     */
    protected $document_date = NULL;

    /**
     * @param string $booking_date
     */
    public function setBookingDate($booking_date)
    {
        $this->booking_date = $booking_date;
    }

    /**
     * @return string
     */
    public function getBookingDate()
    {
        return $this->booking_date;
    }

    /**
     * @param string $document_date
     */
    public function setDocumentDate($document_date)
    {
        $this->document_date = $document_date;
    }

    /**
     * @return string
     */
    public function getDocumentDate()
    {
        return $this->document_date;
    }

    /**
     * @param string $settleaccount
     */
    public function setSettleaccount($settleaccount)
    {
        $this->settleaccount = $settleaccount;
    }

    /**
     * @return string
     */
    public function getSettleaccount()
    {
        return $this->settleaccount;
    }

    /**
     * @param string $transactiontype
     */
    public function setTransactiontype($transactiontype)
    {
        $this->transactiontype = $transactiontype;
    }

    /**
     * @return string
     */
    public function getTransactiontype()
    {
        return $this->transactiontype;
    }
}
