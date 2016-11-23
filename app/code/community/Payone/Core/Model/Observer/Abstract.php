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
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Observer
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_Observer_Abstract
{
    /**
     *
     * @var Payone_Core_Model_Factory
     */
    protected $factory = null;
    /** @var Payone_Core_Helper_Data */
    protected $helper = null;
    /** @var Payone_Core_Helper_Email */
    protected $helperEmail = null;
    
    /** @var Payone_Core_Helper_Config */
    protected $helperConfig = null;
    /** @var Payone_Core_Helper_Score */
    protected $helperScore = null;

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
     * @return Payone_Core_Helper_Data
     */
    protected function helper()
    {
        if ($this->helper === null) {
            $this->helper = $this->getFactory()->helper();
        }

        return $this->helper;
    }

    /**
     * @return Payone_Core_Helper_Email
     */
    protected function helperEmail()
    {
        if ($this->helperEmail === null) {
            $this->helperEmail = $this->getFactory()->helperEmail();
        }

        return $this->helperEmail;
    }

    
    /**
     * @return Payone_Core_Helper_Config
     */
    protected function helperConfig()
    {
        if ($this->helperConfig === null) {
            $this->helperConfig = $this->getFactory()->helperConfig();
        }

        return $this->helperConfig;
    }

    /**
     * @return Payone_Core_Helper_Score
     */
    protected function helperScore()
    {
        if ($this->helperScore === null) {
            $this->helperScore = $this->getFactory()->helperScore();
        }

        return $this->helperScore;
    }
}