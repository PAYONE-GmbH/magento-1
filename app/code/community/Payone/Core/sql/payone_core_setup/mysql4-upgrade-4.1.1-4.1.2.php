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
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();
$tablePaymentBan = $this->getTable('payone_core/paymentBan');
$tableOrderPayment = $this->getTable('sales/order_payment');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-4.1.0-4.1.1.sql');

    $installSqlConfig = array(
        '{{payone_payment_ban}}' => $tablePaymentBan,
        '{{sales_flat_order_payment}}' => $tableOrderPayment,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $connection = $installer->getConnection();

    $connection->addColumn(
        $tableOrderPayment, 'payone_vat_id',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 64,
            'NULLABLE' => false,
            'COMMENT' => 'VAT ID number',
            'DEFAULT' => '')
    );

    $table = $connection->newTable($tablePaymentBan);

    // Add Columns
    $table->addColumn(
        'id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
            'auto_increment' => true)
    );
    $table->addColumn(
        'customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn(
        'payment_method', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn(
        'from_date', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn(
        'to_date', Varien_Db_Ddl_Table::TYPE_DATETIME, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addIndex(
        'UNQ_PAYONE_CUSTOMER_CUSTOMER_ID_PAYMENT_METHOD',
        array(
            'customer_id',
            'payment_method',
        ),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    );

    //Set Engine to MyISAM
    $table->setOption('type', 'MyISAM');

    $connection->createTable($table);
}

$installer->endSetup();
 
