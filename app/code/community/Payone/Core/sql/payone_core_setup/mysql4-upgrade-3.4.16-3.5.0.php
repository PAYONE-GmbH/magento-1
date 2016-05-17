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
$tableRatePayConfig = $this->getTable('payone_core/ratepay_config');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.4.16-3.5.0.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
        '{{sales_flat_order_payment}}' => $tableOrderPayment,
        '{{sales_flat_quote_payment}}' => $tableQuotePayment,
        '{{payone_ratepay_config}}' => $tableRatePayConfig,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $connection = $installer->getConnection();
    $connection->addColumn($tablePaymentMethod, 'ratepay_config',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'NULLABLE' => true,
            'COMMENT' => 'RatePay Config'
        )
    );
    
    $connection->addColumn($tableOrderPayment, 'payone_ratepay_shop_id',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 32,
            'NULLABLE' => true,
            'COMMENT' => 'RatePay ShopId'
        )
    );
    $connection->addColumn($tableQuotePayment, 'payone_ratepay_shop_id',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'LENGTH' => 32,
            'NULLABLE' => true,
            'COMMENT' => 'RatePay ShopId'
        )
    );

    $table = $connection->newTable($tableRatePayConfig);
    $table->addColumn('shop_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => false, 'primary' => true)
    );
    $table->addColumn('merchant_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('merchant_status', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('shop_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('currency', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('activation_status_elv', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('activation_status_installment', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('activation_status_invoice', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('activation_status_prepayment', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('amount_min_longrun', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('b2b_pq_full', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('b2b_pq_light', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('b2b_elv', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('b2b_installment', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('b2b_invoice', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('b2b_prepayment', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('country_code_billing', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('country_code_delivery', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('delivery_address_pq_full', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('delivery_address_pq_light', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('delivery_address_elv', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('delivery_address_installment', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('delivery_address_invoice', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('delivery_address_prepayment', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('device_fingerprint_snippet_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('eligibility_device_fingerprint', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('eligibility_ratepay_elv', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('eligibility_ratepay_installment', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('eligibility_ratepay_invoice', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('eligibility_ratepay_pq_full', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('eligibility_ratepay_pq_light', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('eligibility_ratepay_prepayment', Varien_Db_Ddl_Table::TYPE_INTEGER, 1,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('interest_rate_merchant_towards_bank', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('interestrate_default', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('interestrate_max', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('interestrate_min', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('min_difference_dueday', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('month_allowed', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('month_longrun', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('month_number_max', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('month_number_min', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('payment_amount', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('payment_firstday', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('payment_lastrate', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('rate_min_longrun', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('rate_min_normal', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('service_charge', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_elv_max', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_elv_min', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_installment_max', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_installment_min', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_invoice_max', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_invoice_min', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_prepayment_max', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('tx_limit_prepayment_min', Varien_Db_Ddl_Table::TYPE_FLOAT, NULL,
        array('nullable' => true, 'default' => NULL)
    );
    $table->addColumn('valid_payment_firstdays', Varien_Db_Ddl_Table::TYPE_INTEGER, 2,
        array('nullable' => true, 'default' => NULL)
    );

    $connection->createTable($table);
    
}
$installer->endSetup();
 
