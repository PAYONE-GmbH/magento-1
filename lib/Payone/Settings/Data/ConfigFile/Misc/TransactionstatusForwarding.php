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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Settings_Data_ConfigFile_Misc_TransactionstatusForwarding
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'transactionstatus_forwarding';

    /** @var Payone_Settings_Data_ConfigFile_Misc_TransactionstatusForwarding[] */
    protected $transactionstatus_forwarding = array();

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $value
     */
    public function addTransactionstatusForwarding($value){
        $this->transactionstatus_forwarding[] = $value;
    }

    /**
     * @param array $transactionstatus_forwarding
     */
    public function setTransactionstatusForwarding($transactionstatus_forwarding)
    {
        $this->transactionstatus_forwarding = $transactionstatus_forwarding;
    }

    /**
     * @return array
     */
    public function getTransactionstatusForwarding()
    {
        return $this->transactionstatus_forwarding;
    }
}