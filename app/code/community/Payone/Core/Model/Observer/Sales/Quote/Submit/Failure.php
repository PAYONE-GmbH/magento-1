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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Core_Model_Observer_Sales_Quote_Submit_Failure
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * Retrieves api log entry from session and writes it to the db
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function protocol(Varien_Event_Observer $observer)
    {
        $oSession = Mage::getSingleton('checkout/session');

        /** @var $domainObject Payone_Core_Model_Repository_Api */
        $domainObject = $oSession->getPayoneApiLogEntry();
        if ($domainObject) {
            $domainObject->save();
            $oSession->unsPayoneApiLogEntry();
        }
    }
}
