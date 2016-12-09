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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Data
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Settings_Data_ConfigFile_Protect_Addresscheck
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'addresscheck';

    /** @var string */
    protected $active = '';

    /** @var string */
    protected $mode = '';

    /** @var string */
    protected $min_order_total = '';

    /** @var string */
    protected $max_order_total = '';

    /** @var string */
    protected $checkbilling = '';

    /** @var string */
    protected $checkshipping = '';

    /** @var array */
    protected $personstatusmapping = array();

    /**
     * @param string $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param string $checkBilling
     */
    public function setCheckbilling($checkBilling)
    {
        $this->checkbilling = $checkBilling;
    }

    /**
     * @return string
     */
    public function getCheckbilling()
    {
        return $this->checkbilling;
    }

    /**
     * @param string $checkShipping
     */
    public function setCheckshipping($checkShipping)
    {
        $this->checkshipping = $checkShipping;
    }

    /**
     * @return string
     */
    public function getCheckshipping()
    {
        return $this->checkshipping;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param array $personStatusMapping
     */
    public function setPersonstatusmapping(array $personStatusMapping)
    {
        $this->personstatusmapping = $personStatusMapping;
    }

    /**
     * @return array
     */
    public function getPersonstatusmapping()
    {
        return $this->personstatusmapping;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $max_order_total
     */
    public function setMaxOrderTotal($max_order_total)
    {
        $this->max_order_total = $max_order_total;
    }

    /**
     * @return string
     */
    public function getMaxOrderTotal()
    {
        return $this->max_order_total;
    }

    /**
     * @param string $min_order_total
     */
    public function setMinOrderTotal($min_order_total)
    {
        $this->min_order_total = $min_order_total;
    }

    /**
     * @return string
     */
    public function getMinOrderTotal()
    {
        return $this->min_order_total;
    }
}
