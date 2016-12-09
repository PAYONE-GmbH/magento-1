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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Service_Management_GetInvoice
    extends Payone_Core_Model_Service_Verification_Abstract
{
    protected $prefix = 'payone_getinvoice';

    /** @var Payone_Api_Service_Management_GetInvoice */
    protected $serviceApiGetInvoice = null;

    /** @var Payone_Core_Model_Mapper_ApiRequest_Management_GetInvoice */
    protected $mapper = null;

    /** @var Payone_Core_Model_Handler_Management_GetInvoice */
    protected $handler = null;

    /**
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @return string
     */
    public function execute(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $handler = $this->getHandler();

        // Map Api request:
        $request = $this->getMapper()->mapFromInvoice($invoice);

        // Send request via Api:
        $responseRaw = $this->getServiceApiGetInvoice()->getInvoice($request);

        if($responseRaw instanceof Payone_Api_Response_Error)
            return false;

        $response = $handler->handle($responseRaw);

        return $response;
    }

    /**
     * @param Payone_Core_Model_Handler_Management_GetInvoice $handler
     */
    public function setHandler(Payone_Core_Model_Handler_Management_GetInvoice $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Payone_Core_Model_Handler_Management_GetInvoice
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param Payone_Api_Service_Management_GetInvoice $serviceApiGetInvoice
     */
    public function setServiceApiGetInvoice($serviceApiGetInvoice)
    {
        $this->serviceApiGetInvoice = $serviceApiGetInvoice;
    }

    /**
     * @return Payone_Api_Service_Management_GetInvoice
     */
    public function getServiceApiGetInvoice()
    {
        return $this->serviceApiGetInvoice;
    }

    /**
     * @param Payone_Core_Model_Mapper_ApiRequest_Management_GetInvoice $mapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Payone_Core_Model_Mapper_ApiRequest_Management_GetInvoice
     */
    public function getMapper()
    {
        return $this->mapper;
    }

}