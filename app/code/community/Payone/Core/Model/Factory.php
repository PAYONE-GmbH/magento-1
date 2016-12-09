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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Factory
{
    /** @var Payone_Core_Helper_Data */
    protected $helper = null;

    /** @var Payone_Builder */
    protected $builder = null;

    /** @var Payone_Config */
    protected $config = null;

    public function __construct()
    {
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    public function helper()
    {
        if ($this->helper === null) {
            $this->helper = Mage::helper('payone_core');
        }

        return $this->helper;
    }

    /**
     *
     * @return Payone_Core_Helper_Email
     */
    public function helperEmail()
    {
        return Mage::helper('payone_core/email');
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    public function helperConfig()
    {
        return Mage::helper('payone_core/config');
    }

    /**
     * @return Payone_Core_Helper_Score
     */
    public function helperScore()
    {
        return Mage::helper('payone_core/score');
    }

    /**
     *
     * @return Payone_Core_Helper_Registry
     */
    public function helperRegistry()
    {
        return Mage::helper('payone_core/registry');
    }
    
    /**
     * @return Payone_Core_Helper_Url
     */
    public function helperUrl()
    {
        return Mage::helper('payone_core/url');
    }

    /**
     * @return Payone_Core_Helper_Wizard
     */
    public function helperWizard()
    {
        return Mage::helper('payone_core/wizard');
    }

    /**
     * @return Mage_Tax_Helper_Data
     */
    public function helperMageTax()
    {
        return Mage::helper('tax');
    }

    /**
     *
     * @param Payone_Core_Helper_Data $helper
     */
    public function setHelper(Payone_Core_Helper_Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param string $key
     * @param array $ipConfig
     * @return Payone_TransactionStatus_Service_HandleRequest
     */
    public function getServiceTransactionStatusHandleRequest($key, array $ipConfig)
    {
        $key = md5($key);
        $service = $this->getBuilder()->buildServiceTransactionStatusHandleRequest($key, $ipConfig);

        /** @var $repository Payone_Core_Model_Repository_TransactionStatus */
        $repository = Mage::getModel('payone_core/repository_transactionStatus');
        $service->getServiceProtocol()->addRepository($repository);

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Payment_Authorize
     */
    public function getMapperPaymentRequestAuthorize(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Payment_Authorize */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_payment_authorize');
        $mapper->setConfigPayment($config);
        $mapper->setFactory($this);
        $mapper->setIsAdmin($this->getIsAdmin());

        return $mapper;

    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Payment_Preauthorize
     */
    public function getMapperPaymentRequestPreauthorize(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Payment_Preauthorize */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_payment_preauthorize');
        $mapper->setConfigPayment($config);
        $mapper->setFactory($this);
        $mapper->setIsAdmin($this->getIsAdmin());

        return $mapper;

    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Payment_Capture
     */
    public function getMapperPaymentRequestCapture(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Payment_Capture */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_payment_capture');
        $mapper->setConfigPayment($config);
        $mapper->setFactory($this);
        $mapper->setIsAdmin($this->getIsAdmin());

        return $mapper;

    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Payment_Debit
     */
    public function getMapperPaymentRequestDebit(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Payment_Debit */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_payment_debit');
        $mapper->setConfigPayment($config);
        $mapper->setFactory($this);
        $mapper->setIsAdmin($this->getIsAdmin());

        return $mapper;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment
     */
    public function getMapperPaymentRequestGenericpayment(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_payment_genericpayment');
        $mapper->setConfigPayment($config);
        $mapper->setFactory($this);
        $mapper->setIsAdmin($this->getIsAdmin());

        return $mapper;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_AddressCheck $configAddresscheck
     * @return Payone_Core_Model_Mapper_ApiRequest_Verification_AddressCheck
     */
    public function getMapperVerificationRequestAddressCheck(Payone_Core_Model_Config_Protect_AddressCheck $configAddresscheck)
    {
        $configGlobal = $this->helperConfig()->getConfigGeneral()->getGlobal();

        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Verification_AddressCheck */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_verification_addressCheck');
        $mapper->setFactory($this);
        $mapper->setConfig($configAddresscheck);
        $mapper->setConfigGlobal($configGlobal);

        return $mapper;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $paymentConfig Payment configuration for "debit_payment"
     * @return Payone_Core_Model_Mapper_ApiRequest_Verification_BankAccountCheck
     */
    public function getMapperVerificationRequestBankAccountCheck(Payone_Core_Model_Config_Payment_Method_Interface $paymentConfig)
    {
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Verification_BankAccountCheck */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_verification_BankAccountCheck');
        $mapper->setFactory($this);
        $mapper->setConfig($paymentConfig);

        return $mapper;
    }

    /**
     * @return Payone_Core_Model_Mapper_ApiRequest_Management_GetInvoice
     */
    public function getMapperManagementRequestGetInvoice()
    {
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Management_GetInvoice */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_management_getInvoice');
        $mapper->setFactory($this);

        return $mapper;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Management_ManageMandate
     */
    public function getMapperManagementRequestManageMandate(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var Payone_Core_Model_Mapper_ApiRequest_Management_ManageMandate $mapper */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_management_manageMandate');
        $mapper->setPaymentConfig($config);
        $mapper->setFactory($this);

        return $mapper;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Management_GetFile
     */
    public function getMapperManagementRequestGetFile(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var Payone_Core_Model_Mapper_ApiRequest_Management_GetFile $mapper */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_management_getFile');
        $mapper->setConfig($config);
        $mapper->setFactory($this);

        return $mapper;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_Creditrating $config
     * @return Payone_Core_Model_Mapper_ApiRequest_Verification_Creditrating
     */
    public function getMapperVerificationRequestCreditrating(Payone_Core_Model_Config_Protect_Creditrating $config)
    {
        $configGlobal = $this->helperConfig()->getConfigGeneral()->getGlobal();
        /** @var $mapper Payone_Core_Model_Mapper_ApiRequest_Verification_Creditrating */
        $mapper = Mage::getModel('payone_core/mapper_apiRequest_verification_creditrating');
        $mapper->setFactory($this);
        $mapper->setConfig($config);
        $mapper->setConfigGlobal($configGlobal);

        return $mapper;
    }

    /**
     * @return bool
     */
    public function getIsAdmin()
    {
        return Mage::app()->getStore()->isAdmin();
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Service_Payment_Authorize
     */
    public function getServicePaymentAuthorize(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $mapper = $this->getMapperPaymentRequestAuthorize($config);
        $handler = $this->getHandlerPaymentAuthorize($config);

        /** @var $service Payone_Core_Model_Service_Payment_Authorize */
        $service = Mage::getModel('payone_core/service_payment_authorize');
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setServiceApiPayment($this->getServiceApiPaymentAuthorize());

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Service_Payment_Preauthorize
     */
    public function getServicePaymentPreauthorize(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $mapper = $this->getMapperPaymentRequestPreauthorize($config);
        $handler = $this->getHandlerPaymentPreauthorize($config);

        /** @var $service Payone_Core_Model_Service_Payment_Preauthorize */
        $service = Mage::getModel('payone_core/service_payment_preauthorize');
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setServiceApiPayment($this->getServiceApiPaymentPreauthorize());

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Service_Payment_Capture
     */
    public function getServicePaymentCapture(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $mapper = $this->getMapperPaymentRequestCapture($config);
        $handler = $this->getHandlerPaymentCapture($config);

        /** @var $service Payone_Core_Model_Service_Payment_Capture */
        $service = Mage::getModel('payone_core/service_payment_capture');
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setServiceApiPayment($this->getServiceApiPaymentCapture());

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Service_Payment_Debit
     */
    public function getServicePaymentDebit(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $mapper = $this->getMapperPaymentRequestDebit($config);
        $handler = $this->getHandlerPaymentDebit($config);

        /** @var $service Payone_Core_Model_Service_Payment_Debit */
        $service = Mage::getModel('payone_core/service_payment_debit');
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setServiceApiPayment($this->getServiceApiPaymentDebit());

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Service_Payment_Genericpayment
     */
    public function getServicePaymentGenericpayment(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $mapper = $this->getMapperPaymentRequestGenericpayment($config);
        $handler = $this->getHandlerPaymentGenericpayment($config);

        /** @var $service Payone_Core_Model_Service_Payment_Genericpayment */
        $service = Mage::getModel('payone_core/service_payment_genericpayment');
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setServiceApiPayment($this->getServiceApiPaymentGenericpayment());

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Management_GetInvoice
     */
    public function getServiceManagementGetInvoice()
    {
        $mapper = $this->getMapperManagementRequestGetInvoice();
        $handler = $this->getHandlerManagementGetInvoice();

        /** @var $service Payone_Core_Model_Service_Management_GetInvoice */
        $service = Mage::getModel('payone_core/service_management_getInvoice');
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setServiceApiGetInvoice($this->getServiceApiManagementGetInvoice());

        return $service;
    }

    /**
     * @param $paymentMethodConfigId
     * @param $storeId
     * @return Payone_Core_Model_Service_Management_ManageMandate
     */
    public function getServiceManagementManageMandate($paymentMethodConfigId, $storeId)
    {
        $config = $this->helperConfig()->getConfigPaymentMethodById($paymentMethodConfigId, $storeId);
        $mapper = $this->getMapperManagementRequestManageMandate($config);
        /** @var Payone_Core_Model_Service_Management_ManageMandate $service */
        $service = Mage::getModel('payone_core/service_management_manageMandate');
        $service->setMapper($mapper);
        $service->setServiceApiManageMandate($this->getServiceApiManagementManageMandate());

        return $service;
    }

    /**
     * @param $paymentMethodConfigId
     * @param $storeId
     * @return Payone_Core_Model_Service_Management_GetFile
     */
    public function getServiceManagementGetFile($paymentMethodConfigId, $storeId)
    {
        $config = $this->helperConfig()->getConfigPaymentMethodById($paymentMethodConfigId, $storeId);
        $mapper = $this->getMapperManagementRequestGetFile($config);

        /** @var Payone_Core_Model_Service_Management_GetFile $service */
        $service = Mage::getModel('payone_core/service_management_getFile');
        $service->setMapper($mapper);
        $service->setServiceApiGetFile($this->getServiceApiManagementGetFile());

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_AddressCheck $config
     * @return Payone_Core_Model_Service_Verification_AddressCheck
     */
    public function getServiceVerificationAddressCheck(Payone_Core_Model_Config_Protect_AddressCheck $config)
    {
        $mapper = $this->getMapperVerificationRequestAddressCheck($config);
        $handler = $this->getHandlerVerificationAddressCheck($config);

        /** @var $service Payone_Core_Model_Service_Verification_AddressCheck */
        $service = Mage::getModel('payone_core/service_verification_addressCheck');
        $service->setConfig($config);
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setServiceApiAddressCheck($this->getServiceApiVerificationAddressCheck());

        return $service;
    }

    /**
     * @param $paymentMethodConfigId
     * @param $storeId
     * @return Payone_Core_Model_Service_Verification_BankAccountCheck
     */
    public function getServiceVerificationBankAccountCheck($paymentMethodConfigId, $storeId)
    {
        $config = $this->helperConfig()->getConfigPaymentMethodById($paymentMethodConfigId, $storeId);
        $mapper = $this->getMapperVerificationRequestBankAccountCheck($config);

        /** @var $service Payone_Core_Model_Service_Verification_BankAccountCheck */
        $service = Mage::getModel('payone_core/service_verification_bankAccountCheck');
        $service->setMapper($mapper);
        $service->setConfigPayment($config);
        $service->setServiceApiBankAccountCheck($this->getServiceApiVerificationBankAccountCheck());

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_AddressCheck $config
     * @return Payone_Core_Model_Handler_Verification_AddressCheck
     */
    public function getHandlerVerificationAddressCheck(Payone_Core_Model_Config_Protect_AddressCheck $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Verification_AddressCheck */
        $handler = Mage::getModel('payone_core/handler_verification_addressCheck');
        $handler->setConfig($config);

        return $handler;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_Creditrating $config
     * @return Payone_Core_Model_Handler_Verification_Creditrating
     */
    public function getHandlerVerificationCreditrating(Payone_Core_Model_Config_Protect_Creditrating $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Verification_Creditrating */
        $handler = Mage::getModel('payone_core/handler_verification_creditrating');
        $handler->setConfig($config);

        return $handler;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_Creditrating $config
     * @return Payone_Core_Model_Service_Verification_Creditrating
     */
    public function getServiceVerificationCreditrating(Payone_Core_Model_Config_Protect_Creditrating $config)
    {
        $mapper = $this->getMapperVerificationRequestCreditrating($config);
        $handler = $this->getHandlerVerificationCreditrating($config);

        /** @var $service Payone_Core_Model_Service_Verification_Creditrating */
        $service = Mage::getModel('payone_core/service_verification_creditrating');
        $service->setMapper($mapper);
        $service->setHandler($handler);
        $service->setConfig($config);
        $service->setServiceApiConsumerScore($this->getServiceApiVerificationConsumerScore());

        return $service;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Handler_Payment_Abstract|Payone_Core_Model_Handler_Payment_Authorize
     */
    public function getHandlerPaymentAuthorize(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Payment_Authorize */
        $handler = $this->getHandlerPayment('payone_core/handler_payment_authorize', $config);
        return $handler;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Handler_Payment_Abstract|Payone_Core_Model_Handler_Payment_Preauthorize
     */
    public function getHandlerPaymentPreauthorize(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Payment_Preauthorize */
        $handler = $this->getHandlerPayment('payone_core/handler_payment_preauthorize', $config);
        return $handler;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Handler_Payment_Abstract|Payone_Core_Model_Handler_Payment_Capture
     */
    public function getHandlerPaymentCapture(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Payment_Capture */
        $handler = $this->getHandlerPayment('payone_core/handler_payment_capture', $config);
        return $handler;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Handler_Payment_Abstract|Payone_Core_Model_Handler_Payment_Debit
     */
    public function getHandlerPaymentDebit(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Payment_Debit */
        $handler = $this->getHandlerPayment('payone_core/handler_payment_debit', $config);
        return $handler;
    }

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Handler_Payment_Abstract|Payone_Core_Model_Handler_Payment_Genericpayment
     */
    public function getHandlerPaymentGenericpayment(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Payment_Genericpayment */
        $handler = $this->getHandlerPayment('payone_core/handler_payment_genericpayment', $config);
        return $handler;
    }

    /**
     * @return Payone_Core_Model_Handler_Management_GetInvoice
     */
    public function getHandlerManagementGetInvoice()
    {
        /** @var $handler Payone_Core_Model_Handler_Management_GetInvoice */
        $handler = Mage::getModel('payone_core/handler_management_getInvoice');

        return $handler;
    }

    /**
     * @param $modelName
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Handler_Payment_Abstract
     */
    protected function getHandlerPayment($modelName, Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        /** @var $handler Payone_Core_Model_Handler_Payment_Abstract */
        $handler = Mage::getModel($modelName);
        $handler->setFactory($this);
        $handler->setServiceOrderComment($this->getServiceSalesOrderComment());
        $handler->setServiceOrderStatus($this->getServiceSalesOrderStatus());
        $handler->setServiceTransactionCreate($this->getServiceTransactionCreate());
        $handler->setServiceTransactionUpdate($this->getServiceTransactionUpdate());
        $handler->setConfigPaymentMethod($config);
        return $handler;
    }

    /**
     * @return Payone_Api_Request_Authorization
     */
    public function getRequestPaymentAuthorization()
    {
        $request = new Payone_Api_Request_Authorization();
        return $request;
    }

    /**
     * @return Payone_Api_Request_Preauthorization
     */
    public function getRequestPaymentPreauthorize()
    {
        $request = new Payone_Api_Request_Preauthorization();
        return $request;
    }

    /**
     * @return Payone_Api_Request_Capture
     */
    public function getRequestPaymentCapture()
    {
        $request = new Payone_Api_Request_Capture();
        return $request;
    }

    /**
     * @return Payone_Api_Request_Debit
     */
    public function getRequestPaymentDebit()
    {
        $request = new Payone_Api_Request_Debit();
        return $request;
    }

    /**
     * @return Payone_Api_Request_Genericpayment
     */
    public function getRequestPaymentGenericpayment()
    {
        $request = new Payone_Api_Request_Genericpayment();
        return $request;
    }

    /**
     * @return Payone_Api_Request_AddressCheck
     */
    public function getRequestVerificationAddressCheck()
    {
        $request = new Payone_Api_Request_AddressCheck();
        return $request;
    }


    /**
     * @return Payone_Api_Request_BankAccountCheck
     */
    public function getRequestVerificationBankAccountCheck()
    {
        $request = new Payone_Api_Request_BankAccountCheck();
        return $request;
    }

    /**
     * @return Payone_Api_Request_GetInvoice
     */
    public function getRequestManagementGetInvoice()
    {
        $request = new Payone_Api_Request_GetInvoice();
        return $request;
    }

    /**
     * @return Payone_Api_Request_ManageMandate
     */
    public function getRequestManagementManageMandate()
    {
        $request = new Payone_Api_Request_ManageMandate();
        return $request;
    }

    /**
     * @return Payone_Api_Request_GetFile
     */
    public function getRequestManagementGetFile()
    {
        $request = new Payone_Api_Request_GetFile();
        return $request;
    }

    /**
     * @return Payone_Api_Request_AddressCheck
     */
    public function getRequestVerificationConsumerScore()
    {
        $request = new Payone_Api_Request_Consumerscore();
        return $request;
    }

    /**
     * @return Payone_Api_Service_Payment_Authorize
     */
    public function getServiceApiPaymentAuthorize()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServicePaymentAuthorize();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Payment_PReauthorize
     */
    public function getServiceApiPaymentPreauthorize()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServicePaymentPreauthorize();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Payment_Capture
     */
    public function getServiceApiPaymentCapture()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServicePaymentCapture();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Payment_Debit
     */
    public function getServiceApiPaymentDebit()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServicePaymentDebit();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Payment_Genericpayment
     */
    public function getServiceApiPaymentGenericpayment()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServicePaymentGenericpayment();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_ClientApi_Service_GenerateHash
     */
    public function getServiceClientApiGenerateHash()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceClientApiGenerateHash();

        return $service;
    }

    /**
     * @return Payone_Api_Service_Verification_AddressCheck
     */
    public function getServiceApiVerificationAddressCheck()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceVerificationAddressCheck();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Verification_BankAccountCheck
     */
    public function getServiceApiVerificationBankAccountCheck()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceVerificationBankAccountCheck();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Management_GetInvoice
     */
    public function getServiceApiManagementGetInvoice()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceManagementGetInvoice();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Management_ManageMandate
     */
    public function getServiceApiManagementManageMandate()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceManagementManageMandate();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Management_GetFile
     */
    public function getServiceApiManagementGetFile()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceManagementGetFile();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Api_Service_Verification_ConsumerScore
     */
    public function getServiceApiVerificationConsumerScore()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceVerificationConsumerScore();

        $this->afterBuildServiceApi($service);

        return $service;
    }

    /**
     * @return Payone_Settings_Service_XmlGenerate
     */
    public function getServiceApiSettingsXmlGenerate()
    {
        $builder = $this->getBuilder();
        $service = $builder->buildServiceSettingsXmlGenerate();

        return $service;
    }

    /**
     * @return Payone_ClientApi_Request_CreditCardCheck
     */
    public function getRequestClientApiCreditCardCheck()
    {
        $request = new Payone_ClientApi_Request_CreditCardCheck();
        return $request;
    }


    /**
     * @param Payone_Api_Service_Interface $service
     */
    protected function afterBuildServiceApi(Payone_Api_Service_Interface $service)
    {
        /** @var $repository Payone_Core_Model_Repository_Api */
        $repository = Mage::getModel('payone_core/repository_api');
        $service->getServiceProtocol()->addRepository($repository);
    }

    /**
     * @param null|Payone_Core_Model_Config_Payment_Method_Interface $config
     * @return Payone_Core_Model_Service_InitializePayment
     */
    public function getServiceInitializePayment(Payone_Core_Model_Config_Payment_Method_Interface $config = null)
    {
        /** @var $service Payone_Core_Model_Service_InitializePayment */
        $service = Mage::getModel('payone_core/service_initializePayment');
        $service->setFactory($this);

        if (!is_null($config)) {
            $service->setConfigPaymentMethod($config);
        }

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_InitializeConfig
     */
    public function getServiceInitializeConfig()
    {
        /** @var $service Payone_Core_Model_Service_InitializeConfig */
        $service = Mage::getSingleton('payone_core/service_initializeConfig');
        $service->setFactory($this);

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_TransactionStatus_Execute
     */
    public function getServiceTransactionStatusExecute()
    {
        $maxExecutionTime = $this->helperConfig()
                ->getStoreConfig('payone_general/transactionstatus_execute/max_execution_time');

        /** @var $service Payone_Core_Model_Service_TransactionStatus_Execute */
        $service = Mage::getModel('payone_core/service_transactionStatus_execute');
        $service->setServiceProcess($this->getServiceTransactionStatusProcess());
        $service->setFactory($this);
        $service->setMaxExecutionTime($maxExecutionTime);

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_TransactionStatus_Process
     */
    public function getServiceTransactionStatusProcess()
    {
        /** @var $service Payone_Core_Model_Service_TransactionStatus_Process */
        $service = Mage::getModel('payone_core/service_transactionStatus_process');
        $service->setFactory($this);
        $service->setServiceTransaction($this->getServiceTransactionUpdate());
        $service->setServiceOrderStatus($this->getServiceSalesOrderStatus());
        $service->setServiceOrderComment($this->getServiceSalesOrderComment());
        $service->setServiceStoreClearingParams($this->getServiceTransactionStatusStoreClearingParameters());


        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_TransactionStatus_Forward
     */
    public function getServiceTransactionStatusForward()
    {
        /** @var $service Payone_Core_Model_Service_TransactionStatus_Forward */
        $service = Mage::getModel('payone_core/service_transactionStatus_forward');
        $service->setFactory($this);
        $service->setHttpClient($this->getModelVarienHttpClient());
        return $service;
    }


    /**
     * @return Payone_Core_Model_Service_TransactionStatus_StoreClearingParameters
     */
    public function getServiceTransactionStatusStoreClearingParameters()
    {
        /** @var $service Payone_Core_Model_Service_TransactionStatus_StoreClearingParameters */
        $service = Mage::getModel('payone_core/service_transactionStatus_storeClearingParameters');
        $service->setFactory($this);

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Transaction_Update
     */
    public function getServiceTransactionUpdate()
    {
        /**
         * @var $service Payone_Core_Model_Service_Transaction_Update
         */
        $service = Mage::getModel('payone_core/service_transaction_update');
        $service->setFactory($this);
        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Transaction_Create
     */
    public function getServiceTransactionCreate()
    {
        /**
         * @var $service Payone_Core_Model_Service_Transaction_Create
         */
        $service = Mage::getModel('payone_core/service_transaction_create');
        $service->setFactory($this);
        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_OrderStatus
     */
    public function getServiceSalesOrderStatus()
    {
        /**
         * @var $service Payone_Core_Model_Service_Sales_OrderStatus
         */
        $service = Mage::getModel('payone_core/service_sales_orderStatus');
        $service->setFactory($this);
        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_OrderComment
     */
    public function getServiceSalesOrderComment()
    {
        /**
         * @var $service Payone_Core_Model_Service_Sales_OrderComment
         */
        $service = Mage::getModel('payone_core/service_sales_orderComment');
        $service->setFactory($this);
        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Sales_InvoiceCreate
     */
    public function getServiceSalesInvoiceCreate()
    {
        /**
         * @var $service Payone_Core_Model_Service_Sales_InvoiceCreate
         */
        $service = Mage::getModel('payone_core/service_sales_invoiceCreate');
        $service->setFactory($this);
        return $service;
    }
    
    /**
     * @return Payone_Core_Model_Service_Sales_OrderConfirmation
     */
    public function getServiceSalesOrderConfirmation()
    {
        /**
         * @var $service Payone_Core_Model_Service_Sales_OrderConfirmation
         */
        $service = Mage::getModel('payone_core/service_sales_orderConfirmation');
        $service->setFactory($this);
        return $service;
    }
    

    /**
     * @return Payone_Settings_Service_XmlGenerate
     */
    public function getServiceXmlGenerate()
    {
        $service = $this->getBuilder()->buildServiceSettingsXmlGenerate();
        return $service;
    }


    /**
     * @return Payone_Core_Model_Service_Config_PaymentMethod_Create
     */
    public function getServiceConfigPaymentMethodCreate()
    {
        /** @var $service Payone_Core_Model_Service_Config_PaymentMethod_Create */
        $service = Mage::getModel('payone_core/service_config_paymentMethod_create');
        $service->setFactory($this);

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Config_XmlGenerate
     */
    public function getServiceConfigXmlGenerate()
    {
        /** @var $service Payone_Core_Model_Service_Config_XmlGenerate */
        $service = Mage::getModel('payone_core/service_config_xmlGenerate');
        $service->setFactory($this);

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Config_ProtectCheck
     */
    public function getServiceConfigProtectCheck()
    {
        /** @var $service Payone_Core_Model_Service_Config_ProtectCheck */
        $service = Mage::getModel('payone_core/service_config_protectCheck');
        $service->setFactory($this);

        return $service;
    }

    /**
     * @return Payone_Core_Model_Service_Protocol_Api_Export
     */
    public function getServiceProtocolApiExport()
    {
        return Mage::getModel('payone_core/service_protocol_api_export');
    }

    /**
     * @return Payone_Core_Model_Service_Protocol_TransactionStatus_Export
     */
    public function getServiceProtocolTransactionStatusExport()
    {
        return Mage::getModel('payone_core/service_protocol_transactionStatus_export');
    }

    /**
     * @return Payone_Core_Model_Domain_Config_PaymentMethod
     */
    public function getModelDomainConfigPaymentMethod()
    {
        /** @var $model Payone_Core_Model_Domain_Config_PaymentMethod */
        $model = Mage::getModel('payone_core/domain_config_paymentMethod');

        return $model;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getModelConfigPaymentMethod()
    {
        /** @var $model Payone_Core_Model_Config_Payment_Method_Interface */
        $model = Mage::getModel('payone_core/config_payment_method');

        return $model;
    }

    /**
     * @return Payone_Core_Model_Domain_Transaction
     */
    public function getModelTransaction()
    {
        /** @var $model Payone_Core_Model_Domain_Transaction */
        $model = Mage::getModel('payone_core/domain_transaction');

        return $model;
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_TransactionStatus
     */
    public function getModelTransactionStatus()
    {
        /** @var $model Payone_Core_Model_Domain_Protocol_TransactionStatus */
        $model = Mage::getModel('payone_core/domain_protocol_transactionStatus');

        return $model;
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_Api
     */
    public function getModelApi()
    {
        /** @var $model Payone_Core_Model_Domain_Protocol_Api */
        $model = Mage::getModel('payone_core/domain_protocol_api');

        return $model;
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getModelSalesOrder()
    {
        /** @var $model Mage_Sales_Model_Order */
        $model = Mage::getModel('sales/order');

        return $model;
    }

    /**
     * @return Mage_Sales_Model_Quote
     */
    public function getModelSalesQuote()
    {
        /** @var $model Mage_Sales_Model_Quote */
        $model = Mage::getModel('sales/quote');

        return $model;
    }

    /**
     * @return Mage_Sales_Model_Order_Invoice
     */
    public function getModelSalesOrderInvoice()
    {
        /** @var $model Mage_Sales_Model_Order_Invoice */
        $model = Mage::getModel('sales/order_invoice');
        return $model;
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    public function getModelCustomer()
    {
        /** @var $model Mage_Customer_Model_Customer */
        $model = Mage::getModel('customer/customer');

        return $model;
    }

    /**
     * @return Mage_Customer_Model_Entity_Customer
     */
    public function getSingletonCustomerResource()
    {
        /** @var Mage_Customer_Model_Entity_Customer $resource */
        $resource = Mage::getResourceSingleton('customer/customer');

        return $resource;
    }

    /**
     * @return Mage_Customer_Model_Address
     */
    public function getModelCustomerAddress()
    {
        /** @var $model Mage_Customer_Model_Address*/
        $model = Mage::getModel('customer/address');

        return $model;
    }

    /**
     * @return Mage_Core_Model_Store
     */
    public function getModelCoreStore()
    {
        return Mage::getModel('core/store');
    }

    /**
     * @return Mage_Tax_Model_Calculation
     */
    public function getSingletonTaxCalculation()
    {
        return Mage::getModel('tax/calculation');
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    public function getModelCoreWebsite()
    {
        return Mage::getModel('core/website');
    }

    /**
     * @return Mage_Checkout_Model_Session
     */
    public function getSingletonCheckoutSession()
    {
        /** @var $session Mage_Checkout_Model_Session */
        $session = Mage::getSingleton('checkout/session');

        return $session;
    }

    /**
     * @return Mage_Core_Model_Session
     */
    public function getSingletonCoreSession()
    {
        /** @var $session Mage_Core_Model_Session */
        $session = Mage::getSingleton('core/session');

        return $session;
    }

    /**
     * @return Mage_Payment_Model_Config
     */
    public function getSingletonPaymentConfig()
    {
        return Mage::getSingleton('payment/config');
    }

    /**
     * @param Payone_Builder $builder
     */
    public function setBuilder(Payone_Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return Payone_Builder
     */
    protected function getBuilder()
    {
        if ($this->builder === null) {
            $config = $this->getConfig();
            $this->builder = new Payone_Builder($config);
        }

        return $this->builder;
    }

    /**
     * @param Payone_Config $config
     */
    public function setConfig(Payone_Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_Config
     */
    protected function getConfig()
    {
        if ($this->config === null) {
            // Default config:
            $this->config = new Payone_Config();

            // Set Magento logger configuration:
            $this->config->setValue('api/default/protocol/loggers', $this->getConfigApiLogger());
            $this->config->setValue('transaction_status/default/protocol/loggers', $this->getConfigTransactionStatusLogger());
            $this->config->setValue('transaction_status/validator/proxy/enabled', $this->getConfigProxyMode());
            if($this->helper()->isCompilerEnabled())
            {
                $this->config->setValue('api/default/mapper/currency/currency_properties', $this->getLibCurrencyProperties());
            }
        }

        return $this->config;
    }

    /**
     * @return string
     */
    protected function getLibCurrencyProperties()
    {
        return Mage::getBaseDir('lib') . DIRECTORY_SEPARATOR . 'Payone' . DIRECTORY_SEPARATOR . 'Api' . DIRECTORY_SEPARATOR . 'Mapper' . DIRECTORY_SEPARATOR . 'currency.properties';
    }


    /**
     * @return array
     */
    protected function getConfigApiLogger()
    {
        $options = array(
            'filename' => Mage::getBaseDir('log') . DIRECTORY_SEPARATOR . 'payone_api.log',
            'max_file_size' => '1MB',
            'max_file_count' => 20,);

        $config = array('Payone_Protocol_Logger_Log4php' => $options);

        return $config;

    }

    /**
     * @return array
     */
    protected function getConfigTransactionStatusLogger()
    {
        $options = array(
            'filename' => Mage::getBaseDir('log') . DIRECTORY_SEPARATOR . 'payone_transactionstatus.log',
            'max_file_size' => '1MB',
            'max_file_count' => 20,);

        $config = array('Payone_Protocol_Logger_Log4php' => $options);

        return $config;
    }

    /**
     * @return int
     */
    protected function getConfigProxyMode()
    {
        $configMisc = $this->helperConfig()->getConfigMisc();
        $transactionStatusProcessing = $configMisc->getTransactionstatusProcessing();
        return $transactionStatusProcessing->getProxyMode();
    }

    /**
     * @return Mage_Cron_Model_Schedule
     */
    public function getModelCronSchedule()
    {
        return Mage::getModel('cron/schedule');
    }

    /**
     * @return Payone_Core_Model_System_Config_TransactionStatus
     */
    public function getModelSystemConfigTransactionStatus()
    {
        return Mage::getSingleton('payone_core/system_config_transactionStatus');
    }

    /**
     * @return Payone_Core_Model_System_Config_StatusTransaction
     */
    public function getModelSystemConfigStatusTransaction()
    {
        return Mage::getSingleton('payone_core/system_config_statusTransaction');
    }

    /**
     * @return Payone_Core_Model_System_Config_TranslationMonths
     */
    public function getModelSystemConfigTranslationMonths()
    {
        return Mage::getSingleton('payone_core/system_config_translationMonths');
    }
    
    /**
     * @return Payone_Core_Model_System_Config_TranslationErrors
     */
    public function getModelSystemConfigTranslationErrors()
    {
        return Mage::getSingleton('payone_core/system_config_translationErrors');
    }
    
    /**
     * @return Payone_Core_Model_System_Config_TranslationPlaceholders
     */
    public function getModelSystemConfigTranslationPlaceholders()
    {
        return Mage::getSingleton('payone_core/system_config_translationPlaceholders');
    }

    /**
     * @return Payone_Core_Model_System_Config_ResponseType
     */
    public function getModelSystemConfigResponseType()
    {
        return Mage::getSingleton('payone_core/system_config_responseType');
    }

    /**
     * @return Payone_Core_Model_System_Config_RequestType
     */
    public function getModelSystemConfigRequestType()
    {
        $model = Mage::getSingleton('payone_core/system_config_requestType');
        return $model;
    }

    /**
     * @return Payone_Core_Model_System_Config_PersonStatus
     */
    public function getModelSystemConfigPersonStatus()
    {
        return Mage::getSingleton('payone_core/system_config_personStatus');
    }

    /**
     * @return Payone_Core_Model_System_Config_CreditScore
     */
    public function getModelSystemConfigCreditScore()
    {
        return Mage::getSingleton('payone_core/system_config_creditScore');
    }

    /**
     * @return Mage_Adminhtml_Model_System_Config_Source_Locale
     */
    public function getModelSystemConfigLocale()
    {
        return Mage::getSingleton('adminhtml/system_config_source_locale');
    }

    
    /**
     * @return Mage_Adminhtml_Model_System_Config_Source_Order_Status
     */
    public function getModelSystemConfigOrderStatus()
    {
        return Mage::getSingleton('adminhtml/system_config_source_order_status');
    }

    /**
     * @return Mage_Adminhtml_Model_System_Config_Source_Country_Full
     */
    public function getModelSystemConfigCountryFull()
    {
        return Mage::getSingleton('adminhtml/system_config_source_country_full');
    }

    /**
     * @return Mage_Adminhtml_Model_System_Config_Source_Shipping_Allmethods
     */
    public function getModelSystemConfigShippingMethod()
    {
        return Mage::getSingleton('adminhtml/system_config_source_shipping_allmethods');
    }

    /**
     * @return Payone_Core_Model_System_Config_ClearingType
     */
    public function getModelSystemConfigClearingType()
    {
        return Mage::getSingleton('payone_core/system_config_clearingType');
    }
    
    /**
     * @return Payone_Core_Model_System_Config_PaymentFeeType
     */
    public function getModelSystemConfigPaymentFeeType()
    {
        return Mage::getSingleton('payone_core/system_config_paymentFeeType');
    }

    /**
     * @return Payone_Core_Model_System_Config_Mode
     */
    public function getModelSystemConfigMode()
    {
        return Mage::getSingleton('payone_core/system_config_mode');
    }

    /**
     * @return Payone_Core_Model_System_Config_ReminderLevel
     */
    public function getModelSystemConfigReminderLevel()
    {
        return Mage::getSingleton('payone_core/system_config_reminderLevel');
    }

    /**
     * @return Payone_Core_Model_System_Config_Status
     */
    public function getModelSystemConfigStatus()
    {
        return Mage::getSingleton('payone_core/system_config_status');
    }

    /**
     * @return Payone_Core_Model_System_Config_PaymentMethodType
     */
    public function getModelSystemConfigPaymentMethodType()
    {
        return Mage::getSingleton('payone_core/system_config_paymentMethodType');
    }

    /**
     * @return Payone_Core_Model_System_Config_CreditCardType
     */
    public function getModelSystemConfigCreditCardType()
    {
        return Mage::getSingleton('payone_core/system_config_creditCardType');
    }

    /**
     * @return Payone_Core_Model_System_Config_OnlinebanktransferType
     */
    public function getModelSystemConfigOnlinebanktransferType()
    {
        return Mage::getSingleton('payone_core/system_config_onlinebanktransferType');
    }
    
    /**
     * @return Payone_Core_Model_System_Config_PayolutionType
     */
    public function getModelSystemConfigPayolutionType()
    {
        return Mage::getSingleton('payone_core/system_config_payolutionType');
    }

    /**
     * @return Payone_Core_Model_System_Config_WalletType
     */
    public function getModelSystemConfigWalletType()
    {
        return Mage::getSingleton('payone_core/system_config_walletType');
    }

    /**
     * @return Payone_Core_Model_System_Config_RatepayType
     */
    public function getModelSystemConfigRatePayType()
    {
        return Mage::getSingleton('payone_core/system_config_ratepayType');
    }

    /**
     * @return Payone_Core_Model_System_Config_SafeInvoiceType
     */
    public function getModelSystemConfigSafeInvoiceType()
    {
        return Mage::getSingleton('payone_core/system_config_safeInvoiceType');
    }

    /**
     * @return Payone_Core_Model_System_Config_AddressCheckType
     */
    public function getModelSystemConfigAddressCheckType()
    {
        return Mage::getSingleton('payone_core/system_config_addressCheckType');
    }

    /**
     * @return Payone_Core_Model_System_Config_AuthorizeMethod
     */
    public function getModelSystemConfigAuthorizeMethod()
    {
        return Mage::getSingleton('payone_core/system_config_authorizeMethod');
    }

    /**
     * @return Payone_Core_Model_System_Config_AvsResult
     */
    public function getModelSystemConfigAvsResult()
    {
        return Mage::getSingleton('payone_core/system_config_avsResult');
    }

    /**
     * @return Payone_Core_Model_System_Config_CreditratingChecktype
     */
    public function getModelSystemConfigCreditratingChecktype()
    {
        return Mage::getSingleton('payone_core/system_config_creditratingChecktype');
    }

    /**
     * @return Payone_Core_Model_System_Config_PaymentMethodCode
     */
    public function getModelSystemConfigPaymentMethodCode()
    {
        return Mage::getSingleton('payone_core/system_config_paymentMethodCode');
    }

    /**
     * @return Payone_Core_Model_System_Config_MethodType
     */
    public function getModelSystemConfigMethodType()
    {
        return Mage::getSingleton('payone_core/system_config_methodType');
    }

    /**
     * @return Payone_Core_Model_System_Config_KlarnaCountry
     */
    public function getModelSystemConfigKlarnaCountry()
    {
        return Mage::getSingleton('payone_core/system_config_klarnaCountry');
    }

    /**
     * @return Mage_Core_Model_Resource_Transaction
     */
    public function getModelResourceTransaction()
    {
        return Mage::getModel('core/resource_transaction');
    }

    /**
     * @return Mage_Core_Model_Email_Template
     */
    public function getModelEmailTemplate()
    {
        return Mage::getModel('core/email_template');
    }

    /**
     * @return Mage_Eav_Model_Entity_Type
     */
    public function getModelEavEntityType()
    {
        return Mage::getModel('eav/entity_type');
    }

    /**
     * @return Varien_Http_Client
     */
    public function getModelVarienHttpClient()
    {
        return new Varien_Http_Client();
    }

    /**
     * @return Mage_Core_Model_Config_Data
     */
    public function getModelCoreConfigData()
    {
        /** @var $configData Mage_Core_Model_Config_Data */
        $configData = Mage::getModel('core/config_data');
        return $configData;
    }
}