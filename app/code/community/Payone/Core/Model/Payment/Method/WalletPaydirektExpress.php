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
* @subpackage      Payment_Method
* @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
* @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
* @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
* @link            http://www.fatchip.com
*/
class Payone_Core_Model_Payment_Method_WalletPaydirektExpress extends Payone_Core_Model_Payment_Method_Abstract
{
    /** @var string */
    protected $methodType = Payone_Core_Model_System_Config_PaymentMethodType::WALLETPAYDIREKTEXPRESS;

    /** @var string */
    protected $_code = Payone_Core_Model_System_Config_PaymentMethodCode::WALLETPAYDIREKTEXPRESS;

    /** @var string */
    protected $_formBlockType = 'payone_core/payment_method_form_wallet';

    /** @var string */
    protected $_infoBlockType = 'payone_core/payment_method_info_wallet';

    /** @var Payone_Core_Model_Config_Payment_Method_Interface[] */
    protected $matchingConfigs = array();

    /** @var bool */
    protected $_canUseInternal = false;

    /**
     * @api
     *
     * To be used in Form_Block, which has to display all wallet types
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return Payone_Core_Model_Config_Payment_Method_Interface[]
     */
    public function getAllConfigsByQuote(Mage_Sales_Model_Quote $quote)
    {
        if (empty($this->matchingConfigs)) {
            $configStore = $this->getConfigStore($quote->getStoreId());

            $this->matchingConfigs = $configStore->getPayment()->getMethodsForQuote($this->methodType, $quote);
        }

        return $this->matchingConfigs;
    }

    /**
     * @param string $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $oSession = Mage::getSingleton('checkout/session');
        $oSession->setPayoneExternalCheckoutActive(true);
        $this->redirectUrl = $redirectUrl;
    }
}
