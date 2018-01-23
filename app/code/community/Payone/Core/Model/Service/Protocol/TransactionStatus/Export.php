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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Service_Protocol_TransactionStatus_Export
    extends Payone_Core_Model_Service_Export_Collection
{
    protected $columns = array(
        'store_id',
        'order_id',
        'txid',
        'txtime',
        'reference',
        'key',
        'txaction',
        'mode',
        'mid',
        'aid',
        'portalid',
        'clearingtype',
        'sequencenumber',
        'balance',
        'receivable',
        'transaction_status',
        'failedcause',
        'reasoncode',
        'currency',
        'userid',
        'customerid',
        'param',
        'productid',
        'accessid',
        'reminderlevel',
        'invoiceid',
        'invoice_grossamount',
        'invoice_date',
        'invoice_deliverydate',
        'invoice_deliveryenddate',
        'vaid',
        'vreference',
        'vxid',
        'created_at',
        'updated_at',
        'processing_status',
        'processed_at'
    );
}
