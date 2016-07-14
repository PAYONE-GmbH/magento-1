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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Service_Config_XmlGenerate
    extends Payone_Core_Model_Service_Abstract
{
    const CLASS_PREFIX = 'Payone_Settings_Data_ConfigFile_';
    const PAYMENT_METHOD_CLASS_PREFIX = 'Payone_Settings_Data_ConfigFile_PaymentMethod_';

    /** @var Mage_Core_Model_Store */
    private $store = NULL;

    /**
     * @return mixed
     */
    public function execute()
    {
        $service = $this->getFactory()->getServiceApiSettingsXmlGenerate();
        $serviceConfig = $this->getFactory()->getServiceInitializeConfig();

        /** @var $rootConfig Payone_Settings_Data_ConfigFile_Root */
        $rootConfig = $this->getSettingsClass('root');

        $stores = $this->getStores();
        foreach ($stores as $store) {
            /** @var $store Mage_Core_Model_Store */
            /** @var $config Payone_Core_Model_Config */
            $this->store = $store;
            $config = $serviceConfig->execute($store->getStoreId());
            $shopConfig = $this->generateSettingsShop($config);
            $rootConfig->addShop($shopConfig);
        }

        $xml = $service->generate($rootConfig);

        return $xml;
    }

    /**
     * Get all Magento stores
     * @return Mage_Core_Model_Store[]
     */
    protected function getStores()
    {
        return Mage::app()->getStores();
    }

    /**
     * @param $sectionName
     * @param $section
     * @return Payone_Settings_Data_ConfigFile_Abstract
     */
    protected function generateSettingsBySection($sectionName, $section)
    {
        /** @var $sectionConfig Payone_Settings_Data_ConfigFile_Abstract */
        $sectionConfig = $this->getSettingsClass($sectionName);

        foreach ($section->toArray() as $key => $data) {
            if ($key === 'enabled') {
                $key = 'active';
            }
            $setterName = 'set' . uc_words($key, '');
            if (method_exists($sectionConfig, $setterName)) {
                $sectionConfig->$setterName($data);
            }
        }

        return $sectionConfig;
    }

    /**
     * @param $config
     * @return Payone_Settings_Data_ConfigFile_Abstract|Payone_Settings_Data_ConfigFile_Shop
     */
    protected function generateSettingsShop($config)
    {
        /** @var $shopConfig Payone_Settings_Data_ConfigFile_Shop */
        $shopConfig = $this->getSettingsClass('shop');

        $helper = $this->helper();
        $shopCode = $this->store->getCode();
        $shopName = $this->store->getName();

        $shopConfig->setCode($shopCode);
        $shopConfig->setName($shopName);

        $systemConfig = $this->generateSettingsSystem();
        $shopConfig->setSystem($systemConfig);

        $globalConfig = $this->generateSettingsGlobal($config);
        $shopConfig->setGlobal($globalConfig);

        $clearingtypesConfig = $this->generateSettingsClearingtypes($config);
        $shopConfig->setClearingtypes($clearingtypesConfig);

        $protectConfig = $this->generateSettingsProtect($config);
        $shopConfig->setProtect($protectConfig);

        $miscConfig = $this->generateSettingsMisc($config);
        $shopConfig->setMisc($miscConfig);

        return $shopConfig;
    }

    /**
     * @return Payone_Settings_Data_ConfigFile_Abstract|Payone_Settings_Data_ConfigFile_Shop_System
     */
    protected function generateSettingsSystem()
    {
        $helper = $this->helper();
        /** @var $systemConfig Payone_Settings_Data_ConfigFile_Shop_System */
        $systemConfig = $this->getSettingsClass('shop_system');
        $modulesArray = $this->loadInstalledActiveModules();
        $systemConfig->setName('Magento');
        $systemConfig->setVersion($helper->getMagentoVersion());
        $systemConfig->setEdition($helper->getMagentoEdition());
        $systemConfig->setModules($modulesArray);

        return $systemConfig;
    }

    /**
     * @param Payone_Core_Model_Config $config
     * @return Payone_Settings_Data_ConfigFile_Abstract|Payone_Settings_Data_ConfigFile_Shop_Global
     */
    protected function generateSettingsGlobal(Payone_Core_Model_Config $config)
    {
        $general = $config->getGeneral();
        $global = $general->getGlobal();
        $parameterInvoice = $general->getParameterInvoice();
        $statusMapping = $general->getStatusMapping();
        $paymentCreditcard = $general->getPaymentCreditcard();

        /** @var $globalConfig Payone_Settings_Data_ConfigFile_Shop_Global */
        $globalConfig = $this->generateSettingsBySection('shop_global', $global);
        $statusMappingConfig = new Payone_Settings_Data_ConfigFile_Global_StatusMapping();
        foreach ($statusMapping->toArray() as $paymentMethod => $mapping) {
            $keyClearingType = $this->getPayoneShortKey($paymentMethod);
            if ($keyClearingType !== NULL) {
                $data = array();

                foreach ($mapping as $key => $value) {
                    $singleMap = array();
                    $singleMap['from'] = $key;

                    $mapTo = $value;
                    if (is_array($value)) {
                        $mapTo = implode('|', $value);
                    }
                    $singleMap['to'] = $mapTo;
                    $singleMap['method'] = $paymentMethod;

                    array_push($data, $singleMap);
                }
                $statusMappingConfig->addStatusMapping($keyClearingType, $data);
            }
        }

        $globalConfig->setStatusMapping($statusMappingConfig);
        $globalConfig->setParameterInvoice($parameterInvoice->toArray());
        $globalConfig->setPaymentCreditcard($paymentCreditcard->toArray());

        return $globalConfig;
    }

    /**
     * @param Payone_Core_Model_Config $config
     * @return Payone_Settings_Data_ConfigFile_Abstract|Payone_Settings_Data_ConfigFile_Shop_ClearingTypes
     */
    protected function generateSettingsClearingtypes(Payone_Core_Model_Config $config)
    {
        /** @var $clearingTypes Payone_Settings_Data_ConfigFile_Shop_ClearingTypes */
        $clearingTypes = $this->getSettingsClass('shop_clearingTypes');

        $payment = $config->getPayment();
        $clearingTypesArray = array();
        foreach ($payment->getMethods() as $paymentMethod) {
            /** @var $paymentMethod Payone_Core_Model_Config_Payment_Method */
            /** @var $paymentMethodConfig Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract */
            $paymentMethodConfig = $this->getPaymentMethodClass($paymentMethod->getCode());

            foreach ($paymentMethod->toArray() as $key => $value) {
                if ($key === 'enabled') {
                    $key = 'active';
                }
                $setterName = 'set' . uc_words($key, '');
                if (method_exists($paymentMethodConfig, $setterName)) {
                    $paymentMethodConfig->$setterName($value);
                }
            }

            $allowedCountries = $paymentMethod->getAllowedCountries();
            if (method_exists($paymentMethodConfig, 'setCountries')) {
                $paymentMethodConfig->setCountries(implode(',', $allowedCountries));
            }
            if (method_exists($paymentMethodConfig, 'setAuthorization')) {
                $paymentMethodConfig->setAuthorization($paymentMethod->getRequestType());
            }
            $paymentMethodConfig->setTitle($paymentMethod->getName());

            if ($paymentMethod->getTypes() !== NULL && $paymentMethod->getTypes() !== false) {
                $types = $paymentMethod->getTypes();
                if (is_array($types)) {
                    $types = implode(',', $types);
                }
                if ($paymentMethodConfig instanceof Payone_Settings_Data_ConfigFile_PaymentMethod_Creditcard) {
                    /**@var $paymentMethodConfig Payone_Settings_Data_ConfigFile_PaymentMethod_Creditcard */
                    $paymentMethodConfig->setCvc2($paymentMethod->getCheckCvc());
                }
                $paymentMethodConfig->setTypes($types);

            }

            $feeConfigs = $paymentMethod->getFeeConfig();
            $feeConfigArray = array();
            if (is_array($feeConfigs)) {
                foreach ($feeConfigs as $feeConfig) {
                    $attributeCountry = $attributeShippingMethod = '';
                    if (is_array($feeConfig)) {
                        if (array_key_exists('countries', $feeConfig)) {
                            $attributeCountry = array_shift($feeConfig['countries']);
                        }
                        if (array_key_exists('shipping_method', $feeConfig)) {
                            $attributeShippingMethod = array_shift($feeConfig['shipping_method']);
                        }
                    }
                    $attributeArray = array(
                        'country' => $attributeCountry,
                        'shipping_method' => $attributeShippingMethod);

                    $configArray = array(
                        'value' => $feeConfig['fee_config'],
                        'attribute' => $attributeArray);
                    array_push($feeConfigArray, $configArray);
                }
            }

            $paymentMethodConfig->setFeeConfig($feeConfigArray);

            array_push($clearingTypesArray, $paymentMethodConfig);
        }

        $clearingTypes->setClearingtypes($clearingTypesArray);

        return $clearingTypes;
    }

    /**
     * @param Payone_Core_Model_Config $config
     * @return Payone_Settings_Data_ConfigFile_Abstract|Payone_Settings_Data_ConfigFile_Shop_Protect
     */
    protected function generateSettingsProtect(Payone_Core_Model_Config $config)
    {
        /** @var $protectConfig Payone_Settings_Data_ConfigFile_Shop_Protect */
        $protectConfig = $this->getSettingsClass('shop_protect');

        $protect = $config->getProtect();
        $creditrating = $protect->getCreditrating();

        /** @var $consumerScore Payone_Settings_Data_ConfigFile_Protect_Consumerscore */
        $consumerScore = $this->generateSettingsBySection('protect_consumerscore', $creditrating);
        $yellow = $this->getAllowedPaymentMethods('yellow', $creditrating);
        $consumerScore->setYellow($yellow);
        $red = $this->getAllowedPaymentMethods('red', $creditrating);
        $consumerScore->setRed($red);
        $consumerScore->setDuetime($creditrating->getResultLifetimeInSeconds());

        /** @var $addressCheck Payone_Settings_Data_ConfigFile_Protect_Addresscheck */
        $addressCheck = $this->generateSettingsBySection('protect_addresscheck', $protect->getAddressCheck());
        $personStatusmapping = $protect->getAddressCheck()->getMappingPersonstatus();
        if (!is_array($personStatusmapping)) {
            $personStatusmapping = array();
        }
        $addressCheck->setPersonstatusmapping($personStatusmapping);
        $protectConfig->setConsumerscore($consumerScore);
        $protectConfig->setAddresscheck($addressCheck);

        return $protectConfig;
    }

    protected function getAllowedPaymentMethods($color, Payone_Core_Model_Config_Protect_Creditrating $creditrating)
    {
        $color = ucfirst($color);
        $getter = 'getAllowPaymentMethods' . $color;
        $paymentMethods = $creditrating->$getter();
        if (is_array($paymentMethods)) {
            $paymentMethods = str_replace('payone_', '', implode(',', $paymentMethods));
        }
        return $paymentMethods;
    }

    /**
     * @param Payone_Core_Model_Config $config
     * @return Payone_Settings_Data_ConfigFile_Abstract|Payone_Settings_Data_ConfigFile_Shop_Misc
     */
    protected function generateSettingsMisc(Payone_Core_Model_Config $config)
    {
        /** @var $miscConfig Payone_Settings_Data_ConfigFile_Shop_Misc */
        $miscConfig = $this->getSettingsClass('shop_misc');

        $misc = $config->getMisc();

        /** @var $transactionStatusForwarding Payone_Settings_Data_ConfigFile_Misc_TransactionstatusForwarding */
        $transactionStatusForwarding = $this->getSettingsClass('misc_transactionstatusForwarding');

        foreach ($misc->getTransactionstatusForwarding()->getConfigSortedByUrl() as $key => $value) {
            $status = implode(',', $value['status']);
            $data = array('status' => $status, 'url' => $key, 'timeout' => $value['timeout']);
            $transactionStatusForwarding->addTransactionstatusForwarding($data);
        }

        $miscConfig->setTransactionstatusforwarding($transactionStatusForwarding);
        $miscConfig->setShippingcosts($misc->getShippingCosts()->toArray());

        return $miscConfig;
    }

    /**
     * @param $key
     * @return Payone_Settings_Data_ConfigFile_Abstract
     */
    protected function getSettingsClass($key)
    {
        $key = uc_words($key);
        $classname = self::CLASS_PREFIX . $key;
        $classInstance = new $classname();
        return $classInstance;
    }

    /**
     * @param $key
     * @return Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract
     */
    protected function getPaymentMethodClass($key)
    {
        if ($key === 'safe_invoice') {
            $key = 'financing';
        } // safe_invoice is a sub-paymentmethod of financing in SDK.
        $key = uc_words($key, '');
        $classname = self::PAYMENT_METHOD_CLASS_PREFIX . $key;
        $classInstance = new $classname();
        return $classInstance;
    }

    /**
     * @param $key
     * @return null
     */
    protected function getPayoneShortKey($key)
    {
        $key = strtoupper(uc_words($key, ''));
        $clearingTypes = $this->getFactory()->getModelSystemConfigClearingType();
        $keyArray = $clearingTypes->toArrayNoFlip();
        if (array_key_exists($key, $keyArray)) {
            return $keyArray[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    protected function loadInstalledActiveModules()
    {
        $modulesArray = Mage::getConfig()->getNode('modules')->children();

        $activeModules = array();
        foreach ($modulesArray as $key => $value) {
            /**@var  $value Mage_Core_Model_Config_Element */
            if ($value->is('active') && !$value->is('codePool', 'core')) {
                $activeModules[$key] = (string)$value->version;
            }
        }
        return $activeModules;
    }
}