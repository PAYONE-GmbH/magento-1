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
 * @copyright       Copyright (c) 2016 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Api_Enum_RatepayType
{
    const RPV = 'RPV'; // Ratepay-Invoicing
    const RPS = 'RPS'; // Ratepay-Installment
    #const RPD = 'RPD'; // Ratepay-Debit not implemented yet
    #const RPP = 'RPP'; // Ratepay-Prepayment not implemented yet
}