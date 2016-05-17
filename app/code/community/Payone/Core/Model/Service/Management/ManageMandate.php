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

class Payone_Core_Model_Service_Management_ManageMandate
    extends Payone_Core_Model_Service_Abstract
{
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';

    /** @var Payone_Api_Service_Management_ManageMandate */
    protected $serviceApiManageMandate = null;
    /** @var Payone_Core_Model_Mapper_ApiRequest_Management_ManageMandate */
    protected $mapper = null;

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @param $bankcountry
     * @param $bankaccount
     * @param $bankcode
     * @param $bic
     * @param $iban
     * @throws Mage_Core_Exception
     * @return Payone_Api_Response_Management_ManageMandate_Approved
     */
    public function execute(Mage_Sales_Model_Quote $quote, $bankcountry, $bankaccount, $bankcode, $bic, $iban)
    {
        $request = $this->getMapper()->mapByQuote($quote, $bankcountry, $bankaccount, $bankcode, $bic, $iban);

        $response = $this->getServiceApiManageMandate()->managemandate($request);

//        if (!$response instanceof Payone_Api_Response_Management_ManageMandate_Approved) {
//            throw new Mage_Payment_Exception($this->helper()->__('There has been an error processing your request.'));
//        }
        return $response;
    }

    /**
     * @param \Payone_Core_Model_Mapper_ApiRequest_Management_ManageMandate $mapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return \Payone_Core_Model_Mapper_ApiRequest_Management_ManageMandate
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param \Payone_Api_Service_Management_ManageMandate $serviceApiManageMandate
     */
    public function setServiceApiManageMandate($serviceApiManageMandate)
    {
        $this->serviceApiManageMandate = $serviceApiManageMandate;
    }

    /**
     * @return \Payone_Api_Service_Management_ManageMandate
     */
    public function getServiceApiManageMandate()
    {
        return $this->serviceApiManageMandate;
    }
}
 