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
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
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
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.1.6-3.1.7.sql');

    $installSqlConfig = array(
        '{{sales_flat_order_payment}}' => $tableOrderPayment,
        '{{sales_flat_quote_payment}}' => $tableQuotePayment,
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $connection = $installer->getConnection();

    // payone_config_payment_method table:

    $connection->addColumn(
        $tablePaymentMethod, 'klarna_config',
        // using string definition as AFTER is not supported via array
        'TEXT COMMENT \'Klarna Config\' AFTER `types`'
    );

    // sales_flat_quote_payment table:

    $connection->addColumn(
        $tableQuotePayment, 'payone_customer_dob',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Date Of Birth'
        )
    );

    $connection->addColumn(
        $tableQuotePayment, 'payone_customer_gender',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Gender'
        )
    );

    $connection->addColumn(
        $tableQuotePayment, 'payone_customer_personalid',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Personalid'
        )
    );

    $connection->addColumn(
        $tableQuotePayment, 'payone_billing_addressaddition',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Billing Address Addition'
        )
    );

    $connection->addColumn(
        $tableQuotePayment, 'payone_shipping_addressaddition',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Shipping Address Addition'
        )
    );

    $connection->addColumn(
        $tableQuotePayment, 'payone_customer_telephone',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Telephone'
        )
    );

    // sales_flat_order_payment table:

    $connection->addColumn(
        $tableOrderPayment, 'payone_customer_dob',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Date Of Birth'
        )
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_customer_gender',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Gender'
        )
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_customer_personalid',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Personalid'
        )
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_billing_addressaddition',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Billing Address Addition'
        )
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_shipping_addressaddition',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Shipping Address Addition'
        )
    );

    $connection->addColumn(
        $tableOrderPayment, 'payone_customer_telephone',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 255,
            'NULLABLE' => true,
            'COMMENT' => 'Customer Telephone'
        )
    );
}

$installer->endSetup();
 