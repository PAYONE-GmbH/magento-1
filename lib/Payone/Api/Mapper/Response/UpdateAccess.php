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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Mapper_Response_UpdateAccess
    extends Payone_Api_Mapper_Response_Abstract
    implements Payone_Api_Mapper_Response_Interface
{

    /**
     * @param array $params
     *
     * @return Payone_Api_Response_Capture_Approved|Payone_Api_Response_Error
     * @throws Payone_Api_Exception_UnknownStatus
     */
    public function map(array $params)
    {
        $this->setParams($params);

        if ($this->isOk()) {
            $response = new Payone_Api_Response_Management_UpdateAccess_Ok($params);
        }
        elseif ($this->isError()) {
            $response = new Payone_Api_Response_Error($params);
        }
        else {
            throw new Payone_Api_Exception_UnknownStatus();
        }

        return $response;
    }

}
