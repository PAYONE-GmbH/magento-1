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
class Payone_Core_Helper_Url
    extends Payone_Core_Helper_Abstract
{
    /**
     * Retrieve complete Magento Url
     *
     * @param string $controllerAction in the form "module/controller/action"
     * @return string
     */
    public function getMagentoUrl($controllerAction)
    {
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();

        $url = Mage::getUrl($controllerAction, array(
            '_nosid' => true,
            '_secure' => $isSecure));
        return $url;
    }

    /**
     * @return string
     */
    public function getSuccessUrl()
    {
        $successurl = $this->getMagentoUrl('payone_core/checkout_onepage_payment/success', false);

        return $successurl;
    }

    /**
     * @return string
     */
    public function getErrorUrl()
    {
        $errorurl = $this->getMagentoUrl('payone_core/checkout_onepage_payment/error', false);

        return $errorurl;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $backurl = $this->getMagentoUrl('payone_core/checkout_onepage_payment/back', false);

        return $backurl;
    }

}