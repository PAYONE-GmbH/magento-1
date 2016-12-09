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
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@votum.com> - www.noovias.com
 * @author          Edward Mateja <edward.mateja@votum.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@votum.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.com
 */
class Payone_Core_Model_System_Config_CurrencyUsage extends Payone_Core_Model_System_Config_Abstract
{
    const USE_DEFAULT_CURRENCY = 0;
    const USE_BASE_CURRENCY = 1;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            self::USE_DEFAULT_CURRENCY => 'Use Default Display Currency',
            self::USE_BASE_CURRENCY => 'Use Base Currency'
        );
    }
}