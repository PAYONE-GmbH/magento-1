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
$tableSalesOrderPayment = $this->getTable('sales/order_payment');
$tableSalesQuotePayment = $this->getTable('sales/quote_payment');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.5.6-3.5.7.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
        '{{sales_flat_order_payment}}' => $tableSalesOrderPayment,
        '{{sales_flat_quote_payment}}' => $tableSalesQuotePayment,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    $connection = $installer->getConnection();
    $connection->addColumn($tablePaymentMethod, 'sofortueberweisung_show_iban',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'NULLABLE' => true,
            'DEFAULT' => NULL,
            'COMMENT' => 'sofortueberweisung_show_iban')
    );
    $connection->addColumn($tablePaymentMethod, 'sepa_request_bic',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'NULLABLE' => true,
            'DEFAULT' => NULL,
            'COMMENT' => 'sepa_request_bic')
    );
    
    // Update table sales_flat_order_payment
    $connection->addColumn($tableSalesOrderPayment, 'payone_payolution_bic',
        'VARCHAR(11) COMMENT \'PAYOLUTION BIC\' AFTER `payone_sepa_bic`'
    );

    $connection->addColumn($tableSalesOrderPayment, 'payone_payolution_iban',
        'VARCHAR(34) COMMENT \'PAYOLUTION IBAN\' AFTER `payone_sepa_bic`'
    );

    // Update table sales_flat_quote_payment
    $connection->addColumn($tableSalesQuotePayment, 'payone_payolution_bic',
        'VARCHAR(11) COMMENT \'PAYOLUTION BIC\' AFTER `payone_sepa_bic`'
    );

    $connection->addColumn($tableSalesQuotePayment, 'payone_payolution_iban',
        'VARCHAR(34) COMMENT \'PAYOLUTION IBAN\' AFTER `payone_sepa_bic`'
    );

}
$installer->endSetup();
 

