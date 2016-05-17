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
class Payone_Migrator_Model_Service_Sales_PaymentMigrate
    extends Payone_Migrator_Model_Service_Abstract
{
    /** @var Payone_Migrator_Model_Mapper_Config_Payment */
    protected $mapperConfigPayment = null;

    /**
     * @return bool
     */
    public function execute()
    {
        if (!$this->hasPayoneOrders()) {
            return true;
        }

        // Resource Model:
        $resource = $this->getFactory()->getModelCoreResource();

        // Init Write Connection
        $db = $resource->getConnection('core_write');

        // Fetch Tablenames:
        $tablePayoneTransaction = $resource->getTableName('payone_transaction');
        $tableSalesOrder = $resource->getTableName('sales/order');
        $tableSalesOrderPayment = $resource->getTableName('sales/order_payment');

        // Migrate transaction data:
        $query = $this->prepareTransactionCreationQuery($tablePayoneTransaction, $tableSalesOrder, $tableSalesOrderPayment);
        $db->query($query);

        // Migrate bank account numbers:
        $query = $this->prepareBankAccountNumberMigrationQuery($tableSalesOrderPayment);
        $db->query($query);

        // Migrate credit card types:
        foreach ($this->getMapperConfigPayment()->getMappingCreditcardTypes() as $old => $new) {

            $query = $this->prepareCreditcardtypeMigrationQuery($tableSalesOrderPayment, $old, $new);
            $db->query($query);
        }
        // Migrate onlinebanktransfer types:
        $query = $this->prepareOnlinebanktransferTypeMigrationQuery($tableSalesOrderPayment);
        $db->query($query);

        return true;
    }

    /**
     * Prepare SQL for collecting OLD payment/order data and insert it into NEW transaction table.
     * Only for Mage_Payone payment methods.
     * @param string $tablePayoneTransaction
     * @param string $tableSalesOrder
     * @param string $tableSalesOrderPayment
     *
     * @return string
     */
    public function prepareTransactionCreationQuery($tablePayoneTransaction, $tableSalesOrder, $tableSalesOrderPayment)
    {
        $methodCodesMap = $this->getMapperConfigPayment()->getMappingMethodCode();

        $methodCodesOld = array_keys($methodCodesMap);

        $methodCodes = '';
        foreach ($methodCodesOld as $code) {
            if (strlen($methodCodes) > 0) {
                $methodCodes .= ', ';
            }
            $methodCodes .= "'" . $code . "'";
        }

        $sql = "INSERT INTO
        `$tablePayoneTransaction`
            (`id`,
            `store_id`,
            `order_id`,
            `txid`,
            `txtime`,
            `reference`,
            `last_txaction`,
            `last_sequencenumber`,
            `clearingtype`,
            `mode`,
            `mid`,
            `aid`,
            `portalid`,
            `productid`,
            `currency`,
            `receivable`,
            `balance`,
            `customerid`,
            `userid`,
            `reminderlevel`,
            `failedcause`,
            `accessid`,
            `created_at`,
            `updated_at`)
        SELECT
        NULL as id,
            order.store_id,
            order.entity_id,
            payment.last_trans_id,
            '' AS txtime ,
            order.increment_id,
            '' AS last_txaction,
            payment.sequence_number,
            SUBSTRING(payment.method, 8),
            '' AS mode,
            ''AS mid,
            '' AS aid,
            '' AS portalid,
            '' AS productid,
            order.order_currency_code,
            '' AS receivable,
            '' AS balance,
            '' AS customerid,
            '' AS userid,
            '' AS reminderlevel,
            '' AS failedcause,
            '' AS accessid,
            NOW() AS created_at,
        '0000-00-00 00:00:00' AS updated_at

        FROM `$tableSalesOrder` AS `order`
        INNER JOIN `$tableSalesOrderPayment` AS payment
        ON order.entity_id = payment.parent_id
        WHERE
        payment.method IN ($methodCodes) AND order.entity_id NOT IN (SELECT order_id FROM `$tablePayoneTransaction`)";

        return $sql;
    }

    /**
     * Prepare a query that migrates OLD credit card types (e.g. 'VI') to NEW creditcardtypes (e.g. 'V')
     * Only for payment method 'payone_cc'
     *
     * @param string $tableSalesOrderPayment
     * @param string $oldCcType
     * @param string $newCcType
     *
     * @return string
     */
    protected function prepareCreditcardtypeMigrationQuery($tableSalesOrderPayment, $oldCcType, $newCcType)
    {
        $sql = "UPDATE `$tableSalesOrderPayment`
                 SET cc_type = '$newCcType'
                 WHERE cc_type = '$oldCcType'
                   AND method = 'payone_cc'";

        return $sql;
    }

    /**
     * Prepare a query that migrates onlinebanktransfer types from column cc_type to NEW column payone_onlinebanktransfer_type
     * Only for payment method 'payone_sb'
     *
     * @param string $tableSalesOrderPayment
     *
     * @return string
     */
    protected function prepareOnlinebanktransferTypeMigrationQuery($tableSalesOrderPayment)
    {
        $sql = "UPDATE `$tableSalesOrderPayment`
                     SET payone_onlinebanktransfer_type = cc_type
                     WHERE method = 'payone_sb'";

        return $sql;
    }

    /**
     * Prepare a query that migrates onlinebanktransfer types from column cc_type to NEW column payone_onlinebanktransfer_type
     * Only for payment method 'payone_sb'
     *
     * @param string $tableSalesOrderPayment
     *
     * @return string
     */
    protected function prepareBankAccountNumberMigrationQuery($tableSalesOrderPayment)
    {
        $sql = "UPDATE `$tableSalesOrderPayment`
                         SET payone_bank_code = po_number
                         WHERE method = 'payone_sb'
                            OR method = 'payone_elv'";

        return $sql;
    }

    /**
     * @param Payone_Migrator_Model_Mapper_Config_Payment $mapperConfigPayment
     */
    public function setMapperConfigPayment(Payone_Migrator_Model_Mapper_Config_Payment $mapperConfigPayment)
    {
        $this->mapperConfigPayment = $mapperConfigPayment;
    }

    /**
     * @return Payone_Migrator_Model_Mapper_Config_Payment
     */
    public function getMapperConfigPayment()
    {
        return $this->mapperConfigPayment;
    }

}
