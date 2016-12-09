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
class Payone_Migrator_Model_Mapper_Config_Protect
    extends Payone_Migrator_Model_Mapper_Abstract
{


    const CONFIGPATH_MAGE_PAYONE_CREDITRATING = 'payonecreditrating/general';
    const CONFIGPATH_MAGE_PAYONE_ADDRESSCHECK = 'payonecreditrating/addresscheck';
    const CONFIGPATH_MAGE_PAYONE_CREDITRATING_MISC = 'payonecreditrating/misc';
    const CONFIGPATH_PAYONE_CORE_PROTECT = 'payone_protect';

    /** @var Payone_Migrator_Model_Mapper_Config_Payment */
    protected $mapperConfigPayment = null;

    protected $mappingConfigPathOldToNew = array(
        'payonecreditrating/general/creditratingactive' => 'payone_protect/creditrating/enabled',
        'payonecreditrating/general/transaction_mode' => 'payone_protect/creditrating/mode',
        'payonecreditrating/general/creditratinginquirytype' => 'payone_protect/creditrating/type',
        'payonecreditrating/general/creditratingmintotal' => 'payone_protect/creditrating/min_order_total',
        'payonecreditrating/general/storelifetime' => 'payone_protect/creditrating/result_lifetime',
        'payonecreditrating/general/allow_yellow_for_payment_methods' => 'payone_protect/creditrating/allow_payment_methods_yellow',
        'payonecreditrating/general/allow_red_for_payment_methods' => 'payone_protect/creditrating/allow_payment_methods_red',
        /* Addresscheck: */
        'payonecreditrating/addresscheck/active' => 'payone_protect/address_check_enabled',
        'payonecreditrating/addresscheck/transaction_mode' => 'payone_protect/address_check/mode',
        'payonecreditrating/addresscheck/person_status_to_credit_score_mapping' => 'payone_protect/address_check/mapping_personstatus'
    );

    protected $mappingConfigValueOldToNew = array(
        'payonecreditrating/general/allow_yellow_for_payment_methods' => 'mapPaymentMethodList',
        'payonecreditrating/general/allow_red_for_payment_methods' => 'mapPaymentMethodList',
        'payonecreditrating/addresscheck/person_status_to_credit_score_mapping' => 'mapPersonStatusMapping',
        'payonecreditrating/misc/shipping_sku' => 'payone_misc/shipping_costs/sku'

    );

    /**
     * Get Payone_Core config path for a given Mage_Payone config path
     * Returns FALSE if the config does not exist in Payone_Core
     *
     * @param $oldConfigPath
     * @return string|bool
     */
    public function mapConfigPathOldToNew($oldConfigPath)
    {
        if (array_key_exists($oldConfigPath, $this->mappingConfigPathOldToNew)) {
            $newConfigPath = $this->mappingConfigPathOldToNew[$oldConfigPath];
            if (!empty($newConfigPath)) {
                return $newConfigPath;
            }
        }

        return FALSE;
    }


    /**
     * @param string $oldPath
     * @param string $oldValue
     * @return string
     */
    public
    function mapConfigValueOldToNew($oldPath, $oldValue)
    {
        $newValue = $oldValue;

        if (array_key_exists($oldPath, $this->mappingConfigValueOldToNew)) {
            // Mapping for this key exists.
            $valueMap = $this->mappingConfigValueOldToNew[$oldPath];
            if (is_array($valueMap)) {
                if (array_key_exists($oldValue, $valueMap)) {
                    // Value differs, return new one.
                    $newValue = $valueMap[$oldValue];
                }
            }
            elseif (method_exists($this, $valueMap)) {
                // Check if a special mapping is required:
                $newValue = $this->{$valueMap}($oldValue);
            }
        }

        return $newValue;

    }

    /**
     * Subject is a list of payment methods (e.g. "payone_vor, payone_cc")
     * Replaces Mage_Payone payment methods with Payone_Core methods, (-> "payone_advance_payment, payone_creditcard")
     *
     * @param string $subject
     * @return string The modified $subject
     */
    protected
    function mapPaymentMethodList($subject)
    {
        foreach ($this->getMapperConfigPayment()->getMappingMethodCode() as $oldCode => $newCode) {
            $subject = str_replace($oldCode, $newCode, $subject);
        }

        return $subject;
    }

    /**
     * Subject is a serialized person status mapping
     * Unserializes and constructs Payone_Core compatible array, re-serializes
     * @param string $subject
     * @return string
     */
    protected function mapPersonStatusMapping($subject)
    {
        $oldArray = unserialize($subject);

        if (!is_array($oldArray)) {
            return '';
        }

        // inner arrays must exist:
        if (!array_key_exists('status', $oldArray)
                or !array_key_exists('score', $oldArray)
        ) {
            return '';
        }

        // ... and have the same length:
        $statusArray = $oldArray['status'];
        $scoreArray = $oldArray['score'];
        if (count($scoreArray) != count($statusArray)) {
            return '';
        }

        $newMappings = array();
        for ($i = 0; $i < count($statusArray); $i++) {
            // playing it safe, in case someone manipulated the array.
            if (!array_key_exists($i, $scoreArray)
                    or !array_key_exists($i, $statusArray)
            ) {
                continue;
            }

            $status = $statusArray[$i];
            $score = $scoreArray[$i];

            if (empty($status) or empty($score)) {
                continue;
            }

            $newMapping = array(

                'personstatus' => array(0 => $status),
                'score' => array(0 => $score)
            );
            array_push($newMappings, $newMapping);
        }

        return serialize($newMappings);
    }

    /**
     * @param Payone_Migrator_Model_Mapper_Config_Payment $mapperConfigPayment
     */
    public
    function setMapperConfigPayment(Payone_Migrator_Model_Mapper_Config_Payment $mapperConfigPayment)
    {
        $this->mapperConfigPayment = $mapperConfigPayment;
    }

    /**
     * @return Payone_Migrator_Model_Mapper_Config_Payment
     */
    public
    function getMapperConfigPayment()
    {
        return $this->mapperConfigPayment;
    }
}
