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
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Mapper_ApiRequest_Verification_BankAccountCheck
    extends Payone_Core_Model_Mapper_ApiRequest_Verification_Abstract
{
    /**
     * @var Payone_Core_Model_Config_Payment_Method_Interface
     */
    protected $config = null;

    /**
     * @param string $bankaccount
     * @param string $bankcode
     * @param string $bankcountry
     * @param string $iban
     * @param string $bic
     *
     * @return Payone_Api_Request_BankAccountCheck
     */
    public function map($bankaccount, $bankcode, $bankcountry, $iban = '', $bic = '')
    {
        $request = $this->getFactory()->getRequestVerificationBankAccountCheck();
        $helper = $this->helper();
        $config = $this->getConfig();

        $request->setAid($config->getAid());
        $request->setMid($config->getMid());
        $request->setPortalid($config->getPortalid());
        $request->setKey($config->getKey());
        $request->setMode($config->getMode());

        $request->setIntegratorName('Magento');
        $request->setIntegratorVersion($helper->getMagentoVersion());
        $request->setSolutionName('fatchip');
        $request->setSolutionVersion($helper->getPayoneVersion());


        $request->setEncoding('UTF-8');
        $request->setLanguage($helper->getDefaultLanguage());

        $request->setChecktype($config->getBankAccountCheckType());

        $request->setBankaccount($bankaccount);
        $request->setBankcode($bankcode);
        $request->setBankcountry($bankcountry);
        if (!empty($iban)) {
            $request->setIban(strtoupper($iban));
            if (!empty($bic)) {
                $request->setBic(strtoupper($bic));  // ensure bic and iban are sent uppercase
            }
        }


        return $request;
    }

    /**
     * A config for payment method "debit_payment"
     * @param Payone_Core_Model_Config_Payment_Method_Interface $config
     */
    public function setConfig(Payone_Core_Model_Config_Payment_Method_Interface $config)
    {
        $this->config = $config;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfig()
    {
        return $this->config;
    }
}