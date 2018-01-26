<?php

/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 15/01/18
 * Time: 13:14
 */
class Payone_Core_Model_Domain_Config_Logos
    extends Mage_Core_Model_Abstract
{
    /**
     *
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/config_logos');
    }

    public function getLogoById ($id)
    {
        return $this->load($id);
    }

    public function toOptionArray($blInactiveFilteredOut = false)
    {
        $aOptions = array();
        foreach ($this->getCollection() as $logo) {
            if ($logo->getEnabled() != 1 && $blInactiveFilteredOut) {
                continue;
            }

            $aOptions[$logo->getId()] = $logo->getLabel() . ' (' . Mage::helper('payone_core')->__('Size') . ' ' . $logo->getSize() . ')';
        }

        return $aOptions;
    }
}