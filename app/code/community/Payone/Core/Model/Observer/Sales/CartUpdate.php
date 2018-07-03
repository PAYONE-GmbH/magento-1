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
 * @subpackage      Observer
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Observer_Sales_CartUpdate extends Payone_Core_Model_Observer_Abstract
{
    /**
     * Start the cancellation handler
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function checkExternalCheckoutActive(Varien_Event_Observer $observer)
    {
        /** @var $cancellation Payone_Core_Model_Handler_Cancellation */
        $cancellation = Mage::getModel('payone_core/handler_cancellation');
        $cancellation->handle();
    }
}
