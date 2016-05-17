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
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Christian Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_System_Config_MethodType
    extends Payone_Core_Model_System_Config_Abstract
{
    /**
     * @return array
     */
    public function toArray()
    {
        $settingsCreditcard = new Payone_Settings_Configuration_PaymentMethod_CreditCard();
        $settingsOnlineBankTransfer = new Payone_Settings_Configuration_PaymentMethod_OnlineBankTransfer();
        $settingsWallet = new Payone_Settings_Configuration_PaymentMethod_Wallet();
        $settingsFinancing = new Payone_Settings_Configuration_PaymentMethod_Financing();
        $settingsSafeInvoice = new Payone_Settings_Configuration_PaymentMethod_Financing();

        $return = array_merge(
            $settingsCreditcard->getTypes(),
            $settingsOnlineBankTransfer->getTypes(),
            $settingsWallet->getTypes(),
            $settingsFinancing->getTypes(),
            $settingsSafeInvoice->getTypes()
        );

        return $return;
    }

    /**
     * @return array
     */
    public function toGroupArray()
    {
        $settingsCreditcard = new Payone_Settings_Configuration_PaymentMethod_CreditCard();
        $settingsOnlineBankTransfer = new Payone_Settings_Configuration_PaymentMethod_OnlineBankTransfer();
        $settingsWallet = new Payone_Settings_Configuration_PaymentMethod_Wallet();
        $settingsFinancing = new Payone_Core_Model_System_Config_FinancingType();
        $settingsSafeInvoice = new Payone_Core_Model_System_Config_SafeInvoiceType();

        $return = array(
            'Creditcard' => $settingsCreditcard->getTypes(),
            'Online Bank Transfer' => $settingsOnlineBankTransfer->getTypes(),
            'Wallet' => $settingsWallet->getTypes(),
            'Financing' => $settingsFinancing->toArray(),
            'Safe Invoice' => $settingsSafeInvoice->toArray(),
        );

        return $return;
    }

    /**
     * @return array
     */
    public function toOptionGroupArray()
    {
        $data = array();
        foreach ($this->toGroupArray() as $groupKey => $group) {
            if (!array_key_exists($groupKey, $data)) {
                $data[$groupKey] = array(
                    'label' => $this->helper()->__($groupKey),
                    'value' => array(),
                );
            }

            foreach ($group as $key => $value) {
                $data[$groupKey]['value'][$key] = array(
                    'value' => $key,
                    'label' => Mage::helper('payone_core')->__($value)
                );
            }
        }
        return $data;
    }
}