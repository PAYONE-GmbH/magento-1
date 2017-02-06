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
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2017 <support@e3n.de> - www.e3n.de
 * @author          Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.e3n.de
 */

/**
 * Class Payone_Core_Block_Payment_Method_Info_PayolutionDebit
 */
class Payone_Core_Block_Payment_Method_Info_PayolutionInstallment extends Payone_Core_Block_Payment_Method_Info_Payolution
{

    protected function _construct() 
    {
        $this->setTemplate('payone/core/payment/method/info/payolutioninstallment.phtml');
    }
}