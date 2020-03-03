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
 * @copyright       Copyright (c) 2020 <magento@payone.com> - www.payone.com
 * @author          PAYONE GmbH <magento@payone.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.payone.com
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

$tableTransactionStatus = $this->getTable('payone_core/protocol_transactionStatus');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-4.5.0-4.5.1.sql');

    $installSqlConfig = array(
        '{{payone_protocol_transactionstatus}}' => $tableTransactionStatus,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $connection = $installer->getConnection();

    $connection->addColumn(
        $tableTransactionStatus, 'process_retry_count',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'NULLABLE' => false,
            'COMMENT' => 'Count of processing retries',
            'DEFAULT' => 0)
    );

    $connection->addColumn(
        $tableTransactionStatus, 'processing_error_stacktrace',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'NULLABLE' => true,
            'COMMENT' => 'Detailed error stacktrace')
    );
}

$installer->endSetup();
