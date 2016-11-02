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
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.3.2-3.3.3.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod,
    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    $connection = $installer->getConnection();

    // Update table payone_config_payment_method
    $connection->addColumn(
        $tablePaymentMethod, 'paypal_express_visible_on_cart',
        'INT(1) COMMENT \'Paypal Express Visible on Cart\' AFTER `klarna_campaign_code`'
    );
    $connection->addColumn(
        $tablePaymentMethod, 'paypal_express_address',
        'INT(1) COMMENT \'Paypal Express Address\' AFTER `paypal_express_visible_on_cart`'
    );
    $connection->addColumn(
        $tablePaymentMethod, 'paypal_express_image',
        'VARCHAR(250) COMMENT \'Paypal Express Image\' AFTER `paypal_express_address`'
    );
}

$installer->endSetup();