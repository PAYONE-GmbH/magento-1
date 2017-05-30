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
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */
class Payone_Settings_Data_ConfigFile_PaymentMethod_Ratepay
    extends Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    /** @var string */
    protected $key = Payone_Enum_ClearingType::RATEPAY;
    protected $ratepay_config = array();

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * @param array $ratepay_config
     */
    public function setRatepayConfig($ratepay_config)
    {
        $this->ratepay_config = $ratepay_config;
    }

    /**
     * @return array
     */
    public function getRatepayConfig()
    {
        return $this->ratepay_config;
    }

    /**
     * @param $value
     */
    public function addRatepayConfig($value)
    {
        $this->ratepay_config[] = $value;
    }
    
}
