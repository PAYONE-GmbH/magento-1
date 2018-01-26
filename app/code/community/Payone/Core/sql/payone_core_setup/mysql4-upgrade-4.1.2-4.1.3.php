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
$tablePaymentMethod = $this->getTable('payone_core/config_payment_method');
$tableLogos = $this->getTable('payone_core/config_logos');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-4.1.0-4.1.1.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    $connection = $installer->getConnection();

    /** Alter table 'payone_config_payment_method' */
    // Update table payone_config_payment_method
    $connection->addColumn(
        $tablePaymentMethod, 'cc_type_auto_recognition',
        'INT(1) COMMENT \'Auto recognize credit card type\' AFTER `customer_form_data_save`'
    );

    /** Create table 'payone_config_logos' */
    $table = $connection->newTable($tableLogos);

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
        'label', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn(
        'size', Varien_Db_Ddl_Table::TYPE_VARCHAR, 3,
        array(
            'nullable' => false,
            'default' => 'm')
    );
    $table->addColumn(
        'type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn(
        'path', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255,
        array(
            'nullable' => true,
            'default' => NULL)
    );
    $table->addColumn(
        'enabled', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 1,
        array(
            'nullable' => false,
            'default' => false)
    );

    //Set Engine to MyISAM
    $table->setOption('type', 'MyISAM');

    // Create table 'payone_protocol_api'
    $connection->createTable($table);
}

$installer->endSetup();