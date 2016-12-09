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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_Core_Model_Config_Interface
{
    /**
     * @param int $storeId
     */
    public function setStoreId($storeId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param Payone_Core_Model_Config_General $general
     */
    public function setGeneral(Payone_Core_Model_Config_General $general);

    /**
     * @return Payone_Core_Model_Config_General
     */
    public function getGeneral();

    /**
     * @param Payone_Core_Model_Config_Misc $misc
     */
    public function setMisc(Payone_Core_Model_Config_Misc $misc);

    /**
     * @return Payone_Core_Model_Config_Misc
     */
    public function getMisc();

    /**
     * @param Payone_Core_Model_Config_Payment $payment
     */
    public function setPayment(Payone_Core_Model_Config_Payment $payment);

    /**
     * @return Payone_Core_Model_Config_Payment
     */
    public function getPayment();

    /**
     * @param Payone_Core_Model_Config_Protect $protect
     */
    public function setProtect(Payone_Core_Model_Config_Protect $protect);

    /**
     * @return Payone_Core_Model_Config_Protect
     */
    public function getProtect();

}