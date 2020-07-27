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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2020 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Core_Model_Payment_Method_KlarnaBase extends Payone_Core_Model_Payment_Method_Abstract
{
    protected $_canUseForMultishipping = true;

    protected $methodType = Payone_Core_Model_System_Config_PaymentMethodType::KLARNABASE;

    protected $_code = Payone_Core_Model_System_Config_PaymentMethodCode::KLARNABASE;

    protected $_formBlockType = 'payone_core/payment_method_form_klarnaBase';
    protected $_infoBlockType = 'payone_core/payment_method_info_klarnaBase';

    protected $_mustTransimitInvoicingData = true;

    public function getApiResponseErrorMessage($response)
    {
        if((bool)$this->getConfig()->getShowCustomermessage() === true) {
            return $response->getCustomermessage();
        }

        return parent::getApiResponseErrorMessage($response);
    }

    /**
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function validate()
    {
        // FIXME VBFC MAGE-438 : maybe a better way?
        if (isset($_POST['payment']['authorization_token'])) {
            $token = $this->getInfoInstance()->getAdditionalInformation('klarna_authorization_token');
            if ($token != $_POST['payment']['authorization_token']) {
                $this->getInfoInstance()->setAdditionalInformation('klarna_authorization_token', $_POST['payment']['authorization_token']);
            }
        }
        return parent::validate();
    }

    /**
     * MAGE-438 override parent method to get a generic Klarna title for all methods
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->helper('core')->__("Payone - Klarna");
    }
}
