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
 * @subpackage      Factory
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Migrator_Model
 * @subpackage      Factory
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Migrator_Model_Factory
{
    /**
     * @var Payone_Migrator_Helper_Data
     */
    protected $helper = null;

    /**
     * @return Payone_Migrator_Helper_Data
     */
    public function helper()
    {
        if ($this->helper === null) {
            $this->helper = Mage::helper('payone_migrator');
        }
        return $this->helper;
    }

    /**
     * @return Payone_Migrator_Model_Service_Migrate
     */
    public function getServiceMigrate()
    {
        /** @var $service Payone_Migrator_Model_Service_Migrate */
        $service = Mage::getModel('payone_migrator/service_migrate');
        $service->setFactory($this);
        $service->setServiceConfigGeneralMigrate($this->getServiceConfigurationGeneralMigrate());
        $service->setServiceConfigPaymentMigrate($this->getServiceConfigurationPaymentMigrate());
        $service->setServiceSalesPaymentMigrate($this->getServiceSalesPaymentMigrate());
        $service->setServiceConfigProtectMigrate($this->getServiceConfigurationProtectMigrate());

        return $service;
    }

    /**
     * @return Payone_Migrator_Model_Service_Sales_PaymentMigrate
     */
    public function getServiceSalesPaymentMigrate()
    {
        /** @var $service Payone_Migrator_Model_Service_Sales_PaymentMigrate */
        $service = Mage::getModel('payone_migrator/service_sales_paymentMigrate');
        $service->setFactory($this);
        $service->setMapperConfigPayment($this->getMapperConfigPayment());
        return $service;
    }

    /**
     * @return Payone_Migrator_Model_Service_Configuration_PaymentMigrate
     */
    public function getServiceConfigurationPaymentMigrate()
    {
        /** @var $service Payone_Migrator_Model_Service_Configuration_PaymentMigrate */
        $service = Mage::getModel('payone_migrator/service_configuration_paymentMigrate');
        $service->setFactory($this);
        $service->setMapperConfigPayment($this->getMapperConfigPayment());
        return $service;
    }

    /**
     * @return Payone_Migrator_Model_Service_Configuration_ProtectMigrate
     */
    public function getServiceConfigurationProtectMigrate()
    {
        /** @var $service Payone_Migrator_Model_Service_Configuration_ProtectMigrate */
        $service = Mage::getModel('payone_migrator/service_configuration_protectMigrate');
        $service->setFactory($this);
        $service->setMapperConfigProtect($this->getMapperConfigProtect());

        return $service;
    }

    /**
     * @return Payone_Migrator_Model_Service_Configuration_GeneralMigrate
     */
    public function getServiceConfigurationGeneralMigrate()
    {
        /** @var $service Payone_Migrator_Model_Service_Configuration_GeneralMigrate */
        $service = Mage::getModel('payone_migrator/service_configuration_generalMigrate');
        $service->setFactory($this);
        $service->setMapperConfigGeneral($this->getMapperConfigGeneral());

        return $service;
    }

    /**
     * @return Mage_Core_Model_Resource
     */
    public function getModelCoreResource()
    {
        /** @var $model Mage_Core_Model_Resource */
        $model = Mage::getModel('core/resource');
        return $model;
    }

    /**
     * @return Mage_Core_Model_Config_Data
     */
    public function getModelCoreConfigData()
    {
        /** @var $model Mage_Core_Model_Config_Data */
        $model = Mage::getModel('core/config_data');
        return $model;
    }

    /**
     * @return Payone_Migrator_Model_Mapper_Config_Payment
     */
    public function getMapperConfigPayment()
    {
        /** @var $mapper Payone_Migrator_Model_Mapper_Config_Payment */
        $mapper = Mage::getModel('payone_migrator/mapper_config_payment');

        return $mapper;
    }

    /**
     * @return Payone_Migrator_Model_Mapper_Config_Protect
     */
    public function getMapperConfigProtect()
    {
        /** @var $mapper Payone_Migrator_Model_Mapper_Config_Protect */
        $mapper = Mage::getModel('payone_migrator/mapper_config_protect');
        $mapper->setMapperConfigPayment($this->getMapperConfigPayment());

        return $mapper;
    }

    /**
     * @return Payone_Migrator_Model_Mapper_Config_General
     */
    public function getMapperConfigGeneral()
    {
        /** @var $mapper Payone_Migrator_Model_Mapper_Config_General */
        $mapper = Mage::getModel('payone_migrator/mapper_config_general');

        return $mapper;
    }

    /**
     * @return Payone_Core_Model_Domain_Config_PaymentMethod
     */
    public function getModelDomainConfigPaymentMethod()
    {
        return Mage::getModel('payone_core/domain_config_paymentMethod');
    }

}