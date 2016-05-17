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
 * @subpackage      Validator
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Validator
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Validator_DefaultParameters extends Payone_Api_Validator_Abstract
{
    /**
     * @param Payone_Api_Request_Interface $request
     * @return bool
     * @throws Payone_Api_Exception_Request_InvalidKey
     * @throws Payone_Api_Exception_Request_InvalidMid
     * @throws Payone_Api_Exception_Request_InvalidMode
     * @throws Payone_Api_Exception_Request_InvalidPortalid
     */
    public function validateRequest(Payone_Api_Request_Interface $request)
    {
        if ($request->getMid() == '') {
            throw new Payone_Api_Exception_Request_InvalidMid();
        }
        if ($request->getPortalid() == '') {
            throw new Payone_Api_Exception_Request_InvalidPortalid();
        }
        if ($request->getKey() == '') {
            throw new Payone_Api_Exception_Request_InvalidKey();
        }
        if ($request->getMode() == '') {
            throw new Payone_Api_Exception_Request_InvalidMode();
        }

        return true;
    }
}
