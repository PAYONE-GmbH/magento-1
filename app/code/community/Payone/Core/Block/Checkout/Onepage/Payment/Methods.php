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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Checkout_Onepage_Payment_Methods
    extends Mage_Checkout_Block_Onepage_Payment_Methods
{
    const RESULT_HAVE_TO_FILTER_METHODS = 'have_to_filter_methods';
    const RESULT_ALLOWED_METHODS = 'allowed_methods';

    protected $_eventPrefix = 'checkout_onepage_payment_methods';

    /**
     * @var Mage_Payment_Model_Method_Abstract[]
     */
    protected $methods = null;

    /**
     * @return array
     */
    public function getMethods()
    {
        if ($this->methods !== null) {
            return $this->methods;
        }

        //
        $this->methods = parent::getMethods();

        $allowedMethods = $this->getAllowedMethods();

        // List of allowed methods has to be rewritten
        if ($allowedMethods !== true) {
            $result = array();
            foreach ($this->methods as $method) {
                /**
                 * @var $method Mage_Payment_Model_Method_Abstract
                 */
                $methodAvailable = $allowedMethods->getData($method->getCode());
                if ($methodAvailable == 1) {
                    $result[] = $method;
                }
            }

            // Overwrite Methods with the above created result array
            $this->methods = $result;
        }

        $aRestrictedMethods = $this->getRestrictedMethods();
        if (!empty($aRestrictedMethods)) {
            $this->methods = array_filter(
                $this->methods,
                function (Mage_Payment_Model_Method_Abstract $oMethod) use ($aRestrictedMethods)
                {
                    return !in_array($oMethod->getCode(), $aRestrictedMethods);
                }
            );
        }

        return $this->methods;
    }

    /**
     * @return array
     */
    public function getRestrictedMethods()
    {
        $aRestrictedMethods = array();

        /** @var Payone_Core_Model_Domain_PaymentBan $paymentBanModel */
        $paymentBanModel = Mage::getModel('payone_core/domain_paymentBan');
        $paymentBans = $paymentBanModel->loadByCustomerId($this->getQuote()->getCustomerId());
        foreach ($paymentBans as $paymentBan) {
            $dtToday = new DateTime();
            $dtBanStartDate = new DateTime($paymentBan->getFromDate());
            $dtBanEndDate = new DateTime($paymentBan->getToDate());

            if (
                $dtToday->getTimestamp() > $dtBanStartDate->getTimestamp()
                && $dtToday->getTimestamp() < $dtBanEndDate->getTimestamp()
            ) {
                $aRestrictedMethods[] = $paymentBan->getPaymentMethod();
            }
        }

        return $aRestrictedMethods;
    }

    /**
     * @return bool|Varien_Object
     */
    protected function getAllowedMethods()
    {
        $allowedMethods = $this->getData('allowed_methods');

        // Allowed Methods can be cached in registry
        if ($allowedMethods == null) {
            /**
             * @var $allowedMethods Varien_Object
             */
            $allowedMethods = Mage::registry('payment_methods_allowed_methods');
        }

        // Determine allowed methods using event
        if ($allowedMethods == null) {
            // Trigger Event to determine allowed Methods
            $settings = $this->dispatchEvent();

            //
            if (!$settings->getData(self::RESULT_HAVE_TO_FILTER_METHODS)) {
                return true;
            }

            /**
             * @var $allowedMethods Varien_Object
             */
            $allowedMethods = $settings->getData(self::RESULT_ALLOWED_METHODS);
        }

        $this->setData('allowed_methods', $allowedMethods);

        return $allowedMethods;
    }

    /**
     * @return Varien_Object
     */
    protected function dispatchEvent()
    {
        $settings = new Varien_Object();
        $settings->setData(self::RESULT_HAVE_TO_FILTER_METHODS, false);

        $allowedMethods = new Varien_Object();
        $settings->setData(self::RESULT_ALLOWED_METHODS, $allowedMethods);

        $parameters = array(
            'settings' => $settings,
            'quote' => $this->getQuote(),
            'full_action_name' => $this->getFullActionName(),
        );

        Mage::dispatchEvent($this->_eventPrefix . '_get_methods', $parameters);

        return $settings;
    }

    protected function getFullActionName()
    {
        return $this->getAction()->getFullActionName('/');
    }

    public function getSelectedMethodCode()
    {
        if ($codeMethod = parent::getSelectedMethodCode()) {
            $sessionCodeMethod = Mage::getModel('sales/quote')
                ->load(Mage::getSingleton('checkout/session')->getQuoteId())
                ->getPayment()
                ->getMethodInstance()
                ->getCode();
            if ($codeMethod != $sessionCodeMethod) {
                return $sessionCodeMethod;
            }
        }
        return parent::getSelectedMethodCode();
    }
}
