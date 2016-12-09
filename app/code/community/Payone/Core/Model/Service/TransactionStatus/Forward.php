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
class Payone_Core_Model_Service_TransactionStatus_Forward extends Payone_Core_Model_Service_Abstract
{
    const DEFAULT_TIMEOUT = 15;
    /**
     * @var Payone_Core_Model_Config_Misc_TransactionstatusForwarding
     */
    protected $configForwarding = null;

    /**
     * @var Varien_Http_Client
     */
    protected $httpClient = null;

    protected $allowedKeys = array(
        'txid',
        'txtime',
        'reference',
        'key',
        'txaction',
        'mode',
        'mid',
        'aid',
        'portalid',
        'clearingtype',
        'sequencenumber',
        'balance',
        'receivable',
        'failedcause',
        'currency',
        'userid',
        'customerid',
        'param',
        'productid',
        'accessid',
        'reminderlevel',
        'invoiceid',
        'invoice_grossamount',
        'invoice_date',
        'invoice_deliverydate',
        'invoice_deliveryenddate',
        'vaid',
        'vreference',
        'vxid',
    );

    /**
     * @var array
     */
    protected $exceptions = array();

    /**
     * @param Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
     * @return bool
     */
    public function forward(Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus)
    {
        $currentTxAction = $transactionStatus->getTxaction();
        $config = $this->getConfigForwarding()->getConfig($currentTxAction);

        foreach ($config as $configHost) {
            $response = $this->forwardToHost($configHost, $transactionStatus);
        }

        if ($this->hasExceptions()) {
            return false;
        }
        return true;
    }

    public function forwardToHost(
        array $configHost,
        Payone_Core_Model_Domain_Protocol_TransactionStatus $transactionStatus
    )
    {
        $url = '';
        $response = '';

        try {
            if (!array_key_exists('url', $configHost)) {
                throw new Payone_Core_Exception_TransactionStatusForward('Missing Url');
            }
            $url = $configHost['url'];

            $timeout = self::DEFAULT_TIMEOUT;
            if (array_key_exists('timeout', $configHost)) {
                $timeout = $configHost['timeout'];
            }

            $parameters = $transactionStatus->toArray($this->allowedKeys);

            $configClient = array(
                'timeout' => $timeout,
            );

            $httpClient = $this->getHttpClient();
            $httpClient->setUri($url);
            $httpClient->setConfig($configClient);
            $httpClient->setMethod(Varien_Http_Client::POST);
            $httpClient->setParameterPost($parameters);
            $response = $httpClient->request();

            if ($response !== 'TSOK') {
                throw new Payone_Core_Exception_TransactionStatusForward($response);
            }
        } catch (Exception $e) {
            $this->addForwardException($url, $e);
        }
        return $response;
    }

    /**
     * @param Payone_Core_Model_Config_Misc_TransactionstatusForwarding $config
     */
    public function setConfigForwarding(Payone_Core_Model_Config_Misc_TransactionstatusForwarding $config)
    {
        $this->configForwarding = $config;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_TransactionstatusForwarding
     */
    public function getConfigForwarding()
    {
        return $this->configForwarding;
    }

    /**
     * @param string $url
     * @param Exception $e
     */
    protected function addForwardException($url, Exception $e)
    {
        $this->exceptions[$url] = $e;
    }

    /**
     * @return bool
     */
    public function hasExceptions()
    {
        if (count($this->getExceptions())) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param Varien_Http_Client $httpClient
     */
    public function setHttpClient(Varien_Http_Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Varien_Http_Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

}