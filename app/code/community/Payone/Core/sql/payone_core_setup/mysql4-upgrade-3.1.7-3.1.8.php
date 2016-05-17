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
$tableSalesOrderPayment = $this->getTable('sales/order_payment');
$tableSalesQuotePayment = $this->getTable('sales/quote_payment');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.1.7-3.1.8.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
        '{{sales_flat_order_payment}}' => $tableSalesOrderPayment,
        '{{sales_flat_quote_payment}}' => $tableSalesQuotePayment
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $connection = $installer->getConnection();

    // using string definition as AFTER is not supported via array:
    $connection->addColumn($tablePaymentMethod, 'sepa_country',
        'TEXT COMMENT \'SEPA Country\' AFTER `message_response_blocked`'
    );

    $connection->addColumn($tablePaymentMethod, 'sepa_de_show_bank_data',
        'INT(1) COMMENT \'SEPA Germany Show Bank Data\' AFTER `sepa_country`'
    );

    $connection->addColumn($tablePaymentMethod, 'sepa_mandate_enabled',
        'INT(1) COMMENT \'SEPA Mandate Enabled\' AFTER `sepa_de_show_bank_data`'
    );

    $connection->addColumn($tablePaymentMethod, 'sepa_mandate_download_enabled',
        'INT(1) COMMENT \'SEPA Mandate Download Enabled\' AFTER `sepa_mandate_enabled`'
    );

    // Update table sales_flat_order_payment
    $connection->addColumn($tableSalesOrderPayment, 'payone_sepa_bic',
        'VARCHAR(11) COMMENT \'SEPA BIC\' AFTER `payone_bank_group`'
    );

    $connection->addColumn($tableSalesOrderPayment, 'payone_sepa_iban',
        'VARCHAR(34) COMMENT \'SEPA IBAN\' AFTER `payone_bank_group`'
    );

    // Update table sales_flat_quote_payment
    $connection->addColumn($tableSalesQuotePayment, 'payone_sepa_bic',
        'VARCHAR(11) COMMENT \'SEPA BIC\' AFTER `payone_bank_group`'
    );

    $connection->addColumn($tableSalesQuotePayment, 'payone_sepa_iban',
        'VARCHAR(34) COMMENT \'SEPA IBAN\' AFTER `payone_bank_group`'
    );
}
$installer->endSetup();