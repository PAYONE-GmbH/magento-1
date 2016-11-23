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
class Payone_Core_Block_Payment_Method_Form_SafeInvoice
    extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    protected $hasTypes = true;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/safe_invoice.phtml');
    }

    /**
     * @override To prevent display of fee config on payment method, as there might be different fees for each safe_invoice type
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
        return $this->getFactory()->getModelSystemConfigSafeInvoiceType()->toSelectArray();
    }

    /**
     * Retrieve the payment config method id from Quote.
     * If it matches payment method, return it, otherwise 0
     * @return int|mixed
     */
    public function getPaymentMethodConfigId()
    {
        $preselectedConfigId = $this->getInfoData('payone_config_payment_method_id');

        $preselectPossible = false;
        foreach ($this->getTypes() as $type) {
            if ($type['config_id'] == $preselectedConfigId) {
                $preselectPossible = true;
            }
        }

        if ($preselectPossible) {
            return $preselectedConfigId;
        }
        else {
            return 0;
        }
    }

    /**
     * @return string
     */
    public function getBlockHtmlKlarna()
    {
        /** @var Payone_Core_Block_Payment_Method_Form_SafeInvoice_Klarna $block */
        $block = $this->getLayout()->createBlock('payone_core/payment_method_form_safeInvoice_klarna');
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
            if ($type['code'] == Payone_Api_Enum_FinancingType::KLV) {
                return true;
            }
        } elseif (count($types) > 1) {
            foreach ($types as $type) {
                if ($type['code'] == Payone_Api_Enum_FinancingType::KLV) {
                    return true;
                }
            }
        }

        return false;
    }
}