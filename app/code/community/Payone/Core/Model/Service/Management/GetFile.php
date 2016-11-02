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
 * @subpackage      Service
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Core_Model_Service_Management_GetFile
    extends Payone_Core_Model_Service_Abstract
{
    /** @var Payone_Api_Service_Management_GetFile*/
    protected $serviceApiGetFile;
    /** @var Payone_Core_Model_Mapper_ApiRequest_Management_GetFile */
    protected $mapper;

    /**
     * @param string $mandateIdentification
     * @return bool|string
     */
    public function execute($mandateIdentification)
    {
        $request = $this->getMapper()->map($mandateIdentification);

        $response = $this->getServiceApiGetFile()->getFile($request);

        if (!$response instanceof Payone_Api_Response_Management_GetFile) {
            return false;
        }

        // return the content of the file:
        // Content for pdf-file is saved in rawresponse
        return $response->getRawResponse();
    }

    /**
     * @param \Payone_Core_Model_Mapper_ApiRequest_Management_GetFile $mapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return \Payone_Core_Model_Mapper_ApiRequest_Management_GetFile
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param \Payone_Api_Service_Management_GetFile $serviceApiGetFile
     */
    public function setServiceApiGetFile($serviceApiGetFile)
    {
        $this->serviceApiGetFile = $serviceApiGetFile;
    }

    /**
     * @return \Payone_Api_Service_Management_GetFile
     */
    public function getServiceApiGetFile()
    {
        return $this->serviceApiGetFile;
    }
}
 