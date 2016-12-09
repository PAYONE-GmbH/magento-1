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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Service_Export_Collection extends Payone_Core_Model_Service_Abstract
{
    protected $columns = array();

    public function __construct(array $columns = array())
    {
        if(count($columns) > 0){
            $this->setColumns($columns);
        }
    }
    /**
     * @param Mage_Core_Model_Resource_Db_Collection_Abstract $collection
     * @return string
     */
    public function exportCsv(Mage_Core_Model_Resource_Db_Collection_Abstract $collection)
    {
        $this->prepareCollection($collection);

        $csv = '';
        $data = array();
        // Header
        foreach ($this->getColumns() as $column) {
            $data[] = '"' . $this->helper()->__($column) . '"';
        }

        $csv .= implode(',', $data) . "\n";

        // Items
        foreach ($collection as $item) {
            /**
             * @var $item Varien_Object
             */
            $data = array();
            foreach ($this->getColumns() as $column) {
                $data[] = '"' . $item->getData($column) . '"';
            }

            $csv .= implode(',', $data) . "\n";
        }

        return $csv;
    }

    protected function prepareCollection(Mage_Core_Model_Resource_Db_Collection_Abstract $collection)
    {
        $collection->getSelect()->limit();
        $collection->setPageSize(0);
        $collection->load();
    }

    /**
     * @param array $columns
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

}
