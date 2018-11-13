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
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/* MAGE-392: CreditCard Iframe method removed
 * class adapted to decouple from the configuration,
 * content kept for consistency with existing orders
 */
class Payone_Core_Model_Payment_Method_CreditcardIframe extends Payone_Core_Model_Payment_Method_Abstract
{
    protected $_canUseForMultishipping = true;
    protected $_mustTransimitInvoicingData = true;

    protected $methodType = 'creditcard_iframe';

    protected $_code = 'payone_creditcard_iframe';

    protected $_formBlockType = 'payone_core/payment_method_form_creditcard';


}