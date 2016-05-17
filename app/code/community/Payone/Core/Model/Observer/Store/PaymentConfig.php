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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Observer_Store_PaymentConfig
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function createByWebsite(Varien_Event_Observer $observer)
    {
        /** @var $website Mage_Core_Model_Website */
        $website = $observer->getWebsite();

        if (!$website->isObjectNew()) {
            return;
        }

        $service = $this->getFactory()->getServiceConfigPaymentMethodCreate();
        $service->executeByWebsite($website);

    }


    /**
     * @param Varien_Event_Observer $observer
     */
    public function createByStore(Varien_Event_Observer $observer)
    {
        /** @var $store Mage_Core_Model_Store */
        $store = $observer->getStore();

        if (!$store->isObjectNew()) {
            return;
        }

        $service = $this->getFactory()->getServiceConfigPaymentMethodCreate();
        $service->executeByStore($store);

    }

}