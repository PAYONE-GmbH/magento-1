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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Enum
 * @author          Ronny Schröder
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 */
class Payone_Api_Enum_GenericpaymentAction
{
    /**
     * initialize configuration parameters for Amazon Pay
     */
    const AMAZONPAY_GETCONFIGURATION = 'getconfiguration';
    /**
     * set the work order's Amazon Pay order reference details
     */
    const AMAZONPAY_SETORDERREFERENCEDETAILS = 'setorderreferencedetails';
    /**
     * get the work order's Amazon Pay order reference details
     */
    const AMAZONPAY_GETORDERREFERENCEDETAILS = 'getorderreferencedetails';
    /**
     * initalize paypal express checkout process
     */
    const PAYPAL_ECS_SET_EXPRESSCHECKOUT='setexpresscheckout';
    
    /**
     * get customer shipping address from paypal
     */
    const PAYPAL_ECS_GET_EXPRESSCHECKOUTDETAILS='getexpresscheckoutdetails';
    
    const RATEPAY_PROFILE = 'profile';

    const RATEPAY_REQUEST_TYPE_CALCULATION = "calculation";
    const RATEPAY_DEBIT_TYPE_BANK_TRANSER = "BANK-TRANSFER";
    const RATEPAY_DEBIT_TYPE_DIRECT_DEBIT = "DIRECT-DEBIT";
    const PAYOLUTION_PRE_CHECK = 'pre_check';
    
    const PAYOLUTION_CALCULATION = 'calculation';
}
