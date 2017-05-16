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
class Payone_Core_Block_Payment_Method_Form_OnlineBankTransferIdl
    extends Payone_Core_Block_Payment_Method_Form_OnlineBankTransfer
{
    /**
     * @var bool
     */
    protected $hasTypes = true;
    /**
     * @var null
     */
    protected $config = null;
    /**
     * @var string
     */
    protected $formattedFeePrice = '';
    /**
     * @var bool
     */
    protected $isCvc = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/onlinebanktransferidl.phtml');
    }


    /**
     * @return array
     */
    public function onlineBankTransferTypeMapping()
    {
        return $onlineBankTransferPayment = array(
            Payone_Core_Model_System_Config_PaymentMethodCode::ONLINEBANKTRANSFERIDL => Payone_Api_Enum_OnlinebanktransferType::IDEAL
        );
    }


    public function getBlockHtmlBankGroupIdl()
    {
        /** @var $block Mage_Core_Block_Template */
        $block = $this->getLayout()->createBlock('core/template');
        $block->setTemplate('payone/core/payment/method/form/onlinebanktransfer/bankgroupidl.phtml');
        $block->setMethodCode($this->getMethodCode());

        if($this->getSavedCustomerData('payone_bank_group')){
            $block->setSavedCustomerBankGroup($this->getSavedCustomerData('payone_bank_group'));
        }

        $html = $block->toHtml();
        return $html;
    }
}