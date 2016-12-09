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
 * @package         Payone_SessionStatus
 * @subpackage      Persistence
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 * This Interface defines the methods that have to be implemented to save an api communication
 *
 * @category        Payone
 * @package         Payone_SessionStatus
 * @subpackage      Persistence
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_SessionStatus_Persistence_Interface
{
    /**
     * @abstract
     * @return string
     */
    public function getKey();

    /**
     * @abstract
     * @param Payone_SessionStatus_Request_Interface $request
     * @param Payone_SessionStatus_Response_Interface $response
     * @return boolean
     */
    public function save(
        Payone_SessionStatus_Request_Interface $request,
        Payone_SessionStatus_Response_Interface $response
    );

}
