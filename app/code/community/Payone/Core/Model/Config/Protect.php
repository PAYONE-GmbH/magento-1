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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Config_Protect extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var Payone_Core_Model_Config_Protect_AddressCheck
     */
    protected $addressCheck = null;
    /**
     * @var Payone_Core_Model_Config_Protect_Creditrating
     */
    protected $creditrating = null;

    /**
     * @param Payone_Core_Model_Config_Protect_AddressCheck $addressCheck
     */
    public function setAddressCheck(Payone_Core_Model_Config_Protect_AddressCheck $addressCheck)
    {
        $this->addressCheck = $addressCheck;
    }

    /**
     * @return Payone_Core_Model_Config_Protect_AddressCheck
     */
    public function getAddressCheck()
    {
        return $this->addressCheck;
    }

    /**
     * @param Payone_Core_Model_Config_Protect_Creditrating $creditrating
     */
    public function setCreditrating(Payone_Core_Model_Config_Protect_Creditrating $creditrating)
    {
        $this->creditrating = $creditrating;
    }

    /**
     * @return Payone_Core_Model_Config_Protect_Creditrating
     */
    public function getCreditrating()
    {
        return $this->creditrating;
    }
}
