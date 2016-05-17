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
 * @package         Payone_Migrator_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Migrator_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Migrator_Model_Service_Abstract
{
    /**
     * @var Payone_Migrator_Helper_Data
     */
    protected $helper = null;
    /**
     * @var Payone_Migrator_Helper_Config
     */
    protected $helperConfig = null;

    /** @var Payone_Migrator_Model_Factory */
    protected $factory = null;
    /**
     * @var Mage_Sales_Model_Resource_Order_Payment_Collection
     */
    protected $orderPayments = null;

    protected function hasPayoneOrders()
    {
        return $this->helper()->hasPayoneOrders();
    }

    /**
     * @param Mage_Sales_Model_Resource_Order_Payment_Collection $orderPayments
     */
    public function setOrderPayments($orderPayments)
    {
        $this->orderPayments = $orderPayments;
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Payment_Collection
     */
    public function getOrderPayments()
    {
        if ($this->orderPayments == null) {
            $this->orderPayments = $this->helper()->getOrderPayments();
        }
        return $this->orderPayments;
    }

    /**
     * @return Payone_Migrator_Helper_Data
     */
    protected function helper()
    {
        if ($this->helper === null) {
            $this->helper = $this->getFactory()->helper();
        }
        return $this->helper;
    }

    /**
     * @return Payone_Migrator_Helper_Config
     */
    protected function helperConfig()
    {
        if ($this->helperConfig === null) {
            $this->helperConfig = Mage::helper('payone_migrator/config');
        }
        return $this->helperConfig;
    }

    /**
     * @param Payone_Migrator_Model_Factory $factory
     */
    public function setFactory(Payone_Migrator_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Migrator_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Migrator_Model_Factory();
        }
        return $this->factory;
    }


}