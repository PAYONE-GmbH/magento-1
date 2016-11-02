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
$installer->startSetup();

/** @var $helper Payone_Core_Helper_Data */
$helper = Mage::helper('payone_core');


if ($helper->isCronEnabled() === false) {
    $title = 'PAYONE Payment Extension 3.0: Cronjob Configuration';
    $wikiUrl = 'http://www.magentocommerce.com/wiki/1_-_installation_and_configuration/how_to_setup_a_cron_job';

    // German Description
    $description = 'Um den Betrieb der PAYONE Extension zu gewährleisten, richten Sie bitte Cronjobs für ihr System ein. Weitere Informationen finden Sie unter:<br>';
    $description .= $wikiUrl;
    $description .= '<br><hr><br>';

    // English Description
    $description .= 'To ensure proper operation of the PAYONE Extension, please configure cronjobs for your system. Further information can be found here:<br>';
    $description .= $wikiUrl;


    // Queue a message with priority "critical"
    $message = new Mage_AdminNotification_Model_Inbox();
    $message->setSeverity(Mage_AdminNotification_Model_Inbox::SEVERITY_CRITICAL);
    $message->setTitle($title);
    $message->setDescription($description);
    $message->setUrl($wikiUrl);
    $message->setDateAdded(date('Y-m-d H:i:s'));
    $message->save();
}

$installer->endSetup();