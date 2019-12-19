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
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

/** magento tables */
$tableOrder = $this->getTable('sales/order');

/** payone tables */
$tablePayoneTransaction = $this->getTable('payone_core/transaction');
$tablePayoneTransactionStatus = $this->getTable('payone_core/protocol_transactionStatus');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-4.5.0-4.5.1.sql');

    $installSqlConfig = [
        '{{sales_flat_order}}' => $tableOrder,
        '{{payone_transaction}}' => $tablePayoneTransaction,
        '{{payone_protocol_transactionstatus}}' => $tablePayoneTransactionStatus,
    ];

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    $connection = $installer->getConnection();

    // Alter table sales_flat_order
    $connection->addIndex($tableOrder, 'IDX_SALES_FLAT_ORDER_PO_CANCEL_SUBSTITUTE_INCREMENT_ID', 'payone_cancel_substitute_increment_id');
    // Alter table payone_transaction
    $connection->addIndex($tablePayoneTransaction, 'IDX_PAYONE_TRANSACTION_TXID', 'txid');
    // Alter table payone_protocol_transactionstatus
    $connection->addIndex($tablePayoneTransactionStatus, 'IDX_PAYONE_PROTOCOL_TRANSACTIONSTATUS_TXID', 'txid');
}

$installer->endSetup();
