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
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Core_Model_Mapper_ApiRequest_Management_GetFile
    extends Payone_Core_Model_Mapper_ApiRequest_Abstract
{
    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $config;

    /**
     * @param string $mandateIdentification
     * @return Payone_Api_Request_GetFile
     */
    public function map($mandateIdentification)
    {
        $paymentMethodConfig = $this->getConfig();
        $helper = $this->helper();

        $request = $this->getFactory()->getRequestManagementGetFile();

        // common parameters
        $request->setMid($paymentMethodConfig->getMid());
        $request->setPortalid($paymentMethodConfig->getPortalid());
        $request->setKey($paymentMethodConfig->getKey());
        $request->setMode($paymentMethodConfig->getMode());
        $request->setRequest(Payone_Api_Enum_RequestType::GETFILE);
        $request->setEncoding('UTF-8');
        $request->setIntegratorName('Magento');
        $request->setIntegratorVersion($helper->getMagentoVersion());
        $request->setSolutionName('fatchip');
        $request->setSolutionVersion($helper->getPayoneVersion());

        // special parameters
        $request->setFileReference($mandateIdentification);
        $request->setFileType(Payone_Api_Enum_FileType::SEPA_MANDATE);
        $request->setFileFormat(Payone_Api_Enum_FileFormat::PDF);

        return $request;
    }

    /**
     * @param \Payone_Core_Model_Config_Payment_Method_Interface $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return \Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getConfig()
    {
        return $this->config;
    }
}
 