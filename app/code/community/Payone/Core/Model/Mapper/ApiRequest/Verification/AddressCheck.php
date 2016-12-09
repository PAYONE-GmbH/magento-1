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
class Payone_Core_Model_Mapper_ApiRequest_Verification_AddressCheck
    extends Payone_Core_Model_Mapper_ApiRequest_Verification_Abstract
{
    /** @var Payone_Core_Model_Config_Protect_AddressCheck */
    protected $config = null;


    /**
     * @param Mage_Customer_Model_Address_Abstract $address
     *
     * @return Payone_Api_Request_AddressCheck
     */
    public function mapFromAddress(Mage_Customer_Model_Address_Abstract $address)
    {
        $request = $this->getFactory()->getRequestVerificationAddressCheck();
        $helper = $this->helper();
        $configGlobal = $this->getConfigGlobal();
        $config = $this->getConfig();

        // @todo move addressCheckType detection to method
        // @todo add option to configure used Adresschecktype externaly
        if ($address->getAddressType() === 'billing') {
            $request->setAddresschecktype($config->getCheckBilling());

            // check if billing is used for shipping and shipping-address has to be checked
            if ($address->getUseForShipping() === true and $config->mustCheckShipping()) {
                $request->setAddresschecktype($config->getCheckShipping());
            }
        }
        elseif ($address->getAddressType() === 'shipping') {
            $request->setAddresschecktype($config->getCheckShipping());
        }
        else {
            throw new Exception('Invalid Address Check Type');
        }

        $request->setAid($configGlobal->getAid());
        $request->setMid($configGlobal->getMid());
        $request->setMode($config->getMode());
        $request->setPortalid($configGlobal->getPortalid());
        $request->setKey($configGlobal->getKey());


        $request->setCity($address->getCity());
        $request->setCompany($address->getCompany());
        $request->setCountry($address->getCountry());
        $request->setFirstname($address->getFirstname());
        $request->setLastname($address->getLastname());

        $request->setIntegratorName('Magento');
        $request->setIntegratorVersion($helper->getMagentoVersion());
        $request->setSolutionName('fatchip');
        $request->setSolutionVersion($helper->getPayoneVersion());


        $request->setEncoding('UTF-8');
        $request->setLanguage($helper->getDefaultLanguage());
        $request->setStreet($address->getStreetFull());
        $request->setTelephonenumber($address->getTelephone());

        $countryId = $address->getCountryId();

        if ($countryId == "US" || $countryId == "CA") {
            $request->setState($address->getRegionCode());
        }

        $request->setZip($address->getPostcode());


        return $request;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_AddressCheck $configProtect
     */
    public function setConfig(Payone_Core_Model_Config_Protect_AddressCheck $configProtect)
    {
        $this->config = $configProtect;
    }

    /**
     * @return Payone_Core_Model_Config_Protect_AddressCheck
     */
    public function getConfig()
    {
        return $this->config;
    }
}