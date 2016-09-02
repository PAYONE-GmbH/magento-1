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
 * @package         Payone_Settings
 * @subpackage      Configuration
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Configuration
 * @copyright       Copyright (c) 2016 <support@e3n.de>
 * @author          Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            https://e3n.de/
 */


/**
 * Class Payone_Settings_Configuration_PaymentMethod_RatePay
 */
class Payone_Settings_Configuration_PaymentMethod_RatePay
    extends Payone_Settings_Configuration_Abstract
{
    /**
     * @return array
     */
    public function getTypes()
    {
        $constants = $this->getClassConstants('Payone_Api_Enum_RatepayType');

        $constants = array_flip($constants);

        return $constants;
    }

}
