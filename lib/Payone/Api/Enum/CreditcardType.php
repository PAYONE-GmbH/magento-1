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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Enum
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Enum_CreditcardType
{
    //V: Visa
    const VISA = 'V';
    //M: MasterCard
    const MASTERCARD = 'M';
    //A: Amex
    const AMEX = 'A';
    //D: Diners
    const DINERS = 'D';
    //J: JCB
    const JCB = 'J';
    //O: Maestro International
    const MAESTRO_INTERNATIONAL = 'O';
    //U: Maestro UK
    const MAESTRO_UK = 'U';
    //C: Discover
    const DISCOVER = 'C';
    //B: Carte Bleue
    const CARTE_BLEUE = 'B';
}
