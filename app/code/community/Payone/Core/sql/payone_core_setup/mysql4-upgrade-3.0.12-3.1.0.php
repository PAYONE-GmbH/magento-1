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

$installer = $this;
$installer->startSetup();

$tableOrderPayment = $this->getTable('sales/order_payment');
$tableQuotePayment = $this->getTable('sales/quote_payment');
$tableTransactionStatus = $this->getTable('payone_core/protocol_transactionStatus');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.0.12-3.1.0.sql');

    $installSqlConfig = array(
        '{{sales_flat_order_payment}}' => $tableOrderPayment,
        '{{sales_flat_quote_payment}}' => $tableQuotePayment,
        '{{payone_protocol_transactionstatus}}' => $tableTransactionStatus
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $connection = $installer->getConnection();

    $connection->addColumn(
        $tableQuotePayment, 'payone_financing_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Financing: Type',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableQuotePayment, 'payone_safe_invoice_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Safe Invoice: Type',
            'DEFAULT' => '')
    );


    $connection->addColumn(
        $tableOrderPayment, 'payone_financing_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Financing: Type',
            'DEFAULT' => '')
    );


    $connection->addColumn(
        $tableOrderPayment, 'payone_safe_invoice_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Safe Invoice: Type',
            'DEFAULT' => '')
    );
    
    
    $connection->addColumn(
        $tableOrderPayment, 'payone_clearing_legalnote',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 500,
            'NULLABLE' => false,
            'COMMENT' => 'Legal note | Hinweistext zur Forderungsabtretung',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_clearing_duedate',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 8,
            'NULLABLE' => false,
            'COMMENT' => 'Due date | Zahlungsziel | YYYYMMDD',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_clearing_reference',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Reason for payment | Verwendungszweck',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_clearing_instructionnote',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 200,
            'NULLABLE' => false,
            'COMMENT' => 'Help text for payment clearing | Hinweistext zur Zahlungsabwicklung',
            'DEFAULT' => '')
    );
    
    
    
    
    
    
    
    

    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankaccountholder',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Accountholder',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankcountry',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 2,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Country',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankaccount',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 14,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Account',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankcode',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'LENGTH' => 11,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Code',
            'DEFAULT' => 0)
    );
    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankiban',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank IBAN',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankbic',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 11,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank BIC',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankcity',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank City',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableTransactionStatus, 'clearing_bankname',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Recipient Bank Name',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableTransactionStatus, 'clearing_legalnote',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 500,
            'NULLABLE' => false,
            'COMMENT' => 'Legal note | Hinweistext zur Forderungsabtretung',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableTransactionStatus, 'clearing_duedate',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 8,
            'NULLABLE' => false,
            'COMMENT' => 'Due date | Zahlungsziel | YYYYMMDD',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableTransactionStatus, 'clearing_reference',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 50,
            'NULLABLE' => false,
            'COMMENT' => 'Reason for payment | Verwendungszweck',
            'DEFAULT' => '')
    );

    $connection->addColumn(
        $tableTransactionStatus, 'clearing_instructionnote',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 200,
            'NULLABLE' => false,
            'COMMENT' => 'Help text for payment clearing | Hinweistext zur Zahlungsabwicklung',
            'DEFAULT' => '')
    );
}

$installer->endSetup();