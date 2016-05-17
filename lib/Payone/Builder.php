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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Builder {

    const KEY_API = 'api';
    const KEY_CLIENTAPI = 'client_api';
    const KEY_PROTOCOL = 'protocol';
    const KEY_SETTINGS = 'settings';
    const KEY_TRANSACTIONSTATUS = 'transaction_status';
    const KEY_SESSIONSTATUS = 'session_status';

    /** @var array */
    protected $factories = array();

    /** @var Payone_Config */
    protected $config = null;

    /**
     * @constructor
     * @param null|Payone_Config $config config can be set via constructor or setConfig()
     */
    public function __construct(Payone_Config $config = null) {
        if ($config === null) {
            $config = new Payone_Config(); // Default config
        }
        $this->config = $config;

        $this->factories[self::KEY_API] = new Payone_Api_Factory($config->getApiConfig());
        $this->factories[self::KEY_CLIENTAPI] = new Payone_ClientApi_Factory();
        $this->factories[self::KEY_PROTOCOL] = new Payone_Protocol_Factory();
        $this->factories[self::KEY_SETTINGS] = new Payone_Settings_Factory();
        $this->factories[self::KEY_TRANSACTIONSTATUS] = new Payone_TransactionStatus_Factory($config->getTransactionStatusConfig());
        $this->factories[self::KEY_SESSIONSTATUS] = new Payone_SessionStatus_Factory($config->getSessionStatusConfig());
    }

    /**
     * @api
     *
     * @return Payone_ClientApi_Service_GenerateHash
     */
    public function buildServiceClientApiGenerateHash() {
        return $this->buildService(self::KEY_CLIENTAPI . '/generateHash');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Payment_Authorize
     */
    public function buildServicePaymentAuthorize() {
        return $this->buildService(self::KEY_API . '/payment/authorize');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Payment_Preauthorize
     */
    public function buildServicePaymentPreauthorize() {
        return $this->buildService(self::KEY_API . '/payment/preauthorize');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Payment_Capture
     */
    public function buildServicePaymentCapture() {
        return $this->buildService(self::KEY_API . '/payment/capture');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Payment_Debit
     */
    public function buildServicePaymentDebit() {
        return $this->buildService(self::KEY_API . '/payment/debit');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Payment_Refund
     */
    public function buildServicePaymentRefund() {
        return $this->buildService(self::KEY_API . '/payment/refund');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Verification_3dsCheck
     */
    public function buildServiceVerification3dsCheck() {
        return $this->buildService(self::KEY_API . '/verification/3dscheck');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Management_GetInvoice
     */
    public function buildServiceManagementGetInvoice() {
        return $this->buildService(self::KEY_API . '/management/getInvoice');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Management_GetFile
     */
    public function buildServiceManagementGetFile() {
        return $this->buildService(self::KEY_API . '/management/getFile');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Management_ManageMandate
     */
    public function buildServiceManagementManageMandate() {
        return $this->buildService(self::KEY_API . '/management/manageMandate');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Verification_AddressCheck
     */
    public function buildServiceVerificationAddressCheck() {
        return $this->buildService(self::KEY_API . '/verification/addressCheck');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Verification_CreditCardCheck
     */
    public function buildServiceVerificationCreditCardCheck() {
        return $this->buildService(self::KEY_API . '/verification/creditCardCheck');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Verification_BankAccountCheck
     */
    public function buildServiceVerificationBankAccountCheck() {
        return $this->buildService(self::KEY_API . '/verification/bankAccountCheck');
    }

    /**
     * @api
     *
     * @return Payone_Api_Service_Verification_Consumerscore
     */
    public function buildServiceVerificationConsumerscore() {
        return $this->buildService(self::KEY_API . '/verification/consumerscore');
    }

    /**
     * @api
     *
     * @return Payone_Settings_Service_XmlGenerate
     */
    public function buildServiceSettingsXmlGenerate() {
        return $this->buildService(self::KEY_SETTINGS . '/xmlgenerate');
    }

    /**
     * Service to start the paypal express checkout and
     * in step two get customers shipping address from paypal.
     * usage:
     * $builder = $this->getPayoneBuilder();
     * $service = $builder->buildServicePaymentGenericpayment();
     * $response = $service->request($request);
     * 
     * @api
     * @return Payone_Api_Service_Payment_Genericpayment
     */
    public function buildServicePaymentGenericpayment() {
        return $this->buildService(self::KEY_API . '/payment/genericpayment');
    }

    /**
     * @api
     * @param $key
     * @param array $validIps
     * @return Payone_TransactionStatus_Service_HandleRequest
     */
    public function buildServiceTransactionStatusHandleRequest($key, array $validIps) {
        /** @var $service Payone_TransactionStatus_Service_HandleRequest */
        $service = $this->buildService(self::KEY_TRANSACTIONSTATUS . '/handlerequest');
        $validators = $service->getValidators();

        foreach ($validators as $validator) {
            if ($validator instanceof Payone_TransactionStatus_Validator_DefaultParameters) {
                /** @var $validator Payone_TransactionStatus_Validator_DefaultParameters */
                $validator->setKey($key);
            } elseif ($validator instanceof Payone_TransactionStatus_Validator_Ip) {
                /** @var $validator Payone_TransactionStatus_Validator_Ip */
                $validator->setValidIps($validIps);
                $validator->setConfig($this->getConfig()->getTransactionStatusConfig());
            }
        }

        return $service;
    }

    /**
     * @api
     * @param $key
     * @param array $validIps
     * @return Payone_SessionStatus_Service_HandleRequest
     */
    public function buildServiceSessionStatusHandleRequest($key, array $validIps) {
        /** @var $service Payone_SessionStatus_Service_HandleRequest */
        $service = $this->buildService(self::KEY_SESSIONSTATUS . '/handlerequest');
        $validators = $service->getValidators();

        foreach ($validators as $validator) {
            if ($validator instanceof Payone_SessionStatus_Validator_DefaultParameters) {
                /** @var $validator Payone_SessionStatus_Validator_DefaultParameters */
                $validator->setKey($key);
            } elseif ($validator instanceof Payone_SessionStatus_Validator_Ip) {
                /** @var $validator Payone_SessionStatus_Validator_Ip */
                $validator->setValidIps($validIps);
                $validator->setConfig($this->getConfig()->getSessionStatusConfig());
            }
        }

        return $service;
    }

    /**
     * @param string $key Service key, e.g. "api/payment/authorize"
     * @return Payone_Api_Service_Payment_Abstract
     * @throws Exception
     */
    protected function buildService($key) {
        $config = $this->getConfig();

        $keyArray = explode('/', $key);
        $factoryKey = array_shift($keyArray);
        $serviceKey = implode('/', $keyArray);

        // Load required factory:
        $factory = $this->getFactory($factoryKey);

        $service = $factory->buildService($serviceKey);

        // Add Protocol Service, if required, with custom or default config:
        if (method_exists($service, 'setServiceProtocol')) {
            $protocolConfig = $config->getValue($factoryKey . '/' . $serviceKey . '/protocol');
            if ($protocolConfig == null) {
                $protocolConfig = $config->getValue($factoryKey . '/default/protocol');
            }
            $serviceProtocol = $this->buildServiceProtocol($protocolConfig, $factoryKey);
            $service->setServiceProtocol($serviceProtocol);
        }

        if (method_exists($service, 'setValidator')) {
            $validatorConfig = $config->getValue($factoryKey . '/' . $serviceKey . '/validator');
            if ($validatorConfig == null) {
                $validatorConfig = $config->getValue($factoryKey . '/default/validator');
            }
            $validator = $this->buildServiceValidation($validatorConfig);
            if ($validator !== null) {
                $service->setValidator($validator);
            }
        }

        if (method_exists($service, 'setValidators')) {
            $validatorConfig = $config->getValue($factoryKey . '/' . $serviceKey . '/validators');
            if ($validatorConfig == null) {
                $validatorConfig = $config->getValue($factoryKey . '/default/validators');
            }
            $validator = $this->buildServiceValidation($validatorConfig);
            if ($validator !== null) {
                $service->setValidators($validator);
            }
        }

        return $service;
    }

    /**
     * @param array $protocolConfig
     * @param string $factoryKey
     * @return Payone_Protocol_Service_Protocol_Abstract
     */
    protected function buildServiceProtocol(array $protocolConfig, $factoryKey) {
        $serviceProtocol = $this->getFactory($factoryKey)->buildServiceProtocolRequest();
        $serviceApplyFilters = $this->getFactory(self::KEY_PROTOCOL)->buildServiceApplyFilters();

        // Load filters by config:
        if (array_key_exists('filter', $protocolConfig)) {
            foreach ($protocolConfig['filter'] as $key => $options) {
                if ($options['enabled'] === TRUE || $options['enabled'] === 1) {
                    // @todo hs: un-elegant, kann man das hier ohne switch machen?
                    switch ($key) {
                        case Payone_Protocol_Filter_MaskValue::FILTER_KEY :
                            $filterMaskValue = new Payone_Protocol_Filter_MaskValue();
                            $filterMaskValue->setConfig('percent', $options['percent']);
                            $serviceApplyFilters->addFilter($filterMaskValue);
                            break;
                        case Payone_Protocol_Filter_MaskAllValue::FILTER_KEY :
                            $filterMaskAllValue = new Payone_Protocol_Filter_MaskAllValue();
                            $serviceApplyFilters->addFilter($filterMaskAllValue);
                            break;
                    }
                }
            }
        }
        $serviceProtocol->setServiceApplyFilters($serviceApplyFilters);

        if (array_key_exists('loggers', $protocolConfig)) {
            $loggerConfig = $protocolConfig['loggers'];
            if (is_array($loggerConfig) and count($loggerConfig) > 0) {
                foreach ($loggerConfig as $className => $options) {
                    if (class_exists($className)) {
                        /** @var $logger Payone_Protocol_Logger_Interface * */
                        $logger = new $className;
                        if (method_exists($logger, 'setConfig')) {
                            $logger->setConfig($options);
                        }
                        $serviceProtocol->addLogger($logger);
                    }
                }
            }
        }

        // @todo hs: repository section, for now as a separate array, could this be combined with the loggers?
        if (array_key_exists('repositories', $protocolConfig)) {
            $respositoryConfig = $protocolConfig['repositories'];
            if (is_array($respositoryConfig) and count($respositoryConfig) > 0) {
                foreach ($respositoryConfig as $className => $options) {
                    if (class_exists($className)) {
                        // @todo hs: what do we do with Payone_Api_Persistence_Interface?
                        /** @var $repository Payone_TransactionStatus_Persistence_Interface * */
                        $repository = new $className;
                        if (method_exists($repository, 'setConfig')) {
                            $repository->setConfig($options);
                        }
                        $serviceProtocol->addRepository($repository);
                    }
                }
            }
        }

        return $serviceProtocol;
    }

    /**
     * @param $validatorConfig
     * @return null|validator
     */
    protected function buildServiceValidation($validatorConfig) {
        if (is_array($validatorConfig)) {
            $validator = array();
            foreach ($validatorConfig as $config) {
                if ($config === 'default' or ! class_exists($config)) {
                    return null;
                } else {
                    $validator[] = new $config();
                }
            }

            return $validator;
        } else {
            // Load validator by config (if non-default):
            if ($validatorConfig === 'default' or ! class_exists($validatorConfig)) {
                return null;
            } else {
                $validator = new $validatorConfig();
                return $validator;
            }
        }
    }

    /**
     * @param \Payone_Config $config
     */
    public function setConfig(Payone_Config $config) {
        $this->config = $config;
    }

    /**
     * @return \Payone_Config
     */
    protected function getConfig() {
        return $this->config;
    }

    /**
     * @param $key
     * @return null|Payone_Api_Factory|Payone_Protocol_Factory|Payone_Settings_Factory|Payone_TransactionStatus_Factory
     * @throws Exception
     */
    protected function getFactory($key) {
        if (array_key_exists($key, $this->factories)) {
            return $this->factories[$key];
        } else {
            throw new Exception('Could not get internal factory with key "' . $key . '"');
        }
    }

}
