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
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Adminhtml_System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Adminhtml_Payonecore_System_ConfigurationController
    extends Payone_Core_Controller_Adminhtml_Abstract
{
    protected $acl_resource = 'payone/configuration/configuration_export';
    
    /**
     * @return Mage_Core_Controller_Varien_Action
     */
    public function exportAction()
    {
        $service = $this->getFactory()->getServiceXmlGenerate();
        $configService = $this->getFactory()->getServiceConfigXmlGenerate();
        $configXml = $configService->execute();
        return $this->_prepareDownloadResponse(
            'payone_config_export' . Mage::getSingleton('core/date')->date('Y-m-d_H-i-s') . '.xml', $configXml,
            'application/xml'
        );
    }

    /**
     * @return Mage_Core_Controller_Varien_Action
     */
    public function exportOldAction()
    {
        $config = '';

        $storeId = null;

        $configPaymentCc = Mage::getStoreConfig('payment/payone_cc', $storeId);
        $configPaymentElv = Mage::getStoreConfig('payment/payone_elv', $storeId);
        $configPaymentVor = Mage::getStoreConfig('payment/payone_vor', $storeId);
        $configPaymentRec = Mage::getStoreConfig('payment/payone_rec', $storeId);
        $configPaymentCod = Mage::getStoreConfig('payment/payone_cod', $storeId);
        $configPaymentSb = Mage::getStoreConfig('payment/payone_sb', $storeId);
        $configPaymentWlt = Mage::getStoreConfig('payment/payone_wlt', $storeId);
        $configPaymentFnc = Mage::getStoreConfig('payment/payone_fnc', $storeId);
        $configPaymentSin = Mage::getStoreConfig('payment/payone_sin', $storeId);
        $configPaymentCsh = Mage::getStoreConfig('payment/payone_csh', $storeId);

        $configCreditrating = Mage::getStoreConfig('payonecreditrating', $storeId);

        $configPayment = array(
            'payone_cc' => $configPaymentCc,
            'payone_elv' => $configPaymentElv,
            'payone_vor' => $configPaymentVor,
            'payone_rec' => $configPaymentRec,
            'payone_cod' => $configPaymentCod,
            'payone_sb' => $configPaymentSb,
            'payone_wlt' => $configPaymentWlt,
            'payone_fnc' => $configPaymentFnc,
            'payone_csh' => $configPaymentCsh,
            'payone_sin' => $configPaymentSin
        );

        // PAYMENT
        foreach ($configPayment as $methodCode => $configMethod) {
            $config .= '[' . $methodCode . ']' . "\n";
            foreach ($configMethod as $key => $value) {
                $config .= $key . ' = ' . $value . "\n";
            }

            $config .= "\n";
        }

        // CREDITRATING
        foreach ($configCreditrating as $methodCode => $configMethod) {
            $config .= '[' . $methodCode . ']' . "\n";
            foreach ($configMethod as $key => $value) {
                $config .= $key . ' = ' . $value . "\n";
            }

            $config .= "\n";
        }

        return $this->_prepareDownloadResponse(
            'payone_config_export' . Mage::getSingleton('core/date')->date('Y-m-d_H-i-s') . '.txt', $config,
            'application/text'
        );
    }
}