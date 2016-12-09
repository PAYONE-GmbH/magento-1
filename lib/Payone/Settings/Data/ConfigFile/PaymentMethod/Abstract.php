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
abstract class Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract
    extends Payone_Settings_Data_ConfigFile_Abstract
    implements Payone_Settings_Data_ConfigFile_Interface
{
    /** @var string */
    protected $id = '';

    /** @var string */
    protected $title = '';

    protected $mid = null;
    protected $aid =null;
    protected $portalid = null;
    protected $fee_config = array();
    protected $min_order_total = null;
    protected $max_order_total =null;
    /** @var string */
    protected $types = null;
    /** @var string */
    protected $active = '';
    /** @var string */
    protected $countries = '';

    /** @var string */
    protected $authorization = '';

    /** @var string */
    protected $mode = '';

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    public function getAid()
    {
        return $this->aid;
    }

    public function setFeeConfig($fee_config)
    {
        $this->fee_config = $fee_config;
    }

    public function getFeeConfig()
    {
        return $this->fee_config;
    }

    public function addFeeConfig($value)
    {
        return $this->fee_config[]=$value;
    }

    public function setMaxOrderTotal($max_order_total)
    {
        $this->max_order_total = $max_order_total;
    }

    public function getMaxOrderTotal()
    {
        return $this->max_order_total;
    }

    public function setMid($mid)
    {
        $this->mid = $mid;
    }

    public function getMid()
    {
        return $this->mid;
    }

    public function setMinOrderTotal($min_order_total)
    {
        $this->min_order_total = $min_order_total;
    }

    public function getMinOrderTotal()
    {
        return $this->min_order_total;
    }

    public function setPortalid($portalid)
    {
        $this->portalid = $portalid;
    }

    public function getPortalid()
    {
        return $this->portalid;
    }

    public function setTypes($types)
    {
        $this->types = $types;
    }

    public function getTypes()
    {
        return $this->types;
    }

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
     * @param string $authorization
     */
    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @return string
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * @param string $countries
     */
    public function setCountries($countries)
    {
        $this->countries = $countries;
    }

    /**
     * @return string
     */
    public function getCountries()
    {
        return $this->countries;
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
}
