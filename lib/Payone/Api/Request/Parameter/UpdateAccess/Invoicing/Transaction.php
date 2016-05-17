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
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Request_Parameter_UpdateAccess_Invoicing_Transaction
    extends Payone_Api_Request_Parameter_Abstract
{
    /**
     * @var Payone_Api_Request_Parameter_Invoicing_Access_Item[]
     */
    protected $items = array();

    public function toArray()
    {
        $data = parent::toArray();

        $i = 1;
        foreach ($this->items as $key => $item) {
            /**
             * @var $item Payone_Api_Request_Parameter_Invoicing_Access_Item
             */

            $data = array_merge($data, $item->toArrayByKey($i));

            $i++;
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasItems()
    {
        return count($this->items) ? true : false;
    }

    /**
     * @param Payone_Api_Request_Parameter_Invoicing_Access_Item $item
     */
    public function addItem(Payone_Api_Request_Parameter_Invoicing_Access_Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @param Payone_Api_Request_Parameter_Invoicing_Access_Item[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return Payone_Api_Request_Parameter_Invoicing_Access_Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
