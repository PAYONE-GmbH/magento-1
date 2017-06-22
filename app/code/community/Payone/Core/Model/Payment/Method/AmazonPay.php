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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Payment_Method_AmazonPay extends Payone_Core_Model_Payment_Method_Abstract
{
    protected $_canUseForMultishipping = true;
    protected $methodType = Payone_Core_Model_System_Config_PaymentMethodType::AMAZONPAY;
    protected $_code = Payone_Core_Model_System_Config_PaymentMethodCode::AMAZONPAY;
    protected $_formBlockType = 'payone_core/payment_method_form_amazonPay';
    protected $_infoBlockType = 'payone_core/payment_method_info_amazonPay';

    /**
     * @param \Payone_Api_Response_Error $response
     * @return string
     */
    public function getApiResponseErrorMessage($response)
    {
        return $response->getCustomermessage() . " " . $response->getErrormessage();
    }
}
