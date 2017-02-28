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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/** @var $this Mage_Core_Model_Resource_Setup */
/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;
if(false === Mage::getConfig()->getModuleConfig('Mage_AdminNotification')->is('active', 'true')){
    return $this;
}
$installer->startSetup();

// German Description
$title = 'PAYONE Payment Extension 3.0 Installation';
$description = 'Die PAYONE Payment Extension 3.0 Installation wurde erfolgreich beendet.<br>';
$description .= 'Bitte starten Sie den Konfigurationsassistenten, um die Konfiguration abzuschließen. (PAYONE > Konfigurationsassistent)<br>';
$description .= 'Stellen Sie weiterhin sicher, dass die Cronjobs für ihr System korrekt eingerichtet sind.<br>';
$description .= '<br><hr><br>';

// English Description
$description .= 'PAYONE Payment Extension 3.0 successfully installed.<br>';
$description .= 'Please run the configuration wizard to configure PAYONE. (PAYONE > Configuration-Wizard)';
$description .= 'Also ensure that cronjobs are configured for your system.<br>';

// Queue a message with priority "major"
$message = new Mage_AdminNotification_Model_Inbox();
$message->setSeverity(Mage_AdminNotification_Model_Inbox::SEVERITY_MAJOR);
$message->setTitle($title);
$message->setDescription($description);
$message->setUrl('');
$message->setDateAdded(date('Y-m-d H:i:s'));
$message->save();

$installer->endSetup();
