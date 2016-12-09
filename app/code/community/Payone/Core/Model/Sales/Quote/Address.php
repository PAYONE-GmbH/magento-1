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
 * @subpackage      Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Sales
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Sales_Quote_Address extends Mage_Sales_Model_Quote_Address
{
    /**
     * Validate address attribute values
     *
     * @return bool
     */
    public function validate()
    {
        $errors = $this->_beforeValidate()->toArray();

        if (!empty($errors)) {
            return $errors;
        }

        $errors = $this->_validate();

        if (!empty($errors) and $errors !== true) {
            return $errors;
        }

        $errors = $this->_afterValidate($errors)->toArray();

        if (empty($errors)) {
            return true;
        }

        return $errors;
    }

    protected function _validate()
    {
        $errors = array();

        if ($this->helperCompatibility()->isEnabledDsdataNoState()) {
            // we need to instantiate without Mage::getModel, cause no model is defined for NoState
            if (class_exists('Dsdata_NoState_Model_Quote_Address')) {
                $address = new Dsdata_NoState_Model_Quote_Address($this->getData());
                $errors = $address->validate();
            }
        }
        elseif ($this->helperCompatibility()->isEnabledGoMageLightCheckout()) {
            /**
             * @var $address GoMage_Checkout_Model_Quote_Address
             */
            $address = Mage::getModel('gomage_checkout/quote_address', $this->getData());
            $errors = $address->validate();
        }
        else {
            $errors = parent::validate();
        }

        return $errors;
    }

    /**
     * @return Varien_Object
     */
    protected function _beforeValidate()
    {
        $errors = new Varien_Object();

        $errors = $this->dispatchEvent($this->_eventPrefix . '_validate_before', $errors);

        return $errors;
    }

    /**
     * @param array|bool $errors
     * @return Varien_Object
     */
    protected function _afterValidate($errors)
    {
        if ($errors === true) {
            $errors = array();
        }

        if (!($errors instanceof Varien_Object)) {
            $errors = new Varien_Object($errors);
        }

        $errors = $this->dispatchEvent($this->_eventPrefix . '_validate_after', $errors);

        return $errors;
    }

    /**
     * used to dispatch Validate Events
     *
     * you should check the module/controller/action
     * Validate is triggered very often, but you may only want to do something in checkout
     *
     * @param string $eventName
     * @param Varien_Object $errors
     * @return Varien_Object
     */
    protected function dispatchEvent($eventName, Varien_Object $errors)
    {
        try {
            $params = array(
                $this->_eventObject => $this,
                'quote' => $this->getQuote(),
                'errors' => $errors,
                'full_action_name' => $this->getFullActionName(),
                'use_for_shipping' => $this->getUseForShippingFlag()
            );
            Mage::dispatchEvent($eventName, $params);
        }
        catch (Mage_Core_Exception $e) {
            $msg = $this->helper()->__($e->getMessage());
            $errors->addData(array($msg));
        }
        catch (Exception $e) {
            $msg = $this->helper()->__('Entered Address could not be validated.');
            $errors->addData(array($msg));
        }

        return $errors;
    }

    /**
     * @return null|string
     */
    protected function getUseForShippingFlag()
    {
        $request = Mage::app()->getRequest();
            $billingParam = $request->getPost('billing');
        if (!is_array($billingParam) or !isset($billingParam['use_for_shipping'])) {
            return null;
        }

        return $billingParam['use_for_shipping'];
    }

    protected function getFullActionName()
    {
        $controller = Mage::app()->getFrontController();
        $request = $controller->getRequest();
        $action = $controller->getAction();
        if ($action) {
            $fullActionName = $action->getFullActionName('/');
        }
        else {
            $fullActionName = $request->getModuleName() . '/' .
                    $request->getControllerName() . '/' .
                    $request->getActionName();
        }

        return $fullActionName;
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helper()
    {
        return Mage::helper('payone_core');
    }

    /**
     * @return Payone_Core_Helper_Compatibility
     */
    protected function helperCompatibility()
    {
        return Mage::helper('payone_core/compatibility');
    }

}