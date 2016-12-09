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
 * @subpackage      Mapper
 * @author          Ronny Schröder
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 */
class Payone_Api_Mapper_Request_Payment_Genericpayment
    extends Payone_Api_Mapper_Request_Payment_Abstract
    implements Payone_Api_Mapper_Request_Interface
{
    /**
     * Convert amount for payone service request
     * @param Payone_Api_Request_Interface $request
     * @return type
     */
    public function map(Payone_Api_Request_Interface $request)
    {
        /** @var $request Payone_Api_Request_Authorization */
        if($request->getAmount()) {
            $this->mapAmount($request);
        }

        return $request->toArray();
    }

}
