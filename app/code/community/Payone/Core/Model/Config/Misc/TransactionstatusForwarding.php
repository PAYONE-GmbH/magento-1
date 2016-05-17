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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Config_Misc_TransactionstatusForwarding
    extends Payone_Core_Model_Config_AreaAbstract
{
    const DEFAULT_TIMEOUT = 15;

    /**
     * @var array
     */
    protected $config = array();

    public function init(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key == 'config' and is_string($value)) {
                $value = $this->initConfig($value);
            }
            $this->setValue($key, $value);
        }
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if (count($this->getConfig()) > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param string $txaction
     * @return bool
     */
    public function canForwardTxAction($txaction)
    {
        if (array_key_exists($txaction, $this->getConfig())) {
            return true;
        }
        return false;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param string $txaction
     * @return array
     */
    public function getConfig($txaction = '')
    {
        if($txaction != ''){
            if($this->canForwardTxAction($txaction)){
                return $this->config[$txaction];
            }
        }
        return $this->config;
    }

    /**
     * @param $value
     * @return array
     */
    public function initConfig($value)
    {
        $return = array();
        $raw = unserialize($value);
        if (!is_array($raw)) {
            return null;
        }

        foreach ($raw as $configSet) {
            if (!array_key_exists('url', $configSet)) {
                // Without URL we cannot forward => config is ignored
                continue;
            }
            $url = $configSet['url'];

            if (!array_key_exists('txactions', $configSet)) {
                // Without actions we cannot forward => config is ignored
                continue;
            }
            $txactions = $configSet['txactions'];
            if (!count($txactions)) {
                // Without actions we cannot forward => config is ignored
                continue;
            }

            $timeout = self::DEFAULT_TIMEOUT;
            if (array_key_exists('timeout', $configSet)) {
                $timeout = $configSet['timeout'];
            }

            // All mandatory parameters could be found, now init txactions
            $actionConfig = array(
                'url' => $url,
                'timeout' => $timeout,
            );

            foreach ($txactions as $txaction) {
                if (!array_key_exists($txaction, $return)) {
                    $return[$txaction] = array();
                }
                array_push($return[$txaction], $actionConfig);
            }
        }
        return $return;
    }

    /**
     * @internal param $value
     * @return array
     */
    public function getConfigSortedByUrl()
    {
        $return = array();

        foreach ($this->getConfig() as $status => $statusArray) {

            foreach ($statusArray as $urlArray) {
                $url = $urlArray['url'];
                $timeout = $urlArray['timeout'];

                if(!array_key_exists($url,$return))
                {
                    $return[$url] = array();
                    $return[$url]['timeout'] =  $timeout;
                }
                if(!array_key_exists('status',$return[$url]))
                {
                    $return[$url]['status'] = array();
                }
                array_push($return[$url]['status'],$status);

            }

        }

        return $return;
    }
}
