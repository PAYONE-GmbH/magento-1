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
 * @copyright       Copyright (c) 2017 <support@e3n.de> - www.e3n.de
 * @author          Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.e3n.de
 */

/**
 * Class Payone_Core_Model_Payment_Method_PayolutionDebit
 */
class Payone_Core_Model_Payment_Method_PayolutionDebit extends Payone_Core_Model_Payment_Method_Abstract
{
    /**
     * @var bool
     */
    protected $_canUseForMultishipping = true;
    /**
     * @var string
     */
    protected $methodType = Payone_Core_Model_System_Config_PaymentMethodType::PAYOLUTIONDEBIT;
    /**
     * @var string
     */
    protected $_code = Payone_Core_Model_System_Config_PaymentMethodCode::PAYOLUTIONDEBIT;
    /**
     * @var string
     */
    protected $_formBlockType = 'payone_core/payment_method_form_payolution';
    /**
     * @var string
     */
    protected $_infoBlockType = 'payone_core/payment_method_info_payolution';
    /**
     * @var bool
     */
    protected $_blIpMandatory = true;

    /**
     * @param $response
     * @return string
     */
    public function getApiResponseErrorMessage($response)
    {
        return $this->helper()->__('PAYONE_PAYOLUTION_API_ERRORMESSAGE');
    }

}