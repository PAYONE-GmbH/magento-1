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
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Request_Authorization extends Payone_Api_Request_Authorization_Abstract
{
    protected $request = Payone_Api_Enum_RequestType::AUTHORIZATION;

    /**
     * @var Payone_Api_Request_Parameter_Authorization_Business
     */
    protected $business = null;

    /**
     * @param \Payone_Api_Request_Parameter_Authorization_Business $business
     */
    public function setBusiness(Payone_Api_Request_Parameter_Authorization_Business $business)
    {
        $this->business = $business;
    }

    /**
     * @return \Payone_Api_Request_Parameter_Authorization_Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

}
