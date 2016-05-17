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
 * @package         Payone_Protocol
 * @subpackage      Logger
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 * This class is a default Implementation for Log4php
 *
 * it is used to Log Messages to files and is injected by default
 *
 * <pre  class="prettyprint">
 * $config = array(
'filename' => 'payone/exception.log',
'max_file_size' => '500KB',
'max_file_count' => 10,
);
 * $logger = new Payone_Protocol_Logger_Log4php();
 * $logger->log('MESSAGE');
 * </pre>
 *
 * @category        Payone
 * @package         Payone_Protocol
 * @subpackage      Logger
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Protocol_Logger_Log4php
    implements Payone_Protocol_Logger_Interface
{
    const KEY = 'p1_log4php';
    const LOGGER_APPENDER_NAME = 'Payone_Logger_Log4php_File';

    /** @var Payone_Log4php_Logger */
    protected $logger = null;

    /** @var string */
    protected $key = self::KEY;

    /** @var array */
    protected $config = array(
        'filename' => '',
        'max_file_size' => '1MB',
        'max_file_count' => 20,
    );

    /**
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        if (count($config)) {
            $this->setConfig(array_merge($this->getConfig(), $config));
        }
    }

    /**
     * @param $message
     * @param string $level
     * @return boolean
     */
    public function log($message, $level = self::LEVEL_INFO)
    {
        $fileName = $this->getConfigValue('filename');
        if (empty($fileName)) {
            return FALSE;
        }

        $logger = $this->getLogger();

        switch ($level) {
            case self::LEVEL_ERROR :
                $logger->error($message);
                break;
            case self::LEVEL_INFO :
            default :
                $logger->info($message);
                break;
        }

        return TRUE;
    }

    /**
     * Constructs a default logger, logging to a default file
     *
     * @return Payone_Log4php_Logger
     */
    protected function buildLoggerDefault()
    {
        $logger = Payone_Log4php_Logger::getLogger(self::KEY);

        return $logger;
    }

    /**
     * Build a config that the Log4php_Logger accepts
     * @return array
     */
    protected function buildInternalConfig()
    {
        $fileName = $this->getConfigValue('filename');
        $fileSize = $this->getConfigValue('max_file_size');
        $fileCount = $this->getConfigValue('max_file_count');

        $internalConfig = array(
            'appenders' => array(
                self::LOGGER_APPENDER_NAME => array(
                    'name' => self::LOGGER_APPENDER_NAME,
                    'class' => 'Payone_Log4php_LoggerAppenderRollingFile',
                    'layout' => array(
                        'class' => 'Payone_Log4php_LoggerLayoutTTCC',
                    ),
                    'params' => array(
                        'file' => $fileName,
                        'append' => TRUE,
                        'maxFileSize' => $fileSize,
                        'maxBackupIndex' => $fileCount,
                    ),
                ),
            ),
            'rootLogger' => array(
                'level' => Payone_Log4php_LoggerLevel::getLevelAll(),
                'appenders' => array(self::LOGGER_APPENDER_NAME),
            ),
        );


        return $internalConfig;

    }

    /**
     * @param Payone_Log4php_Logger $logger
     */
    public function setLogger(Payone_Log4php_Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Gets the internal Log4php_Logger, configured
     * @return Payone_Log4php_Logger
     */
    public function getLogger()
    {
        if ($this->logger === null) {
            $this->logger = $this->buildLoggerDefault();
        }
        $internalConfig = $this->buildInternalConfig();
        $this->logger->configure($internalConfig);
        return $this->logger;
    }


    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getConfigValue($key = '')
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setConfigValue($key, $value)
    {
        $this->config[$key] = $value;
    }
}
