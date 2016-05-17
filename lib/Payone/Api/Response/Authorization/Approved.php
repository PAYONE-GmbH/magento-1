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
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Response_Authorization_Approved
    extends Payone_Api_Response_Authorization_Abstract
{
    /**
     * @var string
     */
    protected $creditor_identifier = NULL;
    /**
     * @var int
     */
    protected $clearing_date = NULL;
    /**
     * @var int
     */
    protected $clearing_amount = NULL;

    /**
     * @param string $creditorIdentifier
     */
    public function setCreditorIdentifier($creditorIdentifier)
    {
        $this->creditor_identifier = $creditorIdentifier;
    }

    /**
     * @return string
     */
    public function getCreditorIdentifier()
    {
        return $this->creditor_identifier;
    }

    /**
     * @param int $clearingDate
     */
    public function setClearingDate($clearingDate)
    {
        $this->clearing_date = $clearingDate;
    }

    /**
     * @return int
     */
    public function getClearingDate()
    {
        return $this->clearing_date;
    }

    /**
     * @param int $clearingAmount
     */
    public function setClearingAmount($clearingAmount)
    {
        $this->clearing_amount = $clearingAmount;
    }

    /**
     * @return int
     */
    public function getClearingAmount()
    {
        return $this->clearing_amount;
    }
}
