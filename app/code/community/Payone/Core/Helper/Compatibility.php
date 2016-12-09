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
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Compatibility
    extends Payone_Core_Helper_Abstract
{
    public function isEnabledDsdataNoState()
    {
        return $this->isModuleActive('Dsdata_NoState');
    }

    public function isEnabledGoMageLightCheckout()
    {
        return $this->isModuleActive('GoMage_Checkout');
    }

    protected function isModuleActive($name)
    {
        $isActive = false;
        $module = Mage::getConfig()->getModuleConfig($name);
        if ($module instanceof Varien_Simplexml_Element) {
            $active = (string)$module->active;
            if ($active === 'true') {
                $isActive = true;
            }
        }

        return $isActive;
    }

}