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
 * @package         Payone_Core
 * @subpackage      sql
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

/** payone tables  */
$tableTransaction = $this->getTable('payone_core/transaction');
$tableTransactionStatus = $this->getTable('payone_core/protocol_transactionStatus');
$tablePaymentMethod = $this->getTable('payone_core/config_payment_method');
$tableApiProtocol = $this->getTable('payone_core/protocol_api');

/** magento tables */
$tableOrder = $this->getTable('sales/order');
$tableOrderPayment = $this->getTable('sales/order_payment');
$tableOrderAddress = $this->getTable('sales/order_address');
$tableQuotePayment = $this->getTable('sales/quote_payment');
$tableQuoteAddress = $this->getTable('sales/quote_address');
$tableFlatOrderGrid = $this->getTable('sales/order_grid');
$tableInvoice = $this->getTable('sales/invoice');

$installer = $this;

$installer->startSetup();

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');

$magentoEdition = $helper->getMagentoEdition();
$magentoVersion = $helper->getMagentoVersion();

$useOldStyleInstaller = false;
switch($magentoEdition)
{
    case 'CE' :
        if(version_compare($magentoVersion, '1.6', '<'))
            $useOldStyleInstaller = true;
        break;
    case 'EE' : // Intentional fallthrough
    case 'PE' :
        if(version_compare($magentoVersion, '1.11', '<'))
            $useOldStyleInstaller = true;
        break;
}

if($useOldStyleInstaller) {
    // Use own String for type datetime, to be compatible to Magento 1.5
    $datetime = 'datetime';

    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'install-3.0.0.sql');

    $installSqlConfig = array(
        '{{payone_transaction}}' => $tableTransaction,
        '{{payone_protocol_transactionstatus}}' => $tableTransactionStatus,
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
        '{{payone_protocol_api}}' => $tableApiProtocol,
        '{{sales_flat_order}}' => $tableOrder,
        '{{sales_flat_order_payment}}' => $tableOrderPayment,
        '{{sales_flat_quote_payment}}' => $tableQuotePayment,
        '{{sales_flat_quote_address}}' => $tableQuoteAddress,
        '{{sales_flat_order_grid}}' => $tableFlatOrderGrid,
        '{{sales_flat_invoice}}' => $tableInvoice
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $datetime = Varien_Db_Ddl_Table::TYPE_DATETIME;

    /** Build table 'payone_protocol_api' */
    $connection = $installer->getConnection();
    $table = $connection->newTable($tableApiProtocol);
    //<editor-fold desc="Add Columns to $tableApiProtocol">
    // Add Columns
    $table->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
            'auto_increment' => true)
    );
    $table->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('reference', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20,
        array(
            'nullable' => false)
    );
    $table->addColumn('request', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
        array(
            'nullable' => false)
    );
    $table->addColumn('response', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
        array(
            'nullable' => false)
    );
    $table->addColumn('mode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 16,
        array(
            'nullable' => false)
    );
    $table->addColumn('mid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('aid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('portalid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('raw_request', Varien_Db_Ddl_Table::TYPE_TEXT, NULL,
        array(
            'nullable' => false)
    );
    $table->addColumn('raw_response', Varien_Db_Ddl_Table::TYPE_TEXT, NULL,
        array(
            'nullable' => false)
    );
    $table->addColumn('stacktrace', Varien_Db_Ddl_Table::TYPE_TEXT, NULL,
        array(
            'nullable' => false)
    );
    $table->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => false,
            'default' => '0000-00-00 00:00:00')
    );
    //</editor-fold>

    //Set Engine to MyISAM
    $table->setOption('type', 'MyISAM');

    // Create table 'payone_protocol_api'
    $connection->createTable($table);


    /** Build table 'payone_transaction' */
    $table = $connection->newTable($tableTransaction);
    //<editor-fold desc="Add Columns $tableTransaction">
    // Add Columns
    $table->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
            'auto_increment' => true)
    );
    $table->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('txid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'nullable' => false,
            'default' => '0')
    );
    $table->addColumn('txtime', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0'),
        'Unix_Timestamp'
    );
    $table->addColumn('reference', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20,
        array(
            'nullable' => false)
    );
    $table->addColumn('last_txaction', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64,
        array(
            'nullable' => false)
    );
    $table->addColumn('last_sequencenumber', Varien_Db_Ddl_Table::TYPE_SMALLINT, NULL,
        array(
            'nullable' => false,
            'default' => '0')
    );
    $table->addColumn('clearingtype', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64,
        array(
            'nullable' => false)
    );
    $table->addColumn('mode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 16,
        array(
            'nullable' => false)
    );
    $table->addColumn('mid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('aid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('portalid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('productid', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('currency', Varien_Db_Ddl_Table::TYPE_VARCHAR, 3,
        array(
            'nullable' => false)
    );
    $table->addColumn('receivable', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array(
            'nullable' => false,
            'default' => '0')
    );
    $table->addColumn('balance', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array(
            'nullable' => false,
            'default' => '0')
    );
    $table->addColumn('customerid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('userid', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('reminderlevel', Varien_Db_Ddl_Table::TYPE_VARCHAR, 1,
        array(
            'nullable' => false)
    );
    $table->addColumn('failedcause', Varien_Db_Ddl_Table::TYPE_VARCHAR, 4,
        array(
            'nullable' => false)
    );
    $table->addColumn('accessid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => false)
    );
    $table->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => false,
            'default' => '0000-00-00 00:00:00')
    );
    $table->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => false,
            'default' => '0000-00-00 00:00:00')
    );
    //</editor-fold>

    // Create table 'payone_transaction'
    $connection->createTable($table);


    /** Build table 'payone_protocol_transactionstatus' */
    $table = $connection->newTable($tableTransactionStatus);
    //<editor-fold desc="Add Columns $tableTransactionStatus">
    // Add Columns
    $table->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
            'auto_increment' => true)
    );
    $table->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('txid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('txtime', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL),
        'Unix_Timestamp'
    );
    $table->addColumn('reference', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('key', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('txaction', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('mode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 16,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('mid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('aid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('portalid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('clearingtype', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('sequencenumber', Varien_Db_Ddl_Table::TYPE_SMALLINT, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('balance', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('receivable', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('failedcause', Varien_Db_Ddl_Table::TYPE_VARCHAR, 4,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('currency', Varien_Db_Ddl_Table::TYPE_VARCHAR, 3,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('userid', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('customerid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('param', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('productid', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL),
        'Parameter Contract'
    );
    $table->addColumn('accessid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL),
        'Parameter Contract'
    );
    $table->addColumn('reminderlevel', Varien_Db_Ddl_Table::TYPE_VARCHAR, 1,
        array(
            'nullable' => true,
            'default' => NULL),
        'Parameter Collect'
    );
    $table->addColumn('invoiceid', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20,
        array(
            'nullable' => true,
            'default' => NULL),
        'Parameter Invoicing'
    );
    $table->addColumn('invoice_grossamount', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array(
            'nullable' => true,
            'default' => NULL),
        'Parameter Invoicing'
    );
    $table->addColumn('invoice_date', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => NULL),
        'Parameter Invoicing'
    );
    $table->addColumn('invoice_deliverydate', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => NULL),
        'Parameter Invoicing'
    );
    $table->addColumn('invoice_deliveryenddate', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => NULL),
        'Parameter Invoicing'
    );
    $table->addColumn('vaid', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL),
        'Parameter Billing'
    );
    $table->addColumn('vreference', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20,
        array(
            'nullable' => true,
            'default' => NULL),
        'Parameter Billing'
    );
    $table->addColumn('vxid', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL),
        'Parameter Billing'
    );
    $table->addColumn('processing_status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 16,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('processed_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => '0000-00-00 00:00:00')
    );
    $table->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => '0000-00-00 00:00:00')
    );
    $table->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => '0000-00-00 00:00:00')
    );
    //</editor-fold>

    //Set Engine to MyISAM
    $table->setOption('type', 'MyISAM');

    // Create table 'payone_protocol_transactionstatus'
    $connection->createTable($table);


    /** Build table 'payone_config_payment_method' */
    $table = $connection->newTable($tablePaymentMethod);
    //<editor-fold desc="Add Columns $tablePaymentMethod">
    // Add Columns
    $table->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
            'auto_increment' => true)
    );
    $table->addColumn('scope', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50,
        array(
            'nullable' => false)
    );
    $table->addColumn('scope_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => 0)
    );
    $table->addColumn('code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50,
        array(
            'nullable' => false)
    );
    $table->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
        array(
            'nullable' => true,
            'default' => null)
    );
    $table->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('enabled', Varien_Db_Ddl_Table::TYPE_BOOLEAN, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('fee_config', Varien_Db_Ddl_Table::TYPE_TEXT, NULL,
        array(
            'nullable' => true)
    );
    $table->addColumn('mode', Varien_Db_Ddl_Table::TYPE_VARCHAR, 16,
        array(
            'nullable' => true)
    );
    $table->addColumn('use_global', Varien_Db_Ddl_Table::TYPE_BOOLEAN, NULL,
        array(
            'nullable' => true)
    );
    $table->addColumn('mid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true)
    );
    $table->addColumn('aid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true)
    );
    $table->addColumn('portalid', Varien_Db_Ddl_Table::TYPE_BIGINT, NULL,
        array(
            'unsigned' => true,
            'nullable' => true)
    );
    $table->addColumn('key', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array(
            'nullable' => true)
    );
    $table->addColumn('request_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50,
        array(
            'nullable' => true)
    );
    $table->addColumn('allowspecific', Varien_Db_Ddl_Table::TYPE_BOOLEAN, NULL,
        array(
            'default' => NULL)
    );
    $table->addColumn('specificcountry', Varien_Db_Ddl_Table::TYPE_TEXT, NULL,
        array()
    );
    $table->addColumn('invoice_transmit', Varien_Db_Ddl_Table::TYPE_BOOLEAN, NULL,
        array('default' => NULL
        )
    );
    $table->addColumn('types', Varien_Db_Ddl_Table::TYPE_TEXT, NULL,
        array()
    );
    $table->addColumn('check_cvc', Varien_Db_Ddl_Table::TYPE_BOOLEAN, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('check_bankaccount', Varien_Db_Ddl_Table::TYPE_BOOLEAN, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );

    $table->addColumn('bankaccountcheck_type', Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(
            'nullable' => true,
            'default' => '')
    );

    $table->addColumn('message_response_blocked',Varien_Db_Ddl_Table::TYPE_TEXT, 1024,
        array('nullable' => true,
            'default' => '')
        , 'Message for blocked bank accounts');


    $table->addColumn('min_order_total', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('max_order_total', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('parent_default_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('parent_websites_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('is_deleted', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'nullable' => false,
            'default' => '0'),
        'Parameter Invoicing'
    );
    $table->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => false,
            'default' => '0000-00-00 00:00:00')
    );
    $table->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => false,
            'default' => '0000-00-00 00:00:00')
    );
    //</editor-fold>

    // Set Engine to MyISAM
    $table->setOption('type', 'MyISAM');
    // Create table 'payone_config_payment_method'
    $connection->createTable($table);


    /** Alter table 'sales_flat_order' */
    $connection->addColumn($tableOrder, 'payone_transaction_status',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 16,
            'NULLABLE' => false,
            'COMMENT' => 'payone_transaction_status')
    );
    $connection->addColumn($tableOrder, 'payone_dunning_status',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 16,
            'NULLABLE' => false,
            'COMMENT' => 'payone_dunning_status')
    );
    $connection->addColumn($tableOrder, 'payone_payment_method_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Method Type that was used. Only filled for CreditCard and OnlineBankTransfer')
    );


    /** Alter table 'sales_flat_order_grid' */
    $connection->addColumn($tableFlatOrderGrid, 'payone_transaction_status',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 16,
            'NULLABLE' => false,
            'COMMENT' => 'payone_transaction_status')
    );
    $connection->addColumn($tableFlatOrderGrid, 'payone_dunning_status',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 16,
            'NULLABLE' => false,
            'COMMENT' => 'payone_dunning_status')
    );
    $connection->addColumn($tableFlatOrderGrid, 'payone_payment_method',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => false,
            'COMMENT' => 'payone_payment_method')
    );
    $connection->addColumn($tableFlatOrderGrid, 'payone_payment_method_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Method Type that was used. Only filled for CreditCard and OnlineBankTransfer')
    );

    $connection->addIndex($tableFlatOrderGrid, 'IDX_PAYONE_PAYMENT_METHOD', 'payone_payment_method');

    /** Alter table sales_flat_order_payment */
    $connection->addColumn($tableOrderPayment, 'payone_config_payment_method_id',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'LENGTH' => 11,
            'NULLABLE' => false,
            'COMMENT' => 'payone_config_payment_method_id',
            'DEFAULT' => 0)
    );
    $connection->addColumn($tableOrderPayment, 'payone_payment_method_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Method Type that was used. Only filled for CreditCard and OnlineBankTransfer')
    );
    $connection->addColumn($tableOrderPayment, 'payone_payment_method_name',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => false,
            'COMMENT' => 'Config-Name the Customer Provided when the order was placed')
    );


    $connection->addColumn($tableOrderPayment, 'payone_onlinebanktransfer_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Onlinebanktransfer: Type',
            'DEFAULT' => '')
    );


    $connection->addColumn($tableOrderPayment, 'payone_bank_country',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 2,
            'NULLABLE' => false,
            'COMMENT' => 'Bank Country Code',
            'DEFAULT' => '')
    );


    $connection->addColumn($tableOrderPayment, 'payone_account_number',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 14,
            'NULLABLE' => false,
            'COMMENT' => 'Account number',
            'DEFAULT' => 0)
    );


    $connection->addColumn($tableOrderPayment, 'payone_account_owner',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Account owner',
            'DEFAULT' => 0)
    );

    $connection->addColumn($tableOrderPayment, 'payone_bank_code',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'LENGTH' => 8,
            'NULLABLE' => false,
            'COMMENT' => 'Bank Code',
            'DEFAULT' => 0)
    );


    $connection->addColumn($tableOrderPayment, 'payone_bank_group',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 32,
            'NULLABLE' => false,
            'COMMENT' => 'Onlinebanktransfer: Bank Group',
            'DEFAULT' => '')
    );


    $connection->addColumn($tableOrderPayment, 'payone_pseudocardpan',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 19,
            'NULLABLE' => false,
            'COMMENT' => 'Pseudo Card PAN',
            'DEFAULT' => '')
    );

    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_accountholder',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Accountholder',
            'DEFAULT' => '')
    );

    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_country',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 2,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Country',
            'DEFAULT' => '')
    );
    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_account',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 14,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Account',
            'DEFAULT' => '')
    );
    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_code',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'LENGTH' => 11,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Code',
            'DEFAULT' => 0)
    );
    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_iban',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank IBAN',
            'DEFAULT' => '')
    );
    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_bic',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 11,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank BIC',
            'DEFAULT' => '')
    );
    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_city',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank City',
            'DEFAULT' => '')
    );
    $connection->addColumn($tableOrderPayment, 'payone_clearing_bank_name',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Name',
            'DEFAULT' => '')
    );

    /** Alter table sales_flat_quote_payment */
    $connection->addColumn($tableQuotePayment, 'payone_config_payment_method_id',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'LENGTH' => 11,
            'NULLABLE' => false,
            'COMMENT' => 'payone_config_payment_method_id',
            'DEFAULT' => 0)
    );

    $connection->addColumn($tableQuotePayment, 'payone_onlinebanktransfer_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Onlinebanktransfer: Type',
            'DEFAULT' => '')
    );

    $connection->addColumn($tableQuotePayment, 'payone_bank_country',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 2,
            'NULLABLE' => false,
            'COMMENT' => 'Bank Country Code',
            'DEFAULT' => '')
    );

    $connection->addColumn($tableQuotePayment, 'payone_account_number',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 14,
            'NULLABLE' => false,
            'COMMENT' => 'Account number',
            'DEFAULT' => 0)
    );

    $connection->addColumn($tableQuotePayment, 'payone_account_owner',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Account owner',
            'DEFAULT' => 0)
    );

    $connection->addColumn($tableQuotePayment, 'payone_bank_code',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'LENGTH' => 8,
            'NULLABLE' => false,
            'COMMENT' => 'Bank Code',
            'DEFAULT' => 0)
    );


    $connection->addColumn($tableQuotePayment, 'payone_bank_group',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 32,
            'NULLABLE' => false,
            'COMMENT' => 'Bank Group Type',
            'DEFAULT' => '')
    );


    $connection->addColumn($tableQuotePayment, 'payone_pseudocardpan',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 19,
            'NULLABLE' => false,
            'COMMENT' => 'Pseudo Card PAN',
            'DEFAULT' => '')
    );

    /** Alter table sales_flat_quote_address */
    $connection->addColumn($tableQuoteAddress, 'payone_addresscheck_score',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 1,
            'NULLABLE' => false,
            'COMMENT' => 'AddressCheck Person Status Score (G, Y, R)',
            'DEFAULT' => ''

        ));

    $connection->addColumn($tableQuoteAddress, 'payone_addresscheck_date',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'NULLABLE' => false,
            'DEFAULT' => '0000-00-00 00:00:00',
            'COMMENT' => 'Addresscheck Date'
        ));

    $connection->addColumn($tableQuoteAddress, 'payone_addresscheck_hash',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 32,
            'NULLABLE' => false,
            'DEFAULT' => '',
            'COMMENT' => 'Addresscheck Hash'

        ));

    $connection->addColumn($tableQuoteAddress, 'payone_protect_score',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 1,
            'NULLABLE' => false,
            'COMMENT' => 'Creditrating Score (G, Y, R)',
            'DEFAULT' => ''

        ));

    $connection->addColumn($tableQuoteAddress, 'payone_protect_date',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'NULLABLE' => false,
            'COMMENT' => 'Creditrating Date',
            'DEFAULT' => '0000-00-00 00:00:00'

        ));

    $connection->addColumn($tableQuoteAddress, 'payone_protect_hash',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 32,
            'NULLABLE' => false,
            'COMMENT' => 'Creditrating Score (G, Y, R)',
            'DEFAULT' => ''

        ));

    /** Alter table sales_flat_invoice */
    $connection->addColumn($tableInvoice, 'payone_sequencenumber',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'LENGTH' => 6,
            'NULLABLE' => true,
            'COMMENT' => 'Sequencenumber',
            'DEFAULT' => null
        )
    );
}

// Add attributes:
/* @var $installer Mage_Customer_Model_Entity_Setup */
$setup = new Mage_Customer_Model_Entity_Setup('core_setup');

$setup->addAttribute('customer_address', 'payone_addresscheck_score', array(
    'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'label' => 'Payone Address Check Score',
    'visible' => false,
    'required' => false));

$setup->addAttribute('customer_address', 'payone_addresscheck_date', array(
    'type' => $datetime,
    'label' => 'Addresscheck Date',
    'visible' => false,
    'required' => false));

$setup->addAttribute('customer_address', 'payone_addresscheck_hash', array(
    'type' => 'varchar',
    'label' => 'Addresscheck Hash',
    'visible' => false,
    'required' => false));

$setup->addAttribute('customer_address', 'payone_protect_score', array(
    'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'label' => 'Protect Score (G, Y, R)',
    'visible' => false,
    'required' => false));
$setup->addAttribute('customer_address', 'payone_protect_date', array(
    'type' => $datetime,
    'label' => 'Protect Date',
    'visible' => false,
    'required' => false));

$setup->addAttribute('customer_address', 'payone_protect_hash',
    array('type' => 'varchar',
        'label' => 'Addresscheck Date',
        'visible' => false,
        'required' => false));

$setup->addAttribute('customer', 'payone_user_id',
    array('type' => 'int',
        'visible' => false,
        'required' => false));

$installer->endSetup();