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
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Core_Model_System_Config_Abstract
{
    protected $dataArray;
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * @abstract
     * @return array
     */
    abstract public function toArray();

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $data = array();
        foreach ($this->toArray() as $key => $value) {
            $data[] = array(
                'value' => $key,
                'label' => $this->helper()->__($value)
            );
        }
        return $data;
    }

    /**
     * @return array
     */
    public function toSelectArray()
    {
        $data = array();
        foreach ($this->toArray() as $key => $value) {
            $data[$key] = $this->helper()->__($value);
        }
        return $data;
    }

    /**
     * @return Payone_Core_Helper_Data
     */
    protected function helper()
    {
        return Mage::helper('payone_core');
    }

    /**
     * @param \Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }
        return $this->factory;
    }

}