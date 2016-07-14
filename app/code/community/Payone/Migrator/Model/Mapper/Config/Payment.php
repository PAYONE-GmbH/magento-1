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
class Payone_Migrator_Model_Mapper_Config_Payment extends Payone_Migrator_Model_Mapper_Abstract
{
    protected $mappingMethodToConfigCode = array(
        'payone_cc' => 'creditcard',
        'payone_elv' => 'debit_payment',
        'payone_rec' => 'invoice',
        'payone_vor' => 'advance_payment',
        'payone_sb' => 'online_bank_transfer',
        'payone_wlt' => 'wallet',
        'payone_cod' => 'cash_on_delivery',
        'payone_fnc' => 'financing',
        'payone_csh' => 'barzahlen',
    );

    protected $mappingMethodToMethodCode = array(
        'payone_cc' => 'payone_creditcard',
        'payone_elv' => 'payone_debit_payment',
        'payone_rec' => 'payone_invoice',
        'payone_vor' => 'payone_advance_payment',
        'payone_sb' => 'payone_online_bank_transfer',
        'payone_wlt' => 'payone_wallet',
        'payone_cod' => 'payone_cash_on_delivery',
        'payone_fnc' => 'payone_financing',
        'payone_csh' => 'payone_barzahlen',
    );

    protected $mappingCreditcardTypes = array(
        'VI' => Payone_Api_Enum_CreditcardType::VISA,
        'MC' => Payone_Api_Enum_CreditcardType::MASTERCARD,
        'AE' => Payone_Api_Enum_CreditcardType::AMEX,
        'MCI' => Payone_Api_Enum_CreditcardType::MAESTRO_INTERNATIONAL,
        'JCB' => Payone_Api_Enum_CreditcardType::JCB,
        'DI' => Payone_Api_Enum_CreditcardType::DISCOVER
    );

    public function map($methodCode, array $config)
    {
        // use Varien_Object to reduce array isset checking
        $config = new Varien_Object($config);

        $configData = array();

        $configData['scope'] = $config['scope'];
        $configData['scope_id'] = $config['scope_id'];

        $configData['code'] = $this->mapOldMethodCodeToConfigCode($methodCode);
        $configData['name'] = $config['title'];
        $configData['request_type'] = $this->mapOldRequestType($config['request_type']);
        $configData['enabled'] = $config['active'];
        $configData['use_global'] = 1;
        $configData['allowspecific'] = 1;
        $configData['check_cvc'] = $config['useccv'];
        $configData['min_order_total'] = $config['min_order_total'];
        $configData['max_order_total'] = $config['max_order_total'];

        $configData['types'] = ''; // @todo could map type by methocode
        $configData['check_bankaccount'] = ''; // @todo could map type by methocode

        return $configData;
    }

    public function mapOldRequestType($type)
    {
        $value = null;
        if ($type == 'authorize') {
            $value = 'authorization';
        }
        elseif ($type == 'authorize_capture') {
            $value = 'preauthorization';
        }
        return $value;
    }

    public function mapOldMethodCodeToConfigCode($methodCode)
    {
        $configKey = '';
        if (array_key_exists($methodCode,  $this->mappingMethodToConfigCode)) {
            $configKey =  $this->mappingMethodToConfigCode[$methodCode];
        }
        return $configKey;
    }

    public function mapOldMethodCodeToNew($methodCode)
    {
        $configKey = '';
        if (array_key_exists($methodCode,  $this->mappingMethodToMethodCode)) {
            $configKey =  $this->mappingMethodToMethodCode[$methodCode];
        }
        return $configKey;
    }

    public function getMappingMethodCode()
    {
        return $this->mappingMethodToMethodCode;
    }

    public function getMappingConfigCode()
    {
        return $this->mappingMethodToConfigCode;
    }

    public function getMappingCreditcardTypes()
    {
        return $this->mappingCreditcardTypes;
    }

}
