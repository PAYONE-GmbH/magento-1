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
 * @package         Payone_SessionStatus
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_SessionStatus_Request extends Payone_SessionStatus_Request_Abstract
{
    /**
     * @var string Payment portal key as MD5 value
     */
    protected $key = NULL;

    /**
     * @var Payone_SessionStatus_Request_Item[]
     */
    //@todo cw: use correct name $sessionStatusItems
    protected $sessionStatusItems = array();

    public function toArray()
    {
        $data = parent::toArray();

        $i = 0;
        foreach ($this->getSessionStatusItems() as $key => $item) {
            /** @var $item Payone_SessionStatus_Request_Item */

            $data = array_merge($data, $item->toArrayByKey($i));

            $i++;
        }

        //unset mapped item
        unset($data['sessionStatusItems']);

        return $data;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param \Payone_SessionStatus_Request_Item[] $sessionstatus_items
     */
    public function setSessionStatusItems($sessionstatus_items)
    {
        $this->sessionStatusItems = $sessionstatus_items;
    }

    /**
     * @return \Payone_SessionStatus_Request_Item[]
     */
    public function getSessionStatusItems()
    {
        return $this->sessionStatusItems;
    }

    /**
     * @return bool
     */
    public function hasSessionstatusItems()
    {
        return count($this->sessionStatusItems) ? true : false;
    }

    /**
     * @param Payone_SessionStatus_Request_Item $item
     */
    public function addSessionstatusItem(Payone_SessionStatus_Request_Item $item)
    {
        $this->sessionStatusItems[] = $item;
    }
}
