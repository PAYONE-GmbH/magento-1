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
class Payone_Core_Model_Config_Misc extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var Payone_Core_Model_Config_Misc_TransactionstatusForwarding
     */
    protected $transactionstatusForwarding = null;
    /**
     * @var Payone_Core_Model_Config_Misc_ShippingCosts
     */
    protected $shippingCosts = null;

    /** @var Payone_Core_Model_Config_Misc_Discount */
    protected $discount = null;

    /**
     * @var Payone_Core_Model_Config_Misc_Creditmemo
     */
    protected $creditmemo = null;
    /**
     * @var Payone_Core_Model_Config_Misc_EmailError
     */
    protected $emailError = null;
    /**
     * @var Payone_Core_Model_Config_Misc_EmailAvs
     */
    protected $emailAvs = null;
    /**
     * @var Payone_Core_Model_Config_Misc_TransactionstatusProcessing
     */
    protected $transactionstatusProcessing = null;

    /**
     * @param Payone_Core_Model_Config_Misc_EmailAvs $emailConfigAvs
     */
    public function setEmailAvs(Payone_Core_Model_Config_Misc_EmailAvs $emailConfigAvs)
    {
        $this->emailAvs = $emailConfigAvs;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_EmailAvs
     */
    public function getEmailAvs()
    {
        return $this->emailAvs;
    }

    /**
     * @param Payone_Core_Model_Config_Misc_EmailError $emailConfigError
     */
    public function setEmailError(Payone_Core_Model_Config_Misc_EmailError $emailConfigError)
    {
        $this->emailError = $emailConfigError;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_EmailError
     */
    public function getEmailError()
    {
        return $this->emailError;
    }

    /**
     * @param Payone_Core_Model_Config_Misc_ShippingCosts $shippingCosts
     */
    public function setShippingCosts(Payone_Core_Model_Config_Misc_ShippingCosts $shippingCosts)
    {
        $this->shippingCosts = $shippingCosts;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_ShippingCosts
     */
    public function getShippingCosts()
    {
        return $this->shippingCosts;
    }

    /**
     * @param Payone_Core_Model_Config_Misc_TransactionstatusForwarding $transactionstatusForwarding
     */
    public function setTransactionstatusForwarding(Payone_Core_Model_Config_Misc_TransactionstatusForwarding $transactionstatusForwarding)
    {
        $this->transactionstatusForwarding = $transactionstatusForwarding;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_TransactionstatusForwarding
     */
    public function getTransactionstatusForwarding()
    {
        return $this->transactionstatusForwarding;
    }

    /**
     * @param Payone_Core_Model_Config_Misc_TransactionstatusProcessing $transactionstatusProcessing
     */
    public function setTransactionstatusProcessing($transactionstatusProcessing)
    {
        $this->transactionstatusProcessing = $transactionstatusProcessing;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_TransactionstatusProcessing
     */
    public function getTransactionstatusProcessing()
    {
        return $this->transactionstatusProcessing;
    }

    /**
     * @param Payone_Core_Model_Config_Misc_Creditmemo $creditmemo
     */
    public function setCreditmemo($creditmemo)
    {
        $this->creditmemo = $creditmemo;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_Creditmemo
     */
    public function getCreditmemo()
    {
        return $this->creditmemo;
    }

    /**
     * @param Payone_Core_Model_Config_Misc_Discount $discount
     */
    public function setDiscount(Payone_Core_Model_Config_Misc_Discount $discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return Payone_Core_Model_Config_Misc_Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}
