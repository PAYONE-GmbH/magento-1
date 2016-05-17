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
 * @copyright       Copyright (c) 2013 <kontakt@fatchip.de> - www.fatchip.de
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();
$tableTransaction = $this->getTable('payone_core/transaction');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.3.5-3.3.6.sql');

    $installSqlConfig = array(
        '{{payone_transaction}}' => $tableTransaction,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    $connection = $installer->getConnection();

    // Update table payone_protocol_transactionstatus
    $connection->addColumn($tableTransaction, 'frontend_api_call',
        array(
            'TYPE' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'NULLABLE' => false,
            'COMMENT' => 'Flag to determine if this was a Frontend API call, where the txid is delivered later',
            'DEFAULT' => 0)
    );
}

$installer->endSetup();