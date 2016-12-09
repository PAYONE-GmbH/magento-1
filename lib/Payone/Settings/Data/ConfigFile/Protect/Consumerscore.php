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
class Payone_Settings_Data_ConfigFile_Protect_Consumerscore
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    protected $key = 'consumerscore';

    /** @var string */
    protected $active = '';

    /** @var string */
    protected $mode = '';

    /** @var string */
    protected $min_order_total = '';

    /** @var string */
    protected $max_order_total = '';

    /** @var string */
    protected $addresscheck = '';

    /** @var string */
    protected $red = '';

    /** @var string */
    protected $yellow = '';

    /** @var string */
    protected $duetime = '';

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
     * @param string $addresscheck
     */
    public function setAddresscheck($addresscheck)
    {
        $this->addresscheck = $addresscheck;
    }

    /**
     * @return string
     */
    public function getAddresscheck()
    {
        return $this->addresscheck;
    }

    /**
     * @param string $duetime
     */
    public function setDuetime($duetime)
    {
        $this->duetime = $duetime;
    }

    /**
     * @return string
     */
    public function getDuetime()
    {
        return $this->duetime;
    }

    /**
     * @param string $minAmount
     */
    public function setMinOrderTotal($minAmount)
    {
        $this->min_order_total = $minAmount;
    }

    /**
     * @return string
     */
    public function getMinOrderTotal()
    {
        return $this->min_order_total;
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
     * @param string $red
     */
    public function setRed($red)
    {
        $this->red = $red;
    }

    /**
     * @return string
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @param string $yellow
     */
    public function setYellow($yellow)
    {
        $this->yellow = $yellow;
    }

    /**
     * @return string
     */
    public function getYellow()
    {
        return $this->yellow;
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
}
