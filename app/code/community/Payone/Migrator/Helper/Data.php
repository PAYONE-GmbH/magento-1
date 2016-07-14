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
 * Do not edit or add to this file if you wish to upgrade Payone_Migrator to newer
 * versions in the future. If you wish to customize Payone_Migrator for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Migrator_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Migrator_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Migrator_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    protected $parts = array(
        'config_global',
        'config_payment',
        'order_payment',
        'payment_finish',
        'config_protect'
    );

    protected $steps = array(
        'config_global' => 'config_payment',
        'config_payment' => 'order_payment',
        'order_payment' => 'payment_finish',
        'payment_finish' => 'config_protect',
        'config_protect' => 'finish',
        'finish' => 0
    );

    public function log($message, $level = null)
    {
        Mage::log($message, $level, 'payone_migrator_migration.log', true);
    }

    /**
     * @return bool
     */
    public function hasPayoneOrders()
    {
        $this->log('detecting payment configurations to migrate');

        $count = $this->getOrderPayments()->count();

        $this->log('found ' . $count . ' configurations');

        return ($count > 0) ? true : false;
    }

    /**
     * @return bool
     */
    public function hasProtectConfig()
    {
        $this->log('detecting protect configurations to migrate');

        $count = $this->getOldProtectConfig()->count();

        $this->log('found ' . $count . ' protect config');

        return ($count > 0) ? true : false;
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Payment_Collection
     */
    public function getOrderPayments()
    {
        /** @var $paymentCollection Mage_Sales_Model_Resource_Order_Payment_Collection */
        $paymentCollection = Mage::getModel('sales/order_payment')->getCollection();
        $paymentCollection->join('order', 'main_table.parent_id = order.entity_id', 'store_id');
        $paymentCollection->addFieldToFilter('method',
            array(
                0 => array('eq' => 'payone_rec'),
                1 => array('eq' => 'payone_cc'),
                2 => array('eq' => 'payone_vor'),
                3 => array('eq' => 'payone_elv'),
                4 => array('eq' => 'payone_sb'),
                5 => array('eq' => 'payone_cod'),
                6 => array('eq' => 'payone_wlt'),
                7 => array('eq' => 'payone_csh'),
                8 => array('eq' => 'payone_fnc'),
            )
        );
        $paymentCollection->getSelect()->group(array('method', 'order.store_id'));

        return $paymentCollection;
    }

    /**
     * @return Mage_Core_Model_Mysql4_Config_Data_Collection
     */
    public function getOldProtectConfig()
    {
        /** @var $oldCollection Mage_Core_Model_Mysql4_Config_Data_Collection */
        $oldCollection = Mage::getModel('core/config_data')->getCollection();

        $oldCollection->addFieldToFilter(
            'path',
            array(
                0 => array('like' => Payone_Migrator_Model_Mapper_Config_Protect::CONFIGPATH_MAGE_PAYONE_CREDITRATING . '%'),
                1 => array('like' => Payone_Migrator_Model_Mapper_Config_Protect::CONFIGPATH_MAGE_PAYONE_ADDRESSCHECK . '%'),
                2 => array('like' => Payone_Migrator_Model_Mapper_Config_Protect::CONFIGPATH_MAGE_PAYONE_CREDITRATING_MISC . '%'),
            )
        );
        return $oldCollection;
    }

    public function getNextStep($currentStep)
    {
        return $this->steps[$currentStep];
    }

    public function getSteps()
    {
        return $this->steps;
    }

    public function getParts()
    {
        return $this->parts;
    }

}