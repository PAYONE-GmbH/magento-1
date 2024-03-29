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
 * @copyright       Copyright (c) 2021 <kontakt@fatchip.de> - www.fatchip.de
 * @author          Fatchip GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://www.fatchip.de
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
$installer->startSetup();

$tablePaymentMethod = $this->getTable('payone_core/config_payment_method');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-5.2.0-5.2.1.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
}
else {
    $connection = $installer->getConnection();

    // using string definition as AFTER is not supported via array:
    $connection->addColumn(
        $tablePaymentMethod, 'apl_merchant_id',
        'VARCHAR(150) COMMENT \'Merchant ID for ApplePay\''
    );

    $connection->addColumn(
        $tablePaymentMethod, 'apl_merchant_identification_certificate',
        'VARCHAR(150) COMMENT \'Merchant certificate for ApplePay\''
    );

    $connection->addColumn(
        $tablePaymentMethod, 'apl_certificate_private_key',
        'VARCHAR(150) COMMENT \'Certificate private key for ApplePay\''
    );

    $connection->addColumn(
        $tablePaymentMethod, 'apl_certificate_key_password',
        'VARCHAR(150) COMMENT \'Certificate Key password for ApplePay\''
    );
}

$installer->endSetup();