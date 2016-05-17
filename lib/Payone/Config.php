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
 * Configuration for Payone SDK
 *
 * <b>Example: Replacing the default logging mechanism for Payone Api</b>
 * <pre  class="prettyprint">
 * $config = new Payone_Config();
 *
 * // Array with classname of logger to use as key, options array as value:
 * $myLoggers = array(
 *      'Payone_Protocol_Logger_Log4php' => array(
 *          'filename' => 'my_path/my_logfile.log',
 *          'max_file_size' => '50MB',
 *          'max_file_count' => 10));
 *
 * $config->setValue('api/default/protocol/loggers/', $myLoggers);
 * </pre>
 *
 * <b>Example: Adding an additional logger for Payone TransactionStatus</b>
 * <pre  class="prettyprint">
 * $config = new Payone_Config();
 *
 *  // options array:
 * $myLogger = array(
 *          'filename' => 'my_path/my_logfile.log',
 *          'max_file_size' => '50MB',
 *          'max_file_count' => 10));
 *
 *
 * $config->setValue('transaction_status/default/protocol/loggers/My_Logger_Class', $myLogger); *
 * </pre>
 *
 *
 * <b>Example: Changing the target log file for all logging activities</b>
 * <pre  class="prettyprint">
 *
 * // Initiate default config:
 * $config = new Payone_Config();
 *
 * // Change the log file only:
 * $config->setValue('api/default/protocol/loggers/Payone_Protocol_Logger_Log4php/filename', 'my_file.log'); *
 * </pre>
 *
 * @category        Payone
 * @package         Payone
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Config
{
    const KEY_SEPARATOR = '/';
    /** @var array */
    protected $config = array();

    /** @var Payone_Api_Config */
    protected $apiConfig = null;

    /** @var Payone_TransactionStatus_Config */
    protected $transactionStatusConfig = null;

    /** @var Payone_SessionStatus_Config */
    protected $sessionStatusConfig = null;

    /**
     * @constructor
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        if (empty($data)) {
            if($this->getApiConfig() === null)
            {
                $this->apiConfig = new Payone_Api_Config();
            }
            if($this->getTransactionStatusConfig() === null)
            {
                $this->transactionStatusConfig = new Payone_TransactionStatus_Config();
            }
            if($this->getSessionStatusConfig() === null)
            {
                $this->sessionStatusConfig = new Payone_SessionStatus_Config();
            }
            $this->config = $this->getDefaultConfigData();
        }
        else {
            if(array_key_exists('api', $data))
                $this->setApiConfig($data['api']);
            if(array_key_exists('transaction_status',$data))
                $this->setTransactionStatusConfig($data['transaction_status']);
            if(array_key_exists('session_status',$data))
                $this->setSessionStatusConfig($data['session_status']);
            $this->config = $data;
        }
    }

    /**
     * Retrieve a value from the config
     * @param string $key Config key in the form 'node/node/node'
     * @return mixed
     */
    public function getValue($key)
    {
        return $this->get($key, $this->config);
    }

    /**
     * @param string $key Config key in the form 'node/node/node'
     * @param mixed $value Config to set
     */
    public function setValue($key, $value)
    {
        $this->set($key, $value, $this->config);
    }

    /**
     * @return array
     */
    protected function getDefaultConfigData()
    {
        $configData = array(
            'api' => $this->getApiConfig(),
            'transaction_status' => $this->getTransactionStatusConfig(),
            'session_status' => $this->getSessionStatusConfig()
        );

        return $configData;
    }

    /**
     * @param string $key  Key in the form 'something/something'
     * @param mixed $value The value to set, can be any type
     * @param array $tree
     *
     * @return bool TRUE on Success
     */
    protected function set($key, $value, array &$tree)
    {
        if (strpos($key, self::KEY_SEPARATOR) !== FALSE
            // and is_array($tree)
        ) {
            // Disassemble key, extracting the first node of the string:
            $explodedKey = explode(self::KEY_SEPARATOR, $key);
            $currentKey = array_shift($explodedKey);
            $newKey = implode(self::KEY_SEPARATOR,$explodedKey);

            /** @var $object Payone_Config_Abstract  */
            $object = $tree[$currentKey];
            $object->setValue($newKey,$value);
            return TRUE;
        }
        else {
            // Set value (can overwrite an existing value)
            $tree[$key] = $value;
            // Exit recursion, Success!
            return TRUE;
        }
    }

    /**
     * Recursively read from a nested array with a key/path
     * If a non-existant key is given, NULL will be returned.
     * Retrieving sub-trees is possible as well.
     *
     * Example:
     * get('root/node/node', array($root => array($node => array($node => 'value'))))
     * will return 'value'
     *
     * @recursive
     *
     * @param $key
     * @param array|mixed$tree An array, or, if recursively called, a leaf of the treef
     * @return mixed
     */
    protected function get($key, $tree)
    {
        if (strpos($key, self::KEY_SEPARATOR) !== FALSE and is_array($tree)) {
            // Disassemble key, extracting the first node of the string:
            $explodedKey = explode(self::KEY_SEPARATOR, $key);
            $currentKey = array_shift($explodedKey);
            $newKey = implode(self::KEY_SEPARATOR,$explodedKey);

            /** @var $object Payone_Config_Abstract */
            $object = $tree[$currentKey];
            return $object->getValue($newKey);
        }
        elseif (is_array($tree) and array_key_exists($key, $tree)) {
            return $tree[$key]; // Exit recursion, Success!
        }
        else {
            return NULL; // Exit recursion, unsuccessful
        }
    }

    /**
     * @param Payone_Api_Config $apiConfig
     */
    public function setApiConfig($apiConfig)
    {
        $this->apiConfig = $apiConfig;
    }

    /**
     * @return Payone_Api_Config
     */
    public function getApiConfig()
    {
        return $this->apiConfig;
    }

    /**
     * @param Payone_TransactionStatus_Config $transactionStatusConfig
     */
    public function setTransactionStatusConfig($transactionStatusConfig)
    {
        $this->transactionStatusConfig = $transactionStatusConfig;
    }

    /**
     * @return Payone_TransactionStatus_Config
     */
    public function getTransactionStatusConfig()
    {
        return $this->transactionStatusConfig;
    }

    /**
     * @param Payone_SessionStatus_Config $sessionStatusConfig
     */
    public function setSessionStatusConfig($sessionStatusConfig)
    {
        $this->sessionStatusConfig = $sessionStatusConfig;
    }

    /**
     * @return Payone_SessionStatus_Config
     */
    public function getSessionStatusConfig()
    {
        return $this->sessionStatusConfig;
    }
}
