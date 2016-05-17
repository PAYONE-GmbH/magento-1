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
class Payone_Core_Helper_Score
    extends Payone_Core_Helper_Abstract
{
    /**
     * @param $score
     * @param $storeId
     * @return array|bool
     */
    public function evaluate($score, $storeId){

        /** @var Payone_Core_Model_Config_Protect_Creditrating $config */
        $config = $this->getFactory()->helperConfig()->getConfigProtect($storeId)->getCreditrating();//@todo cw should be moved as a seperate config
        $configuredMethods = array();
        $allowedMethods = array();

        if ($score === Payone_Api_Enum_AddressCheckScore::RED) {
            $configuredMethods = $config->getAllowPaymentMethodsRed();
        }
        elseif ($score === Payone_Api_Enum_AddressCheckScore::YELLOW) {
            $configuredMethods = $config->getAllowPaymentMethodsYellow();
        }
        elseif ($score === Payone_Api_Enum_AddressCheckScore::GREEN) {
            $configuredMethods = true;
        }

        if ($configuredMethods === true) {
            return true;
        }

        foreach ($configuredMethods as $value) {
            $allowedMethods[$value] = 1;
        }

        return $allowedMethods;
    }

    /**
     *
     * Searches for worst Score, returns 'G' as default
     *
     * @param array $scores
     * @return string
     */
    public function detectWorstScore(array $scores)
    {
        $worstScore = Payone_Api_Enum_AddressCheckScore::GREEN;
        if (in_array(Payone_Api_Enum_AddressCheckScore::RED, $scores,true)) {
            $worstScore = Payone_Api_Enum_AddressCheckScore::RED;
        }
        elseif (in_array(Payone_Api_Enum_AddressCheckScore::YELLOW, $scores, true)) {
            $worstScore = Payone_Api_Enum_AddressCheckScore::YELLOW;
        }

        return $worstScore;
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return array
     */
    public function detectWorstAddressScoreByQuote(Mage_Sales_Model_Quote $quote)
    {
        $quoteAddresses = $quote->getAllAddresses();

        $addressScores = array();
        foreach ($quoteAddresses as $address) {
            /** @var $address Mage_Sales_Model_Quote_Address */
            $addressScores[] = $address->getPayoneAddresscheckScore();
        }
        return $this->detectWorstScore($addressScores);
    }
}