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
class Payone_Core_Model_Service_Payment_Preauthorize
    extends Payone_Core_Model_Service_Payment_Abstract
{
    const EVENT_TYPE = 'preauthorization';

    /** @var Payone_Api_Service_Payment_PreauthorizeInterface */
    protected $serviceApiPayment = null;

    /**
     * @param Payone_Api_Request_Interface $request
     * @return Payone_Api_Response_Error|Payone_Api_Response_Preauthorization_Approved|Payone_Api_Response_Preauthorization_Redirect
     */
    protected function perform(Payone_Api_Request_Interface $request)
    {
        return $this->getServiceApiPayment()->preauthorize($request);
    }

    /**
     * @param Payone_Api_Service_Payment_PreauthorizeInterface $serviceApiPayment
     */
    public function setServiceApiPayment(Payone_Api_Service_Payment_PreauthorizeInterface $serviceApiPayment)
    {
        $this->serviceApiPayment = $serviceApiPayment;
    }

    /**
     * @return Payone_Api_Service_Payment_PreauthorizeInterface
     */
    public function getServiceApiPayment()
    {
        return $this->serviceApiPayment;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return self::EVENT_TYPE;
    }
}