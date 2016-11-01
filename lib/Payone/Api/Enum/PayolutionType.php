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

class Payone_Api_Enum_PayolutionType
{
    const PYV = 'PYV'; // Payolution-Invoicing
    const PYM = 'PYM'; // Payolution-Monthly
    const PYS = 'PYS'; // Payolution-Installment
    const PYD = 'PYD'; // Payolution-Debit
    
    const PYV_FULL = 'Payolution-Invoicing';
    const PYM_FULL = 'Payolution-Monthly';
    const PYS_FULL = 'Payolution-Installment';
    const PYD_FULL = 'Payolution-Debit';
    
    public static function getLongType($sType) 
    {
        $sLongType = '';
        switch ($sType) {
            case self::PYV:
                $sLongType = self::PYV_FULL;
                break;
            case self::PYM:
                $sLongType = self::PYM_FULL;
                break;
            case self::PYS:
                $sLongType = self::PYS_FULL;
                break;
            case self::PYD:
                $sLongType = self::PYD_FULL;
                break;
            default:
                break;
        }

        return $sLongType;
    }
    
}