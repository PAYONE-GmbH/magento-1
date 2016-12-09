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
 * @package         Payone_TransactionStatus
 * @subpackage      Enum
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_TransactionStatus
 * @subpackage      Enum
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_TransactionStatus_Enum_Failedcause
{
    // soc Insufficient funds
    const INSUFFICIENT_FUNDS = 'soc';
    // cka Account expired
    const ACCOUNT_EXPIRED = 'cka';
    // uan Account no. / name not idential, incorrect or savings account
    const UNKNOWN_ACCOUNT_NAME = 'uan';
    // ndd No direct debit
    const NO_DIRECT_DEBIT = 'ndd';
    // rcl Recall
    const RECALL = 'rcl';
    // obj Objection
    const OBJECTION = 'obj';
    // ret Return
    const RETURNS = 'ret';
    // nelv Debit cannot be collected
    const DEBIT_NOT_COLLECTABLE = 'nelv';
    // cb Credit card chargeback
    const CREDITCARD_CHARGEBACK = 'cb';
    // ncc Credit card cannot be collected
    const CREDITCARD_NOT_COLLECTABLE = 'ncc';
}
