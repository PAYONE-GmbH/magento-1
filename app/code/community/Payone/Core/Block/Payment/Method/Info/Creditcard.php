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
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Payment_Method_Info_Creditcard
    extends Payone_Core_Block_Payment_Method_Info_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/info/creditcard.phtml');

    }

    /**
     * @return string
     */
    public function getCcOwner()
    {
        return $this->getInfo()->getCcOwner();
    }

    /**
     * @return string
     */
    public function getCcNumberEnc()
    {
        return $this->getInfo()->getCcNumberEnc();
    }

    /**
     * @return string
     */
    public function getCcExpMonth()
    {
        $sMonth = $this->getInfo()->getCcExpMonth();
        if(!$sMonth) {
            $sDate = $this->getInfo()->getPayoneCardexpiredate();
            if($sDate && strlen($sDate) == 4) {
                $sMonth = substr($sDate, 2, 2);
            }
        }

        return $sMonth;
    }

    /**
     * @return string
     */
    public function getCcExpYear()
    {
        $sYear = $this->getInfo()->getCcExpYear();
        if(!$sYear) {
            $sDate = $this->getInfo()->getPayoneCardexpiredate();
            if($sDate && strlen($sDate) == 4) {
                $sYear = substr($sDate, 0, 2);
            }
        }

        return $sYear;
    }

    /**
     * @return string
     */
    public function getCcType()
    {
        $model = $this->getFactory()->getModelSystemConfigCreditCardType();
        $array = $model->toSelectArray();
        return $array[$this->getInfo()->getCcType()];
    }
}