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
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Handler_Verification_Creditrating
    extends Payone_Core_Model_Handler_Verification_Abstract
    implements Payone_Core_Model_Handler_Verification_Interface
{
    /** @var Payone_Core_Model_Config_Protect_Creditrating */
    protected $config = null;

    protected $prefix = 'payone_protect';

    /**
     * Handle Creditrating by Payone API response:
     *
     * @param Payone_Api_Response_Interface $response
     * @return string|bool will return true if all methods are available
     * @throws Exception|Mage_Payment_Exception
     */
    public function handle(Payone_Api_Response_Interface $response)
    {
        $address = $this->getAddress();

        $creditRatingScore = array();
        if ($response instanceof Payone_Api_Response_Consumerscore_Valid) {
            /** @var $response Payone_Api_Response_Consumerscore_Valid */
            $creditRatingScore = $response->getScore();

            $address->setPayoneProtectScore($creditRatingScore);
            $address->setPayoneProtectDate(now());
            $address->setPayoneProtectHash($this->helper()->createAddressHash($address));

            $this->saveCustomerAddress($address);
        }
        elseif ($response instanceof Payone_Api_Response_Consumerscore_Invalid) {
            /** @var $response Payone_Api_Response_Consumerscore_Invalid*/
            $creditRatingScore = Payone_Api_Enum_ConsumerscoreScore::RED;

            $address->setPayoneProtectScore($creditRatingScore);
            $address->setPayoneProtectDate(now());
            $address->setPayoneProtectHash($this->helper()->createAddressHash($address));

            $this->saveCustomerAddress($address);
        }
        elseif ($response instanceof Payone_Api_Response_Error) {
            /** @var $response Payone_Api_Response_Error */

            $creditRatingScore = $this->handleError(null, $response);
        }

        //address shoult be saved to prevent to much creditratings
        $address->save();
        return $creditRatingScore;
    }

    /**
     * @param Exception|null $ex
     * @return array|bool
     *
     */
    public function handleException(Exception $ex = null)
    {
        return $this->handleError($ex);
    }

    /**
     * Endpoint for handling all kinds of errors and exceptions,
     * Notifies Magento admin of error (by email), allows displaying of ALL payment methods
     *
     * Takes Exception and/or Error response as parameter, to provide information for error email
     *
     * @param null|Exception $ex
     * @param null|Payone_Api_Response_Error $response
     * @throws Mage_Payment_Exception
     * @return bool
     */
    protected function handleError(Exception $ex = null, Payone_Api_Response_Error $response = null)
    {
        $config = $this->getConfig();
        if ($config->isIntegrationEventAfterPayment()) {
            if ($config->onErrorStopCheckout()) {
                // Mage_Payment_Exception is caught in checkout and message gets displayed to customer.
                throw new Mage_Payment_Exception($config->getStopCheckoutMessage());
            }

            return true;
        }

        $additionalInfo = array();
        if (!empty($response)) {
            $errorName = 'Creditrating check ERROR. Code: ' . $response->getErrorcode();
            $errorMessage = $response->getErrormessage();
            $stackTrace = '';
            $additionalInfo['customermessage'] = $response->getCustomermessage();
        }
        elseif (!empty($ex)) {
            $errorName = 'Creditrating check Exception. ' . get_class($ex);
            $errorMessage = $ex->getMessage();
            $stackTrace = $ex->getTraceAsString();
        }
        else {
            $errorName = 'Creditrating check unexpected error. ';
            $errorMessage = 'An unexpected error occured during creditrating check.';
            $stackTrace = '';
        }

        $helperEmail = $this->helperEmail();
        $helperEmail->setStoreId($this->getConfigStore()->getStoreId());
        $helperEmail->sendEmailError($errorName, $errorMessage, $stackTrace, $additionalInfo);
        return true;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_Creditrating $config
     */
    public function setConfig(Payone_Core_Model_Config_Protect_Creditrating $config)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_Core_Model_Config_Protect_Creditrating
     */
    public function getConfig()
    {
        return $this->config;
    }
}
