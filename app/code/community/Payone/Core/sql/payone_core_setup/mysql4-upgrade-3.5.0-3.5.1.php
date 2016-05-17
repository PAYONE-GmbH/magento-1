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
$tableCustomer = $this->getTable('payone_core/customer');
$tableApiProtocol = $this->getTable('payone_core/protocol_api');
$tableTransactionStatus = $this->getTable('payone_core/protocol_transactionStatus');
$tableTransaction = $this->getTable('payone_core/transaction');

$sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.5.0-3.5.1.sql');

$installSqlConfig = array(
    '{{payone_config_payment_method}}' => $tablePaymentMethod,
    '{{payone_customer}}' => $tableCustomer,
    '{{payone_protocol_api}}' => $tableApiProtocol,
    '{{payone_protocol_transactionstatus}}' => $tableTransactionStatus,
    '{{payone_transaction}}' => $tableTransaction,
);

$installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
$installer->run($installSql);
$installer->endSetup();
 
