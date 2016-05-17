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
class Payone_Core_Model_Service_Config_ProtectCheck
    extends Payone_Core_Model_Service_Abstract
{

    /**
     * @var null
     */
    protected $scopeId = null;

    /**
     * @param $addressCheckEnabled
     * @param $creditratingEnabled
     */
    public function execute($addressCheckEnabled = null, $creditratingEnabled = null)
    {
        $protectValue = 0;

        if ($addressCheckEnabled == 1 || $creditratingEnabled == 1) {
            $protectValue = 1;
        }
        else {
            $configCollection = $this->getFilteredModelConfigCollection();

            $configData = $configCollection->getFirstItem();
            if ($configData->hasData()) {
                $protectValue = 1;
            }
        }

        $path = 'payone_protect/general/enabled';

        /**
         * @var $protect Mage_Core_Model_Config_Data
         */
        $protect = $this->getFactory()->getModelCoreConfigData();
        $protect->setScope('default');
        $protect->setScopeId(0);

        $protect->setPath($path);

        /** We must check wheter there is a DB entry for the unique constraint 'config_scope'
         *  in Magento versions < 1.6 (in newer versions this check is done by Magento)
         *
         * @see Mage_Core_Model_Resource_Config_Data::_checkUnique() since Magento 1.6.0.0
         */
        if (version_compare($this->helper()->getMagentoVersion(), '1.6', '<')) {
            $protect = $this->checkConfigUnique($protect);
        }

        $protect->setValue($protectValue);

        return $protect->save();
    }

    /**
     * @param Mage_Core_Model_Config_Data $object
     * @return Mage_Core_Model_Config_Data
     */
    protected function checkConfigUnique(Mage_Core_Model_Config_Data $object)
    {
        /** @var $collection Mage_Core_Model_Mysql4_Config_Data_Collection | Mage_Core_Model_Resource_Config_Data_Collection */
        $collection = $this->getFactory()->getModelCoreConfigData()->getCollection();
        $collection->addFieldToFilter('scope', $object->getScope());
        $collection->addFieldToFilter('scope_id', $object->getScopeId());
        $collection->addFieldToFilter('path', $object->getPath());
        $collection->load();

        if ($collection->count() > 0) {
            /** @var $config Mage_Core_Model_Config_Data */
            $config = $collection->getFirstItem();
            $object->setId($config->getId());
        }

        return $object;
    }

    /**
     * Add default filter to collection
     *
     * @return Mage_Core_Model_Mysql4_Config_Data_Collection
     */
    protected function getFilteredModelConfigCollection()
    {
        /** @var $configCollection Mage_Core_Model_Mysql4_Config_Data_Collection */
        $configCollection = $this->getFactory()->getModelCoreConfigData()->getCollection();
        $configCollection->addFieldToFilter('path',
            array(
                array('eq' => 'payone_protect/creditrating/enabled'),
                array('eq' => 'payone_protect/address_check/enabled'))
        );
        $configCollection->addFieldToFilter('value', array('eq' => '1'));

        return $configCollection;
    }

    /**
     * @param $scopeId
     */
    public function setScopeId($scopeId)
    {
        $this->scopeId = $scopeId;
    }

    /**
     * @return int|null
     */
    public function getScopeId()
    {
        return $this->scopeId;
    }
}