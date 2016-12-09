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
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2016 <support@e3n.de> - www.e3n.de
 * @author          Tim Rein <tim.rein@e3n.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.e3n.de
 */

/**
 * Class Payone_Core_Block_Checkout_RatePayInstallmentplan
 */
class Payone_Core_Block_Checkout_RatePayInstallmentplan extends Mage_Core_Block_Template
{
    /**
     * show calculated installmentplan
     * @param $result
     * @return string
     */
    public function showRateResultHtml($result) 
    {

        $numberOfRates = $result['last-rate']?$result['number-of-rates']-1:$result['number-of-rates'];
        $html = '
        <h2 class="ratepay-mid-heading"><b>' . $this->__('lang_individual_rate_calculation') . '</b></h2>
        <table id="ratepay-InstallmentTerms" cellspacing="0">
            <tr>
                <th>
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('/images/payone/info-icon.png') .'"/></div>
                        <div class="ratepay-FloatLeft">' . $this->__('lang_cash_payment_price') . ':</div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoPaymentPrice">' . $this->__('lang_mouseover_cash_payment_price') . '</div>
                         </div>
                     </div>
                </th>
                <td>&nbsp;' . $result['amount'] . '</td>
                <td class="ratepay-TextAlignLeft">&euro;</td>
            </tr>
            <tr class="piTableHr">
                <th>
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('/images/payone/info-icon.png') .'"/></div>
                         <div class="ratepay-FloatLeft">' . $this->__('lang_service_charge') . ':</div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoServiceCharge">' . $this->__('lang_mouseover_service_charge') . '</div>
                        </div>
                    </div>
                </th>
                <td>&nbsp;' . $result['service-charge'] . '</td>
                <td class="ratepay-TextAlignLeft">&euro;</td>
            </tr>
            <tr class="piPriceSectionHead">
                <th class="ratepay-PercentWidth">
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('images/payone/info-icon.png') . '"/></div>
                        <div class="ratepay-FloatLeft">' . $this->__('lang_effective_rate') . ':</div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoEffectiveRate">' . $this->__('lang_mouseover_effective_rate') . ':</div>
                        </div>
                    </div>
                </th>
                <td colspan="2"><div class="ratepay-FloatLeft">&nbsp;<div class="ratepay-PercentWith">' . $result['annual-percentage-rate'] . '%</div></div></td>
            </tr>
            <tr class="piTableHr">
                <th>
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('images/payone/info-icon.png') . '"/></div>
                        <div class="ratepay-FloatLeft">' . $this->__('lang_interestrate_default') . ':</div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoDebitRate">' . $this->__('lang_mouseover_debit_rate') . ':</div>
                        </div>
                    </div>
                 </th>
                <td colspan="2"><div class="ratepay-FloatLeft">&nbsp;<div class="ratepay-PercentWith">' . $result['interest-rate'] . '%</div></div></td>
            </tr>
            <tr>
                <th>
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('images/payone/info-icon.png') . '"/></div>
                        <div class="ratepay-FloatLeft">' . $this->__('lang_interest_amount') . ':</div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoInterestAmount">' . $this->__('lang_mouseover_interest_amount') . ':</div>
                        </div>
                    </div>
                </th>
                <td>&nbsp;' . $result['interest-amount'] . '</td>
                <td class="ratepay-TextAlignLeft">&euro;</td>
            </tr>
            <tr>
                <th>
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('images/payone/info-icon.png') . '"/></div>
                        <div class="ratepay-FloatLeft"><b>' . $this->__('lang_total_amount') . ':</b></div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoTotalAmount">' . $this->__('lang_mouseover_total_amount') . '</div>
                        </div>
                    </div>
                </th>
                <td><b>&nbsp;' . $result['total-amount'] . '</b></td>
                <td class="ratepay-TextAlignLeft"><b>&euro;</b></td>
            </tr>
            <tr>
                <td colspan="2"><div class="ratepay-FloatLeft">&nbsp;<div></td>
            </tr>
            <tr>
                <td colspan="2"><div class="ratepay-FloatLeft">' . $this->__('lang_calulation_result_text') . '<div></td>
            </tr>
             <tr class="ratepay-result piPriceSectionHead">
                <th class="ratepay-PaddingTop">
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('images/payone/info-icon.png') . '"/></div>
                        <div class="ratepay-FloatLeft"><b>' . $this->__('lang_duration_time') . ':</b></div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoDurationTime">' . $this->__('lang_mouseover_duration_time') . '</div>
                        </div>
                    </div>
                </th>
                <td><b>&nbsp;' . $result['number-of-rates'] .$this->__('lang_months') . '</b></td>
                <td>&nbsp;</td>
            </tr>
            <tr class="ratepay-result">
                <th>
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('images/payone/info-icon.png') . '"/></div>
                        <div class="ratepay-FloatLeft piRpPaddingLeft"><b>' . $numberOfRates  . '' . $this->__('lang_duration_month') . ':</b></div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoDurationMonth">' . $this->__('lang_mouseover_duration_month') . '</div>
                        </div>
                    </div>
                </th>
                <td><b>&nbsp;' . $result['rate'] . '</b></td>
                <td class="ratepay-PaddingRight"><b>&euro;</b></td>
            </tr>
            <tr class="ratepay-result piRpPaddingBottom">
                <th class="ratepay-PaddingBottom">
                    <div class="ratepay-InfoDiv">
                        <div class="ratepay-InfoImgDiv"><img class="ratepay-InfoImg" src="' . Mage::getDesign()->getSkinUrl('images/payone/info-icon.png') . '"/></div>
                        <div class="ratepay-FloatLeft piRpPaddingLeft"><b>' . $this->__('lang_last_rate') . ':</b></div>
                        <div class="ratepay-RelativePosition">
                            <div class="ratepay-MouseoverInfo" id="ratepayMouseoverInfoLastRate">' . $this->__('lang_mouseover_last_rate') . '</div>
                        </div>
                    </div>
                </th>
                <td class="ratepay-PaddingBottom"><b>&nbsp;' . $result['last-rate'] . '</b></td>
                <td class="ratepay-PaddingRight piRpPaddingBottom"><b>&euro;</b></td>
            </tr>
            <tr>
                <td colspan="2"><div class="ratepay-CalculationText ">' . $this->__('lang_calulation_example') . '</div></td>
            </tr>
        </table>';

        return $html;
    }
}