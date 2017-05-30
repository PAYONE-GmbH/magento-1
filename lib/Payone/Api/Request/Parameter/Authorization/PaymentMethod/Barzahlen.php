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
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Api_Request_Parameter_Authorization_PaymentMethod_Barzahlen
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{

    /**
     * @var string
     */
    protected $api_version = NULL;
    
    /**
     * @var string
     */
    protected $cashtype = NULL;

    public function setApiVersion()
    {
        $this->api_version = '3.10';
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->api_version;
    }
    
    public function setCashtype()
    {
        $this->cashtype = 'BZN';
    }

    /**
     * @return string
     */
    public function getCashtype()
    {
        return $this->cashtype;
    }
    
}
