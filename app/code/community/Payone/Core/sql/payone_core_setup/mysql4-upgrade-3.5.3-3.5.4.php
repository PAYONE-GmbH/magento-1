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
 * @copyright       Copyright (c) 2016 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

$tablePaymentMethod = $this->getTable('payone_core/config_payment_method');
$tableOrderPayment = $this->getTable('sales/order_payment');
$tableQuotePayment = $this->getTable('sales/quote_payment');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.5.3-3.5.4.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
        '{{sales_flat_order_payment}}' => $tableOrderPayment,
        '{{sales_flat_quote_payment}}' => $tableQuotePayment,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    /* Changes to payone_config_payment_method */
    
    $connection = $installer->getConnection();
    $connection->addColumn(
        $tablePaymentMethod, 'company_name',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'DEFAULT' => NULL,
            'COMMENT' => 'company_name')
    ); 
    $connection->addColumn(
        $tablePaymentMethod, 'b2b_mode',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'NULLABLE' => true,
            'DEFAULT' => NULL,
            'COMMENT' => 'b2b_mode')
    ); 
    
    /* Changes to sales_flat_order_payment */
    
    $connection->addColumn(
        $tableOrderPayment, 'payone_payolution_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Payolution type',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableOrderPayment, 'payone_isb2b',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'NULLABLE' => false,
            'COMMENT' => 'Was B2B mode active?',
            'DEFAULT' => '0')
    );
    $connection->addColumn(
        $tableOrderPayment, 'payone_trade_registry_number',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 64,
            'NULLABLE' => false,
            'COMMENT' => 'trade registry number',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableOrderPayment, 'payone_workorder_id',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 64,
            'NULLABLE' => false,
            'COMMENT' => 'workorder id',
            'DEFAULT' => '')
    );

    /* Changes to sales_flat_quote_payment */
    
    $connection->addColumn(
        $tableQuotePayment, 'payone_payolution_type',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 3,
            'NULLABLE' => false,
            'COMMENT' => 'Payolution type',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableQuotePayment, 'payone_isb2b',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'NULLABLE' => false,
            'COMMENT' => 'Was B2B mode active?',
            'DEFAULT' => '0')
    );
    $connection->addColumn(
        $tableQuotePayment, 'payone_trade_registry_number',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 64,
            'NULLABLE' => false,
            'COMMENT' => 'trade registry number',
            'DEFAULT' => '')
    );
    $connection->addColumn(
        $tableQuotePayment, 'payone_workorder_id',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 64,
            'NULLABLE' => false,
            'COMMENT' => 'workorder id',
            'DEFAULT' => '')
    );
}

$installer->endSetup();
 
