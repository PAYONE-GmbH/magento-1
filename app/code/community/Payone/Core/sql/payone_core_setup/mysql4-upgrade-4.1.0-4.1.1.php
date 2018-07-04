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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

$tablePaymentMethod = $this->getTable('payone_core/config_payment_method');
$tableTransaction = $this->getTable('payone_core/transaction');
$tableProtocolTxStatus = $this->getTable('payone_core/protocol_transactionStatus');
$tableOrderPayment = $this->getTable('sales/order_payment');
$tableOrder = $this->getTable('sales/order');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-4.1.0-4.1.1.sql');

    $installSqlConfig = [
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
        '{{payone_transaction}}' => $tableTransaction,
        '{{payone_protocol_transactionstatus}}' => $tableProtocolTxStatus,
        '{{sales_flat_order_payment}}' => $tableOrderPayment,
        '{{sales_flat_order}}' => $tableOrder,
    ];

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    $connection = $installer->getConnection();

    // Alter table payone_config_payment_method
    $connection->addColumn($tablePaymentMethod, 'request_type_amazon', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH'   => 50,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'request_type_amazon',
    ]);
    $connection->addColumn($tablePaymentMethod, 'amz_client_id', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH'   => 255,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'amz_client_id',
    ]);
    $connection->addColumn($tablePaymentMethod, 'amz_seller_id', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH'   => 255,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'amz_seller_id',
    ]);
    $connection->addColumn($tablePaymentMethod, 'amz_button_type', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'LENGTH'   => 11,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'amz_button_type',
    ]);
    $connection->addColumn($tablePaymentMethod, 'amz_button_color', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'LENGTH'   => 11,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'amz_button_color',
    ]);
    $connection->addColumn($tablePaymentMethod, 'amz_button_lang', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'LENGTH'   => 11,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'amz_button_lang',
    ]);
    $connection->addColumn($tablePaymentMethod, 'amz_sync_mode', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'LENGTH'   => 11,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'amz_sync_mode',
    ]);

    // Alter table payone_transaction
    $connection->addColumn($tableTransaction, 'transaction_status', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH'   => 64,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'Transaction status',
    ]);
    $connection->addColumn($tableTransaction, 'reasoncode', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH'   => 64,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'Reasoncode',
    ]);

    // Alter table payone_protocol_transactionstatus
    $connection->addColumn($tableProtocolTxStatus, 'transaction_status', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH'   => 64,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'Transaction status',
    ]);
    $connection->addColumn($tableProtocolTxStatus, 'reasoncode', [
        'TYPE'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH'   => 64,
        'NULLABLE' => true,
        'DEFAULT'  => null,
        'COMMENT'  => 'Reasoncode',
    ]);

    // Alter table sales_flat_order_payment
    $connection->addColumn($tableOrderPayment, 'payone_amz_order_reference', [
        'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'LENGTH' => 255,
        'NULLABLE' => true,
        'DEFAULT' => null,
        'COMMENT' => 'Amazon Order Reference',
    ]);

    // Alter table sales_flat_order
    $connection->addColumn($tableOrder, 'payone_prevent_confirmation', [
        'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'LENGTH' => 1,
        'NULLABLE' => true,
        'DEFAULT' => null,
        'COMMENT' => 'Flag to prevent confirmation mail',
    ]);
}

$installer->endSetup();
