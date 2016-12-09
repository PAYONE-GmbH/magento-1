<?php
/**
 * Class description
 *
 * @category   Votum
 * @package    Votum_Module
 * @author     Edward Mateja <edward.mateja@votum.de>
 */

class Payone_Licensemanager_Block_Adminhtml_Notification_Toolbar extends Mage_Adminhtml_Block_Notification_Toolbar
{
    public function isShow()
    {
        $helper = Mage::helper('payone_licensemanager');
        $result = !$helper->isPayoneRegisterd();
        return $result;
    }

    public function getNoticeMessageText()
    {
        return $this->__('Payone extension is currently disable. Please register this extension to make it active.');
    }

    public function getNoticeMessageUrl()
    {
        return $this->getUrl('adminhtml/payonelicensemanager_active');
    }

    public function getReadDetailsText()
    {
        return $this->__('Register PAYONE extension');
    }
}