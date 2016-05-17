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

// Add attributes:
/* @var $installer Mage_Customer_Model_Entity_Setup */
$setup = new Mage_Customer_Model_Entity_Setup('core_setup');
$setup->addAttribute('customer', 'payone_last_payment_method', array(
    'type' => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'label' => 'Payone Last Payment Method',
    'visible' => false,
    'required' => false));

$installer->endSetup();