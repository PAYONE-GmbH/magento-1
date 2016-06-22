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
 * @package         Payone_Licensemanager_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Licensemanager_Block
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Licensemanager_Block_Adminhtml_Notification_Window extends Mage_Adminhtml_Block_Notification_Window
{
    public function canShow()
    {
        $helper = Mage::helper('payone_licensemanager');
        $session = Mage::getSingleton('core/session');
        $result = !$helper->isPayoneRegisterd() && !$session->getPayoneLicensePopupWindow();
        $session->setPayoneLicensePopupWindow(true);
        return $result;
    }

    public function getHeaderText()
    {
        return 'PAYONE';
    }

//    public function getSeverityIconsUrl()
//    {
//        if (is_null($this->_severityIconsUrl)) {
//            $this->_severityIconsUrl =
//                (Mage::app()->getFrontController()->getRequest()->isSecure() ? 'https://' : 'http://')
//                . sprintf(Mage::getStoreConfig(self::XML_SEVERITY_ICONS_URL_PATH), Mage::getVersion(),
//                    'SEVERITY_CRITICAL')
//            ;
//        }
//        return $this->_severityIconsUrl;
//    }
//
//    public function getSeverityText()
//    {
//        return $this->__('critical');
//    }

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