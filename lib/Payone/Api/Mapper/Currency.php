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
 * @package         Payone_Api
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Mapper_Currency extends Payone_Api_Mapper_Abstract
    implements Payone_Api_Mapper_Currency_Interface
{
    const PATH_CURRENCY_PROPERTIES_DEFAULT = 'currency.properties';

    /** Property names from properties file */
    const PROPERTY_NAME_ID = 'id';
    const PROPERTY_NAME_SIGN = 'sign';
    const PROPERTY_NAME_CODE = 'code';
    const PROPERTY_NAME_SUBDIV = 'subdiv';

    /**
     * @var string
     */
    protected $pathToProperties = '';
    /**
     * @var array
     */
    protected $mappingByCode = null;
    /**
     * @var array
     */
    protected $mappingById = null;

    /**
     * @param $amount
     * @param $code
     * @return string
     *
     */
    public function mapAmountToSub($amount, $code)
    {
        $subdiv = $this->initSubdivByCode($code);
        return number_format($amount * $subdiv, 0, '.', '');
    }

    /**
     * @param $subAmount
     * @param string $code
     * @return string
     * @throws Payone_Api_Exception_MappingNotFound
     */
    public function mapAmountToMain($subAmount, $code)
    {
        $subdiv = $this->initSubdivByCode($code);
        return number_format((float)$subAmount / $subdiv, 2, '.', '');
    }

    /**
     * @param string $code
     * @return string
     * @throws Payone_Api_Exception_MappingNotFound
     */
    public function getIdByCode($code)
    {
        $code = strtolower($code);
        if (!$this->hasMappingByCode($code, self::PROPERTY_NAME_ID)) {
            throw new Payone_Api_Exception_MappingNotFound();
        }

        $mapping = $this->getMappingByCode($code);
        return $mapping[self::PROPERTY_NAME_ID];
    }

    /**
     * @param $id
     * @return string
     * @throws Payone_Api_Exception_MappingNotFound
     */
    public function getCodeById($id)
    {
        $id = trim((string)$id);
        $mappingById = $this->getMappingById();
        if (!array_key_exists($id, $mappingById) || trim($mappingById[$id]) === '') {
            throw new Payone_Api_Exception_MappingNotFound();
        }
        return $mappingById[$id];
    }

    /**
     * @param string $code
     * @return string
     * @throws Payone_Api_Exception_MappingNotFound
     */
    public function getCurrencySymbolByCode($code)
    {
        $code = strtolower($code);
        if (!$this->hasMappingByCode($code, self::PROPERTY_NAME_SIGN)) {
            throw new Payone_Api_Exception_MappingNotFound();
        }
        $mapping = $this->getMappingByCode($code);
        return $mapping[self::PROPERTY_NAME_SIGN];
    }

    /**
     * @param $code
     * @return int
     * @throws Payone_Api_Exception_MappingNotFound
     */
    protected function initSubdivByCode($code)
    {
        $code = strtolower($code);
        if (!$this->hasMappingByCode($code, self::PROPERTY_NAME_SUBDIV)) {
            throw new Payone_Api_Exception_MappingNotFound();
        }

        $mapping = $this->getMappingByCode($code);

        $subdiv = $mapping[self::PROPERTY_NAME_SUBDIV];
        if (!is_numeric($subdiv)) {
            throw new Payone_Api_Exception_MappingNotFound();
        }
        $subdiv = (int)$subdiv;
        if ($subdiv === 0) {
            $subdiv = 1;
        }
        return $subdiv;
    }

    /**
     * @return array
     */
    protected function initCurrenciesRaw()
    {
        $pathToProperties = $this->getPathToProperties();
        $pathToProperties = $pathToProperties != '' ? $pathToProperties : self::PATH_CURRENCY_PROPERTIES_DEFAULT;

        $contents = file_get_contents($pathToProperties, true);
        $lines = explode(PHP_EOL, $contents);
        $currenciesRaw = array();
        foreach ($lines as $key => $line) {
            $lines[$key] = trim($line);
            if ($lines[$key] !== '') {
                $exploded = explode('=', $lines[$key]);
                if (count($exploded) !== 2) {
                    continue;
                }
                $key = trim($exploded[0]);
                $value = trim($exploded[1]);
                $currenciesRaw[$key] = $value;
            }
        }
        return $currenciesRaw;
    }

    /**
     * @return array
     */
    protected function initMappingByCode()
    {
        $currenciesRaw = $this->initCurrenciesRaw();

        $mappingByCode = array();
        foreach ($currenciesRaw as $key => $data) {
            $explodedKey = explode('.', $key);
            if (count($explodedKey) != 3) {
                continue;
            }
            $type = $explodedKey[1];
            $typeCode = $explodedKey[2];

            /** ignore the assignment of id to code and type should be set */
            if (is_numeric($typeCode) or $type == '') {
                continue;
            }

            $typeCodeKey = $type;
            if ($type == self::PROPERTY_NAME_CODE) {
                $typeCodeKey = self::PROPERTY_NAME_ID;
            }

            if ($typeCodeKey == '') {
                continue;
            }

            if (!array_key_exists($typeCode, $mappingByCode)) {
                $mappingByCode[$typeCode] = array();
            }

            $mappingByCode[$typeCode][$typeCodeKey] = trim($data);
        }
        ksort($mappingByCode);
        return $mappingByCode;
    }

    /**
     * @param array $mappingByCode
     */
    public function setMappingByCode(array $mappingByCode)
    {
        $this->mappingByCode = $mappingByCode;
    }

    /**
     * @param string $code
     * @return array
     */
    public function getMappingByCode($code = '')
    {
        if ($this->mappingByCode === null) {
            $this->mappingByCode = $this->initMappingByCode();
        }
        if ($code != '') {
            return $this->mappingByCode[$code];
        }
        return $this->mappingByCode;
    }

    /**
     * @param $code
     * @param $innerArrayKey ('id' | 'sign' | 'subdiv')
     * @return bool
     */
    public function hasMappingByCode($code, $innerArrayKey)
    {
        $mappingByCode = $this->getMappingByCode();
        if (!array_key_exists($code, $mappingByCode)) {
            return false;
        }
        if (!array_key_exists($innerArrayKey, $mappingByCode[$code])) {
            return false;
        }
        if ($mappingByCode[$code][$innerArrayKey] === '') {
            return false;
        }
        return true;
    }

    /**
     * @return array
     *
     * mappingById is derived from mappingByCode
     */
    public function getMappingById()
    {
        if ($this->mappingById === null) {
            $this->mappingById = $this->initMappingById();
        }
        return $this->mappingById;
    }

    /**
     * @return array
     */
    protected function initMappingById()
    {
        $mappingByCode = $this->getMappingByCode();
        $mappingById = array();
        foreach ($mappingByCode as $code => $data) {
            if (!array_key_exists(self::PROPERTY_NAME_ID, $data)) {
                continue;
            }
            $id = $data[self::PROPERTY_NAME_ID];
            if ($id !== '') {
                $mappingById[$id] = $code;
            }
        }
        return $mappingById;
    }

    /**
     * @param string $pathToProperties
     */
    public function setPathToProperties($pathToProperties)
    {
        $this->pathToProperties = $pathToProperties;
    }

    /**
     * @return string
     */
    public function getPathToProperties()
    {
        return $this->pathToProperties;
    }
}
