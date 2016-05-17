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
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */
class Payone_Core_Block_Payment_Method_Form_Financing
    extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    protected $hasTypes = true;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/financing.phtml');
    }

    /**
     * @override To prevent display of fee config on payment method, as there might be different fees for each financing type
     *
     * @return string
     */
    public function getMethodLabelAfterHtml()
    {
        return '';
    }

    /**
     * @return array
     */
    protected function getSystemConfigMethodTypes()
    {
        return $this->getFactory()->getModelSystemConfigFinancingType()->toSelectArray();
    }

    /**
     * @return string
     */
    public function getBlockHtmlKlarna()
    {
        /** @var Payone_Core_Block_Payment_Method_Form_Financing_Klarna $block */
        $block = $this->getLayout()->createBlock('payone_core/payment_method_form_financing_klarna');
        $block->setQuote($this->getQuote());
        $block->setPaymentMethodConfig($this->getPaymentConfig());
        $html = $block->toHtml();
        return $html;
    }

    /**
     * @return bool
     */
    public function showBlockHtmlKlarna()
    {
        $types = $this->getTypes();

        if (count($types) == 1) {
            $type = array_pop($types);
            if ($type['code'] == Payone_Api_Enum_FinancingType::KLS) {
                return true;
            }
        } elseif (count($types) > 1) {
            foreach ($types as $type) {
                if ($type['code'] == Payone_Api_Enum_FinancingType::KLS) {
                    return true;
                }
            }
        }
        return false;
    }
}