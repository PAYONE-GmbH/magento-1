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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_ClientApi
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_ClientApi_Factory
{

    /**
     * @param string $key Service Key, e.g. 'payment/refund'
     * @return Payone_Api_Service_Payment_Authorize|Payone_Api_Service_Payment_Debit|Payone_Api_Service_Payment_Preauthorize|Payone_Api_Service_Payment_Refund
     * @throws Exception
     */
    public function buildService($key)
    {
        switch ($key) {
            case 'generateHash' :
                return $this->buildServiceGenerateHash();
                break;
            default :
                throw new Exception('Could not build service with key "' . $key . '"');
        }
    }

    /**
     * @return Payone_ClientApi_Service_GenerateHash
     */
    public function buildServiceGenerateHash()
    {
        $service = new Payone_ClientApi_Service_GenerateHash();
        return $service;
    }

}
