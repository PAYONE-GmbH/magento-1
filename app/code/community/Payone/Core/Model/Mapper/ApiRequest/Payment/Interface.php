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
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_Core_Model_Mapper_ApiRequest_Payment_Interface
{
    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return mixed
     */
    public function mapFromPayment(Mage_Sales_Model_Order_Payment $payment);

    /**
     * @param float $amount
     */
    public function setAmount($amount);

    /**
     * @return float
     */
    public function getAmount();

    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $configPayment
     * @return void
     */
    public function setConfigPayment(Payone_Core_Model_Config_Payment_Method_Interface $configPayment);

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfigPayment();

}