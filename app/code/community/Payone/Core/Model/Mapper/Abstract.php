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
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Mapper_Abstract
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /** @var Payone_Core_Helper_Data */
    protected $helper = null;

    /** @var Payone_Core_Helper_Url */
    protected $helperUrl = null;

    /** @var bool */
    protected $isAdmin = false;

    /**
     *
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }

    /**
     *
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Payone_Core_Helper_Data $helper
     */
    public function setHelper(Payone_Core_Helper_Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return Payone_Core_Helper_Data
     */
    public function helper()
    {
        if ($this->helper === null) {
            $this->helper = $this->getFactory()->helper();
        }

        return $this->helper;
    }

    /**
     * @param Payone_Core_Helper_Url $helperUrl
     */
    public function setHelperUrl(Payone_Core_Helper_Url $helperUrl)
    {
        $this->helperUrl = $helperUrl;
    }

    /**
     * @return Payone_Core_Helper_Url
     */
    public function helperUrl()
    {
        if ($this->helperUrl === null) {
            $this->helperUrl = $this->getFactory()->helperUrl();
        }

        return $this->helperUrl;
    }


    /**
     * @param boolean $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

}