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
class Payone_Core_Model_Config_Misc_Creditmemo extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var string
     */
    protected $adjustment_refund_sku = '';
    /**
     * @var string
     */
    protected $adjustment_refund_name = '';
    /**
     * @var string
     */
    protected $adjustment_fee_sku = '';
    /**
     * @var string
     */
    protected $adjustment_fee_name = '';

    /**
     * @param string $adjustment_fee_name
     */
    public function setAdjustmentFeeName($adjustment_fee_name)
    {
        $this->adjustment_fee_name = $adjustment_fee_name;
    }

    /**
     * @return string
     */
    public function getAdjustmentFeeName()
    {
        return $this->adjustment_fee_name;
    }

    /**
     * @param string $adjustment_fee_sku
     */
    public function setAdjustmentFeeSku($adjustment_fee_sku)
    {
        $this->adjustment_fee_sku = $adjustment_fee_sku;
    }

    /**
     * @return string
     */
    public function getAdjustmentFeeSku()
    {
        return $this->adjustment_fee_sku;
    }

    /**
     * @param string $adjustment_refund_name
     */
    public function setAdjustmentRefundName($adjustment_refund_name)
    {
        $this->adjustment_refund_name = $adjustment_refund_name;
    }

    /**
     * @return string
     */
    public function getAdjustmentRefundName()
    {
        return $this->adjustment_refund_name;
    }

    /**
     * @param string $adjustment_refund_sku
     */
    public function setAdjustmentRefundSku($adjustment_refund_sku)
    {
        $this->adjustment_refund_sku = $adjustment_refund_sku;
    }

    /**
     * @return string
     */
    public function getAdjustmentRefundSku()
    {
        return $this->adjustment_refund_sku;
    }
}