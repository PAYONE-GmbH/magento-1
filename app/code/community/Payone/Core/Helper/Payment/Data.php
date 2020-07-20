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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Payment_Data extends Mage_Payment_Helper_Data
{
    /**
     * @var array
     */
    protected $deletedMethodTitles = array(
        'payone_masterpass' => 'Masterpass',
    );

    /**
     * @var array
     */
    protected $deletedMethodClearingTypes = array(
        'masterpass' => 'wlt'
    );

    public function getMethodInstance($code)
    {
        $instance = parent::getMethodInstance($code);

        if (!$instance && isset($this->deletedMethodTitles[$code])) {
            $instance = Mage::getModel('payone_core/payment_method_pseudo');
            $instance->setName($this->deletedMethodTitles[$code]);
        }

        return $instance;
    }

    /**
     * @param string $code
     * @return string
     */
    public function getDeletedMethodClearingType($code)
    {
        return isset($this->deletedMethodClearingTypes[$code]) ? $this->deletedMethodClearingTypes[$code] : '';
    }

    /**
     * Get and sort available payment methods for specified or current store
     *
     * array structure:
     *  $index => Varien_Simplexml_Element
     *
     * @param mixed $store
     * @param Mage_Sales_Model_Quote $quote
     * @return array
     */
    public function getStoreMethods($store = null, $quote = null)
    {
        $res = array();
        foreach ($this->getPaymentMethods($store) as $code => $methodConfig) {
            $prefix = self::XML_PATH_PAYMENT_METHODS . '/' . $code . '/';
            if (!$model = Mage::getStoreConfig($prefix . 'model', $store)) {
                continue;
            }
            $methodInstance = Mage::getModel($model);
            if (!$methodInstance) {
                continue;
            }
            $methodInstance->setStore($store);

            if (!$methodInstance->isAvailable($quote)) {
                /* if the payment method cannot be used at this time */
                continue;
            }

            /**
             * MAGE-494 : code ported from KlarnaModule
             * File : app/code/community/Klarna/Payments/Helper/Data.php
             */
            if ($methodInstance instanceof Klarna_Payments_Model_Payment_Payments) {
                /** @var Klarna_Payments_Model_Quote $klarnaQuote */
                $klarnaQuote = Mage::helper('klarna_payments/checkout')->getKlarnaQuote();
                foreach ($klarnaQuote->getPaymentMethodCategories() as $values) {

                    /** @var Klarna_Payments_Model_Payment_Payments $newRes */
                    $newRes = clone $methodInstance;
                    $newRes->setCategoryInformation($values);
                    $newRes->setCode('klarna_payments_' . $values['identifier']);
                    $newRes->setTitle($values['name']);

                    $res[] = $newRes;
                }

                continue;
            }

            $sortOrder = (int)$methodInstance->getConfigData('sort_order', $store);
            $methodInstance->setSortOrder($sortOrder);
            $res[] = $methodInstance;
        }

        usort($res, array($this, '_sortMethods'));
        return $res;
    }
}