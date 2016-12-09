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
 * @package         Payone_Api
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_Api_Service_Interface
{
    /**
     * @param \Payone_Api_Adapter_Interface $adapter
     */
    public function setAdapter(Payone_Api_Adapter_Interface $adapter);

    /**
     * @return \Payone_Api_Adapter_Interface
     */
    public function getAdapter();

    /**
     * @param Payone_Api_Service_ProtocolRequest_Interface $serviceProtocol
     */
    public function setServiceProtocol(Payone_Api_Service_ProtocolRequest_Interface $serviceProtocol);

    /**
     * @return Payone_Api_Service_ProtocolRequest_Interface
     */
    public function getServiceProtocol();
}
