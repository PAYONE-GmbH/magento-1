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
class Payone_Migrator_Model_Service_Migrate
    extends Payone_Migrator_Model_Service_Abstract
{
    /**
     * @var Payone_Migrator_Model_Service_Configuration_GeneralMigrate
     */
    protected $serviceConfigGeneralMigrate = null;

    /**
     * @var Payone_Migrator_Model_Service_Configuration_PaymentMigrate
     */
    protected $serviceConfigPaymentMigrate = null;

    /**
     * @var Payone_Migrator_Model_Service_Sales_PaymentMigrate
     */
    protected $serviceSalesPaymentMigrate = null;

    /** @var Payone_Migrator_Model_Service_Configuration_ProtectMigrate */
    protected $serviceConfigProtectMigrate = null;

    /**
     * @param $part
     * @return bool
     */
    public function migratePart($part)
    {
        if (!$this->helperConfig()->haveToMigratePart($part)) {
            return true;
        }

        ini_set('max_execution_time', 1800);
        ini_set('memory_limit', '512M');

        $this->helper()->log($part . ' : start migration');

        if ($part == 'config_global') {
            $status = $this->migrateConfigGlobal();
        }
        elseif ($part == 'config_payment') {
            $status = $this->migrateConfigPayment();
        }
        elseif ($part == 'order_payment') {
            $status = $this->migrateOrderPayment();
        }
        elseif ($part == 'payment_finish') {
            $status = $this->migrateConfigPaymentFinish();
        }
        elseif ($part == 'config_protect') {
            $status = $this->migrateConfigProtect();
        }
        elseif ($part == 'finish') {
            $status = $this->finish();
        }
        else {
            $status = false;
        }

        $statusCode = $status === true ? 'success' : 'error';
        $this->helperConfig()->setMigrationPartStatus($part, $statusCode);

        $this->helper()->log($part . ' : finished migration with ' . $status);
        return $status;
    }

    public function migrateConfigGlobal()
    {
        return $this->getServiceConfigGeneralMigrate()->migrate();
    }

    public function migrateConfigPayment()
    {
        // return true;
        return $this->getServiceConfigPaymentMigrate()->createConfigurationsAndConnectToOrders();
    }

    public function migrateOrderPayment()
    {
        return $this->getServiceSalesPaymentMigrate()->execute();
    }

    public function migrateConfigPaymentFinish()
    {
        if (!$this->helperConfig()->isPartStatusSuccess('config_payment')
                or !$this->helperConfig()->isPartStatusSuccess('order_payment')
        ) {
            return false;
        }
        return $this->getServiceConfigPaymentMigrate()->finishPaymentMigration();
    }

    public function migrateConfigProtect()
    {
        return $this->getServiceConfigProtectMigrate()->execute();
    }

    public function finish()
    {
        if ($this->helperConfig()->areAllMigrationPartsSuccess()) {
            $this->helperConfig()->setMigrationStatus();
            return true;
        }
        return false;
    }

    /**
     * @param Payone_Migrator_Model_Service_Configuration_PaymentMigrate $serviceConfigPaymentMigrate
     */
    public function setServiceConfigPaymentMigrate($serviceConfigPaymentMigrate)
    {
        $this->serviceConfigPaymentMigrate = $serviceConfigPaymentMigrate;
    }

    /**
     * @return Payone_Migrator_Model_Service_Configuration_PaymentMigrate
     */
    public function getServiceConfigPaymentMigrate()
    {
        return $this->serviceConfigPaymentMigrate;
    }

    /**
     * @param Payone_Migrator_Model_Service_Sales_PaymentMigrate $serviceSalesPaymentMigrate
     */
    public function setServiceSalesPaymentMigrate($serviceSalesPaymentMigrate)
    {
        $this->serviceSalesPaymentMigrate = $serviceSalesPaymentMigrate;
    }

    /**
     * @return Payone_Migrator_Model_Service_Sales_PaymentMigrate
     */
    public function getServiceSalesPaymentMigrate()
    {
        return $this->serviceSalesPaymentMigrate;
    }

    /**
     * @param Payone_Migrator_Model_Service_Configuration_GeneralMigrate $serviceConfigGeneralMigrate
     */
    public function setServiceConfigGeneralMigrate($serviceConfigGeneralMigrate)
    {
        $this->serviceConfigGeneralMigrate = $serviceConfigGeneralMigrate;
    }

    /**
     * @return Payone_Migrator_Model_Service_Configuration_GeneralMigrate
     */
    public function getServiceConfigGeneralMigrate()
    {
        return $this->serviceConfigGeneralMigrate;
    }

    /**
     * @param Payone_Migrator_Model_Service_Configuration_ProtectMigrate $serviceConfigProtectMigrate
     */
    public function setServiceConfigProtectMigrate(Payone_Migrator_Model_Service_Configuration_ProtectMigrate $serviceConfigProtectMigrate)
    {
        $this->serviceConfigProtectMigrate = $serviceConfigProtectMigrate;
    }

    /**
     * @return Payone_Migrator_Model_Service_Configuration_ProtectMigrate
     */
    public function getServiceConfigProtectMigrate()
    {
        return $this->serviceConfigProtectMigrate;
    }

}
