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
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Core_Block_Checkout_Onepage_Success_SepaMandatePdfLink
    extends Mage_Core_Block_Template
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * after block is rendered we should clear relevant checkout session fields to avoid
     * falsy interpretation on further checkout passes
     *
     * @param string $html
     * @return string
     */
    protected function _afterToHtml($html)
    {
        $this->getCheckoutSession()->unsPayoneSepaMandateDownloadEnabled();
        $this->getCheckoutSession()->unsPayoneSepaMandateIdentification();
        return parent::_afterToHtml($html);
    }

    /**
     * @return Mage_Checkout_Model_Session
     */
    protected function getCheckoutSession()
    {
        return $this->getFactory()->getSingletonCheckoutSession();
    }

    /**
     * @return bool
     */
    public function showLink()
    {
        $orderId = $this->getOrderId();
        if (!$orderId) {
            return false;
        }
        $order = $this->getFactory()->getModelSalesOrder();
        $order->load($orderId);

        if ($order->getPayment()->getMethod() != Payone_Core_Model_System_Config_PaymentMethodCode::DEBITPAYMENT) {
            return false;
        }

        $downloadEnabled = $this->getCheckoutSession()->getPayoneSepaMandateDownloadEnabled();
        $mandateIdentification = $this->getMandateIdentification();

        if (!$downloadEnabled or empty($mandateIdentification)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getMandateIdentification()
    {
        return $this->getCheckoutSession()->getPayoneSepaMandateIdentification();
    }

    /**
     * @return string
     */
    public function getLinkText()
    {
        return $this->helperPayone()->__('Your mandate as PDF');
    }

    /**
     * @return string
     */
    public function getLinkHref()
    {
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();

        $url = Mage::getUrl('payone_core/checkout_onepage_success/getSepaPdf',
            array(
                '_nosid' => true,
                '_secure' => $isSecure,
                'order_id' => $this->getOrderId(),
                'mandate_identification' => $this->getMandateIdentification()
            )
        );

        return $url;
    }

    /**
     * @return int
     */
    protected function getOrderId()
    {
        return $this->getCheckoutSession()->getLastOrderId();
    }

    /**
     * @param \Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }
        return $this->factory;
    }

    /**
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayone()
    {
        return $this->getFactory()->helper();
    }
}