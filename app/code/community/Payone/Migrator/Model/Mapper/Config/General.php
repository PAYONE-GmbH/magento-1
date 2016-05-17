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
class Payone_Migrator_Model_Mapper_Config_General extends Payone_Migrator_Model_Mapper_Abstract
{
    const CONFIG_PAYONE_GENERAL_GLOBAL = 'payone_general/global/';
    const CONFIG_PAYONE_GENERAL_PARAMETER_INVOICE = 'payone_general/parameter_invoice/';

    protected $mappingArray = array(
        'allowspecific' => 'allowspecific',
        'merchant_id' => 'mid',
        'portal_id' => 'portalid',
        'account_id' => 'aid',
        'security_key' => 'key',
        'request_type' => 'request_type',
        'submit_products' => 'transmit_enabled'
    );

    public function mapConfigData($configDataMethod)
    {
        /** @var $configDataMethod Mage_Core_Model_Config_Data */
        $configData = $this->getFactory()->getModelCoreConfigData();

        // Is path to be mapped?
        $path = $this->mapPath($configDataMethod->getPath());
        if($path == null){
            return null;
        }

        // Scope
        $configData->setScope($configDataMethod->getScope());
        $configData->setScopeId($configDataMethod->getScopeId());

        $configData->setPath($path);

        // Value
        $value = $this->mapValueByPath($configDataMethod->getValue(), $configDataMethod->getPath());
        $configData->setValue($value);

        return $configData;
    }

    public function mapPath($path)
    {
        $field = $this->getFieldFromPath($path);

        if(!array_key_exists($field,$this->mappingArray)){
            return null;
        }

        $newPath = self::CONFIG_PAYONE_GENERAL_GLOBAL . $this->mappingArray[$field];
        if($field == 'submit_products')
            $newPath = self::CONFIG_PAYONE_GENERAL_PARAMETER_INVOICE . $this->mappingArray[$field];
        return $newPath;

    }

    /**
     * @param string $value
     * @param string $path
     * @return string
     */
    public function mapValueByPath($value, $path)
    {
        $field = $this->getFieldFromPath($path);

        $newValue = $value;

        return $newValue;
    }

    protected function getFieldFromPath($path)
    {
        $pathParts = explode('/', $path);
        return array_pop($pathParts);
    }
}
