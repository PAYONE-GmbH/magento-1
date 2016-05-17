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
class Payone_TransactionStatus_Enum_Txaction
{
    const APPOINTED = 'appointed';
    const CAPTURE = 'capture';
    const PAID = 'paid';
    const UNDERPAID = 'underpaid';
    const CANCELATION = 'cancelation';
    const REFUND = 'refund';
    const DEBIT = 'debit';
    const REMINDER = 'reminder';
    const VAUTHORIZATION = 'vauthorization';
    const VSETTLEMENT = 'vsettlement';
    const TRANSFER= 'transfer';
    const INVOICE= 'invoice';
}
