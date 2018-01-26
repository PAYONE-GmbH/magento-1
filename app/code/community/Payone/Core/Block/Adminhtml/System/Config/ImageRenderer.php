<?php

class Payone_Core_Block_Adminhtml_System_Config_ImageRenderer
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }

    protected function _getValue(Varien_Object $row)
    {
        $type = $row->getData('type');
        if ($type == Payone_Core_Model_System_Config_LogoType::URL) {
            $url = $row->getData('path');
        }
        else {
            $url = Mage::getBaseUrl('media') . $row->getData('path');
        }
        $out = '<img src="' . $url . '" style="max-width: 100px;" />';

        return $out;
    }
}
