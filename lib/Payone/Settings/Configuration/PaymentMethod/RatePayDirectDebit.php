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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/**
 * Class Payone_Settings_Configuration_PaymentMethod_RatePayDirectDebit
 */
class Payone_Settings_Configuration_PaymentMethod_RatePayDirectDebit
    extends Payone_Settings_Configuration_Abstract
{
    /**
     * @return array
     */
    public function getTypes()
    {
        $constants = $this->getClassConstants('Payone_Api_Enum_RatepayDirectDebitType');

        $constants = array_flip($constants);

        return $constants;
    }

}
