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
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */
class Payone_Core_Model_System_Config_TranslationErrors extends Payone_Core_Model_System_Config_Abstract
{

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'invalidCardpan' => Mage::helper('payone_core')->__('Invalid cardpan'),
            'invalidCvc' => Mage::helper('payone_core')->__('Invalid CVC'),
            'invalidPanForCardtype' => Mage::helper('payone_core')->__('Invalid cardpan for the cardtype'),
            'invalidCardtype' => Mage::helper('payone_core')->__('Invalid cardtype'),
            'invalidExpireDate' => Mage::helper('payone_core')->__('Invalid expire date'),
            'invalidIssueNumber' => Mage::helper('payone_core')->__('Invalid issue number'),
            'transactionRejected' => Mage::helper('payone_core')->__('Transaction rejected'),
        );
    }
}