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
 * Do not edit or add to this file if you wish to upgrade Payone_Migrator to newer
 * versions in the future. If you wish to customize Payone_Migrator for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Migrator_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Migrator_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Migrator_Model_Mapper_Abstract
{
    /** @var Payone_Migrator_Helper_Data */
    protected $helper = null;
    /** @var Payone_Migrator_Model_Factory */
    protected $factory = null;


    /**
     * @param Payone_Migrator_Helper_Data $helper
     */
    public function setHelper(Payone_Migrator_Helper_Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @return Payone_Migrator_Helper_Data
     */
    public function helper()
    {
        if ($this->helper === null) {
            $this->helper = $this->getFactory()->helper();
        }
        return $this->helper;
    }

    /**
     * @param Payone_Migrator_Model_Factory $factory
     */
    public function setFactory(Payone_Migrator_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Migrator_Model_Factory
     */
    public function getFactory()
    {
        if($this->factory === null)
        {
            $this->factory = new Payone_Migrator_Model_Factory();
        }
        return $this->factory;
    }


}