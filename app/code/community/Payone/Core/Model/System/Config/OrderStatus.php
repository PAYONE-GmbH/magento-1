<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order Statuses source model
 */
class Payone_Core_Model_System_Config_OrderStatus extends Payone_Core_Model_System_Config_Abstract
{
    // set null to enable all possible
    protected $_states = array(
        Mage_Sales_Model_Order::STATE_NEW,
        Mage_Sales_Model_Order::STATE_PENDING_PAYMENT,
        Mage_Sales_Model_Order::STATE_PROCESSING,
        Mage_Sales_Model_Order::STATE_HOLDED,
        Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW,
    );

    /**
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function toOptionArray()
    {
        if ($this->_states) {
            $statuses = Mage::getSingleton('sales/order_config')->getStateStatuses($this->_states);
        }
        else {
            $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        }

        $options = array();
        $options[] = array(
            'value' => '',
            'label' => Mage::helper('adminhtml')->__('-- Please Select --')
        );
        foreach ($statuses as $code => $label) {
            $options[] = array(
                'value' => $code,
                'label' => $label
            );
        }

        return $options;
    }

    /**
     * @return array
     */
    public function toGroupArray()
    {
        $states = $this->getStates();

        $stateStatusArray = array();
        foreach ($this->_states as $state) {
            $stateStatuses = Mage::getSingleton('sales/order_config')->getStateStatuses($state);

            if (array_key_exists($state, $states)) {
                $stateLabel = $states[$state];
            }
            else {
                $stateLabel = $this->helper()->__($state);
            }

            $stateStatusArray[$state] = array(
                'label' => $stateLabel,
                'values' => $stateStatuses
            );
        }

        return $stateStatusArray;
    }

    /**
     * @return array
     */
    public function toOptionGroupArray()
    {
        $data = array();
        $options = $this->toGroupArray();
        foreach ($options as $stateCode => $stateConfig) {
            if (!array_key_exists('values', $stateConfig)) {
                continue;
            }

            $stateValues = $stateConfig['values'];

            if (array_key_exists('label', $stateConfig)) {
                $stateLabel = $stateConfig['label'];
            }
            else {
                $stateLabel = $this->helper()->__($stateCode);
            }

            if (!array_key_exists($stateCode, $data)) {
                $data[$stateCode] = array(
                    'label' => $stateLabel,
                    'value' => array(),
                );
            }

            foreach ($stateValues as $key => $value) {
                $keyValue = $stateCode . '|' . $key;
                $data[$stateCode]['value'][$keyValue] = array(
                    'value' => $keyValue,
                    'label' => $this->helper()->__($value)
                );
            }
        }

        array_unshift($data, $this->helper()->__('-- Please Select --'));

        return $data;
    }

    /**
     * Wrap for comaptibility issues
     * @return array
     */
    protected function getStates()
    {
        if (version_compare($this->helper()->getMagentoVersion(), '1.5', '>')) {
            $states = Mage::getSingleton('sales/order_config')->getStates();
        }
        else {
            $states = array();
            foreach (Mage::getConfig()->getNode('global/sales/order/states')->children() as $state) {
                $label = (string)$state->label;
                $states[$state->getName()] = Mage::helper('sales')->__($label);
            }
        }

        return $states;
    }
}
