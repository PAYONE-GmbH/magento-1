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
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Handler
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_Core_Model_Handler_Interface
{
    /**
     * @todo should return a result Object
     *
     * @param Payone_Api_Response_Interface $response
     */
    public function handle(Payone_Api_Response_Interface $response);

    /**
     * @param Payone_Core_Model_Config_Interface $config
     */
    public function setConfigStore(Payone_Core_Model_Config_Interface $config);

    /**
     * @return Payone_Core_Model_Config_Interface
     */
    public function getConfigStore();

    /**
     * @param Payone_Api_Request_Interface $request
     */
    public function setRequest(Payone_Api_Request_Interface $request);

    /**
     * @return Payone_Api_Request_Interface
     */
    public function getRequest();
}
