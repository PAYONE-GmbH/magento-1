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
class Payone_Core_Model_Config implements Payone_Core_Model_Config_Interface
{
    /**
     * @var int
     */
    protected $storeId = null;

    /**
     * @var Payone_Core_Model_Config_General
     */
    protected $general = null;
    /**
     * @var Payone_Core_Model_Config_Payment
     */
    protected $payment = null;
    /**
     * @var Payone_Core_Model_Config_Protect
     */
    protected $protect = null;
    /**
     * @var Payone_Core_Model_Config_Misc
     */
    protected $misc = null;

    /**
     * @param int $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * @param Payone_Core_Model_Config_General $general
     */
    public function setGeneral(Payone_Core_Model_Config_General $general)
    {
        $this->general = $general;
    }

    /**
     * @return Payone_Core_Model_Config_General
     */
    public function getGeneral()
    {
        return $this->general;
    }

    /**
     * @param Payone_Core_Model_Config_Misc $misc
     */
    public function setMisc(Payone_Core_Model_Config_Misc $misc)
    {
        $this->misc = $misc;
    }

    /**
     * @return Payone_Core_Model_Config_Misc
     */
    public function getMisc()
    {
        return $this->misc;
    }

    /**
     * @param Payone_Core_Model_Config_Payment $payment
     */
    public function setPayment(Payone_Core_Model_Config_Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Payone_Core_Model_Config_Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param Payone_Core_Model_Config_Protect $protect
     */
    public function setProtect(Payone_Core_Model_Config_Protect $protect)
    {
        $this->protect = $protect;
    }

    /**
     * @return Payone_Core_Model_Config_Protect
     */
    public function getProtect()
    {
        return $this->protect;
    }

}