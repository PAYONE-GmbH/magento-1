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
 * @copyright       Copyright (c) 2017 <support@e3n.de> - www.e3n.de
 * @author          Tim Rein <web.it.rein@gmail.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.e3n.de
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
if (false === Mage::getConfig()->getModuleConfig('Mage_AdminNotification')->is('active', 'true')) {
    return $this;
}
$installer->startSetup();


$tablePaymentMethod = $this->getTable('payone_core/config_payment_method');

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');
$useSqlInstaller = $helper->mustUseSqlInstaller();

if ($useSqlInstaller) {
    $sql = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upgrade-3.7.7-3.7.8.sql');

    $installSqlConfig = array(
        '{{payone_config_payment_method}}' => $tablePaymentMethod

    );

    $installSql = str_replace(array_keys($installSqlConfig), array_values($installSqlConfig), $sql);
    $installer->run($installSql);
} else {
    $connection = $installer->getConnection();


    // Update table payone_config_payment_method
    $connection->addColumn(
        $tablePaymentMethod, 'hide_cvc',
        'TEXT COMMENT \'hide_cvc\' AFTER `check_cvc`'
    );


}


// German Description
$title = 'PAYONE Payment Extension Major Update';
$description = 'PAYONE Plugin: Die Wallets und Onlineüberweisungen wurden aufgeteilt.<br>';
$description .= 'Bitte löschen Sie die Zahlungsarten Payone Wallet und Payone Onlineüberweisung und fügen Sie die einzelnen von Ihnen genutzen Wallets und Onlineüberweisungen erneut hinzu.<br>';
$description .= 'Hierdurch wird die Conversion bei diesen Zahlungsarten erheblich verbessert.<br>';
$description .= '<br><hr><br>';

// English Description
$description .= 'PAYONE Plugin: The wallet and online bank transfer payment methods have been split up.<br>';
$description .= 'Please delete the Payone Wallet and Payone Online Bank Transfer payment method and add the individual wallets and online bank transfer services.';
$description .= 'This significantly improves conversion with these payment types.<br>';

// Queue a message with priority "major"
$message = new Mage_AdminNotification_Model_Inbox();
$message->setSeverity(Mage_AdminNotification_Model_Inbox::SEVERITY_MAJOR);
$message->setTitle($title);
$message->setDescription($description);
$message->setUrl('');
$message->setDateAdded(date('Y-m-d H:i:s'));
$message->save();

$installer->endSetup();
