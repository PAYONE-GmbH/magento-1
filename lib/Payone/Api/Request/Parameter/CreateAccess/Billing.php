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
class Payone_Api_Request_Parameter_CreateAccess_Billing
    extends Payone_Api_Request_Parameter_Abstract
{

    /**
     * ID or Name of booking account. (Permitted symbols: 0-9, a-z, A-Z, .,-,_,/)
     *
     * @var string
     */
    protected $vaccountname = NULL;

    /**
     * Length of payroll cycle
     *
     * @var int
     */
    protected $settle_period_length = NULL;

    /**
     * Time unit of payroll cycle
     *
     * @var string
     */
    protected $settle_period_unit = NULL;

    /**
     * Date of next billing as unixtimestamp
     *
     * @var int
     */
    protected $settletime = NULL;

    /**
     * @var string
     */
    protected $payout_open_balance = NULL;

    /**
     * @param string $payout_open_balance
     */
    public function setPayoutOpenBalance($payout_open_balance)
    {
        $this->payout_open_balance = $payout_open_balance;
    }

    /**
     * @return string
     */
    public function getPayoutOpenBalance()
    {
        return $this->payout_open_balance;
    }

    /**
     * @param int $settle_period_length
     */
    public function setSettlePeriodLength($settle_period_length)
    {
        $this->settle_period_length = $settle_period_length;
    }

    /**
     * @return int
     */
    public function getSettlePeriodLength()
    {
        return $this->settle_period_length;
    }

    /**
     * @param string $settle_period_unit
     */
    public function setSettlePeriodUnit($settle_period_unit)
    {
        $this->settle_period_unit = $settle_period_unit;
    }

    /**
     * @return string
     */
    public function getSettlePeriodUnit()
    {
        return $this->settle_period_unit;
    }

    /**
     * @param int $settletime
     */
    public function setSettletime($settletime)
    {
        $this->settletime = $settletime;
    }

    /**
     * @return int
     */
    public function getSettletime()
    {
        return $this->settletime;
    }

    /**
     * @param string $vaccountname
     */
    public function setVaccountname($vaccountname)
    {
        $this->vaccountname = $vaccountname;
    }

    /**
     * @return string
     */
    public function getVaccountname()
    {
        return $this->vaccountname;
    }


}
