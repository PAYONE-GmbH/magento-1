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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Payment_Method_Info_Abstract
    extends Mage_Payment_Block_Info
{

    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $paymentConfig = null;

    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     * @throws Payone_Core_Exception_PaymentMethodConfigNotFound
     */
    public function getPaymentConfig()
    {
        if ($this->paymentConfig === null) {
            /** @var $method Payone_Core_Model_Payment_Method_Abstract */
            $method = $this->getMethod();
            $paymentInfo = $method->getInfoInstance();
            if ($paymentInfo instanceof Mage_Sales_Model_Order_Payment) {
                $order = $paymentInfo->getOrder();
                $config = $this->helperConfig()->getConfigPaymentMethodByOrder($order);
            }
            elseif ($paymentInfo instanceof Mage_Sales_Model_Quote_Payment) {
                $quote = $paymentInfo->getQuote();
                $config = $this->helperConfig()->getConfigPaymentMethodByQuote($quote);
            }
            if(empty($config))
            {
                $message = 'Payment method configuration not found.';
                throw new Payone_Core_Exception_PaymentMethodConfigNotFound($message);
            }
            $this->paymentConfig = $config;
        }
        return $this->paymentConfig;
    }

    /**
     * @return string
     */
    public function getMethodTitle()
    {
        $paymentconfig = $this->getPaymentConfig();
        return $paymentconfig->getName();
    }

    /**
     * @return int
     */
    public function getLastTransId()
    {
        return $this->getInfo()->getLastTransId();
    }

    /**
     * @return Payone_Core_Helper_Config
     */
    protected function helperConfig()
    {
        return $this->getFactory()->helperConfig();
    }

    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }
        return $this->factory;
    }

    /**
     * @param string $text
     * @return string
     */
    public function strToXXX($text) {
        if(!empty($text)) {
            $result = str_repeat('x', strlen($text) - 8);
            $result = substr($text, 0, 4).$result.substr($text, -4);
        } else {
            $result = $text;
        }
        return $result;
    }
}