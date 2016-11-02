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
class Payone_Core_Model_Handler_Verification_AddressCheck
    extends Payone_Core_Model_Handler_Verification_Abstract
    implements Payone_Core_Model_Handler_Verification_Interface
{
    /** @var Payone_Core_Model_Config_Protect_AddressCheck */
    protected $config = null;

    protected $prefix = 'payone_addresscheck';

    /**
     * @param Payone_Api_Response_Interface $response
     */
    public function handle(Payone_Api_Response_Interface $response)
    {
        $address = $this->getAddress();
        $errors = $this->getErrors();
        $config = $this->getConfig();

        $mapping = $config->getMappingPersonstatus();

        if ($response instanceof Payone_Api_Response_AddressCheck_Valid) {
            /** @var $response Payone_Api_Response_AddressCheck_Valid */
            if ($response->isCorrect()) {
                $this->handleCorrectAddress();
                // Do nothing, best case, resume with personStatus mapping.
            }
            elseif ($response->isCorrectable()) {
                $correctedAddress = $this->prepareAddressCorrectionDataForCustomer($response);
                if($correctedAddress['city'] == $address->getCity()
                   && $correctedAddress['postcode'] == $address->getPostcode()
                   && $correctedAddress['street'] == $address->getStreetFull())
                {
                    // PAYONE Api supports name correction, but it is not desired here, handle as a correct address.
                    $this->handleCorrectAddress();
                }
                elseif ($this->getConfig()->getConfirmAddressCorrection()) {
                    // Address correction must be confirmed by customer, hand it up to controller/frontend
                    $errors->setData('payone_address_corrected', $correctedAddress);
                }
                else {
                    // Automatically correct address, allowing customer to resume checkout
                    $this->correctAddress($response);
                }
            }

            $personStatus = $response->getPersonstatus();
            if($personStatus != 'NONE') {
                if (array_key_exists($personStatus, $mapping)) {
                    $score = $mapping[$personStatus];
                    $address->setData('payone_addresscheck_score', $score);
                }
            } else {
                $score = 'G';
                $address->setData('payone_addresscheck_score', $score);
            }

            $this->saveCustomerAddress($address);
        }
        elseif ($response instanceof Payone_Api_Response_AddressCheck_Invalid) {
            /** @var $response Payone_Api_Response_AddressCheck_Invalid */
            $message = $this->getMessageForInvalidData($response->getCustomermessage());
            $errors->setData('payone_address_invalid', $message);
        }
        elseif ($response instanceof Payone_Api_Response_Error) {
            /** @var $response Payone_Api_Response_Error */
            $this->handleError();
        }
    }

    /**
     * @param Exception|null $ex
     */
    public function handleException(Exception $ex = null)
    {
        $this->handleError();
    }

    /**
     * Endpoint for handling all kinds of errors and exceptions, handles output to customer
     * Does not take a message parameter, as we don´ want to expose this to the customer
     *
     * @return void
     */
    protected function handleError()
    {
        $config = $this->getConfig();
        if ($config->onErrorContinueCheckout()) {
            // Forced continuation of checkout, ignoring failed address check
            return;
        }

        $message = $config->getStopCheckoutMessage();
        if (empty($message)) {
            $message = $this->getGenericErrorMessage();
        }

        $message = $this->helper()->__($message); // Trying to translate the message

        $this->getErrors()->setData('payone_address_error', $message);
    }

    /**
     * @return void
     */
    public function handleCorrectAddress()
    {
        $address = $this->getAddress();
        $hash = $this->helper()->createAddressHash($address);

        $address->setData('payone_addresscheck_hash', $hash);
        $address->setData('payone_addresscheck_date', now());
    }

    /**
     * Auto-corrects address
     * Updated customer address (if available) as well
     *
     * @param Payone_Api_Response_AddressCheck_Valid $response
     */
    protected function correctAddress(Payone_Api_Response_AddressCheck_Valid $response)
    {
        $address = $this->getAddress();

        $newCity = $response->getCity();
        $newZip = $response->getZip();
        $newStreet = $response->getStreet();
        $sNewStreet2 = $response->getStreet2();
        if(!empty($sNewStreet2)) {
            $newStreet = array($newStreet, $sNewStreet2);
        }

        $address->setCity($newCity);
        $address->setPostcode($newZip);
        $address->setStreetFull($newStreet);

        $this->handleCorrectAddress();
    }


    /**
     * @param Payone_Api_Response_AddressCheck_Valid $response
     *
     * @return array|bool Corrected address data as array, false on no correction
     */
    protected function prepareAddressCorrectionDataForCustomer(Payone_Api_Response_AddressCheck_Valid $response)
    {
        $correctedAddress = array(
            'city' => $response->getCity(),
            'postcode' => $response->getZip(),
            'street' => $response->getStreet(),
            'street2' => $response->getStreet2(),
            'customermessage' => $this->helper()->__('Address corrected. Please confirm.'));

        return $correctedAddress;
    }

    /**
     * @return string
     */
    protected function getGenericErrorMessage()
    {
        return 'There has been an error processing your request.';
    }

    /**
     * @param $customermessage
     * @return string
     */
    protected function getMessageForInvalidData($customermessage)
    {

        $message = $this->getConfig()->getMessageResponseInvalid();

        if(empty($message))
        {
            $message = $this->helper()->__('Address data invalid.');
        }

        $substitutionArray = array(
            '{{payone_customermessage}}' => $customermessage,
        );

        $message = str_replace(array_keys($substitutionArray), array_values($substitutionArray), $message);

        return $message;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_AddressCheck $config
     */
    public function setConfig(Payone_Core_Model_Config_Protect_AddressCheck $config)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_Core_Model_Config_Protect_AddressCheck
     */
    public function getConfig()
    {
        return $this->config;
    }
}
