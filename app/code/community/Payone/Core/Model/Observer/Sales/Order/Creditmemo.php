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
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Model_Observer_Sales_Order_Creditmemo
    extends Payone_Core_Model_Observer_Abstract
{
    /**
     * MAGE-391: introduced to fix tax calculation of CreditMemo
     * in case of partial refund
     *
     * @param Varien_Event_Observer $observer
     */
    public function baseCurrencyTaxRecalculation(Varien_Event_Observer $observer)
    {
        /** @var Mage_Sales_Model_Order_Creditmemo $creditmemo */
        $creditmemo = $observer->getData('creditmemo');

        // If first refund, no need to adjust
        $order = $creditmemo->getOrder();
        if (isset($order) && $order->getBaseTotalRefunded() !== null) {

            // Fix item base tax amount
            // keeping track of initial calculated amount, to fix total tax of credit memo
            $initialBaseItemTax = 0;
            $baseSubtotal = null;
            /** @var Mage_Sales_Model_Order_Creditmemo_Item $_item */
            foreach ($creditmemo->getAllItems() as $_item) {
                if ($_item->getBaseTaxAmount() != $_item->getTaxAmount()) {
                    $initialBaseItemTax += ($_item->getBaseTaxAmount());

                    $baseTaxAmount = ($_item->getBasePriceInclTax() - $_item->getBasePrice()) * $_item->getQty();
                    $_item->setBaseTaxAmount($baseTaxAmount);
                    $_item->setBaseRowTotal($_item->getBasePrice() * $_item->getQty());

                    if ($baseSubtotal == null) {
                        $baseSubtotal = 0;
                    }
                    $baseSubtotal += $_item->getBaseRowTotal();
                }
            }

            // Fix the Tax amount of the whole credit memo
            if ($creditmemo->getBaseTaxAmount() != $creditmemo->getTaxAmount()) {
                $initialBaseSubtotal = $creditmemo->getBaseSubtotal();
                $initialBaseTaxAmount = $creditmemo->getBaseTaxAmount();

                if (isset($baseSubtotal)) {
                    $recalculatedBaseItemTax = ($creditmemo->getBaseSubtotalInclTax() - $baseSubtotal);
                    $creditmemo->setBaseTaxAmount(
                        $recalculatedBaseItemTax
                        + $initialBaseTaxAmount
                        - $initialBaseItemTax
                    );

                    $creditmemo->setBaseSubtotal($baseSubtotal);
                }

                $creditmemo->setBaseGrandTotal(
                    $creditmemo->getBaseGrandTotal()
                    + $creditmemo->getBaseSubtotal() - $initialBaseSubtotal
                    - $initialBaseTaxAmount
                    + $creditmemo->getBaseTaxAmount()
                );
            }
        }
    }
}
