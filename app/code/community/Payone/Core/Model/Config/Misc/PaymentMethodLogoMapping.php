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
class Payone_Core_Model_Config_Misc_PaymentMethodLogoMapping
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
     * @param array $config
     */
    public function setConfig($config)
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
     * @param $value
     * @return array
     */
    public function initConfig($value)
    {
        $aMapping = array();
        $raw = unserialize($value);
        if (!is_array($raw)) {
            return null;
        }

        foreach ($raw as $key => $entry) {
            $aMethodDetails = explode('_', array_shift($entry['method']));
            $aMapping[$key] = array(
                'method' => isset($aMethodDetails[0]) ? $aMethodDetails[0] : '',
                'size' => array_shift($entry['size']),
                'type' => isset($aMethodDetails[1]) ? $aMethodDetails[1] : '',
                'logo_id' => array_shift($entry['logo'])
            );
        }

        return $aMapping;
    }
}
