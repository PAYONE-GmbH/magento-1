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
 * @copyright       Copyright (c) 2013 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();
$tableCustomer = $this->getTable('payone_core/customer');
$tablePaymentMethod = $this->getTable('payone_core/config_payment_method');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.2.1-3.2.2.sql');

    $installSqlConfig = array(
        '{{payone_customer}}' => $tableCustomer,
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    /** Build table 'payone_customer' */
    $connection = $installer->getConnection();
    $table = $connection->newTable($tableCustomer);

    // Add Columns
    $table->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
            'identity' => true,
            'auto_increment' => true)
    );
    $table->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn('customer_data', Varien_Db_Ddl_Table::TYPE_TEXT, NULL,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addIndex(
        $installer->getIdxName(
            'payone_core/customer',
            array(
                'customer_id',
                'code',
            ),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array(
            'customer_id',
            'code',
        ),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    );

    //Set Engine to MyISAM
    $table->setOption('type', 'MyISAM');

    // Create table 'payone_protocol_api'
    $connection->createTable($table);

    // Update table payone_config_payment_method
    $connection->addColumn($tablePaymentMethod, 'customer_form_data_save',
        'INT(1) COMMENT \'Save payment data for logged in customer\' AFTER `sepa_mandate_download_enabled`'
    );
}
$installer->endSetup();