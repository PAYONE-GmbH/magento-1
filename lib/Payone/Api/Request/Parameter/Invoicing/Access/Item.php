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
class Payone_Api_Request_Parameter_Invoicing_Access_Item
    extends Payone_Api_Request_Parameter_Abstract
{
    /**
     * @var string
     */
    protected $id_trail = NULL;
    /**
     * @var int
     */
    protected $no_trail = NULL;
    /**
     * @var int
     */
    protected $pr_trail = NULL;
    /**
     * @var string
     */
    protected $de_trail = NULL;
    /**
     * @var int
     */
    protected $va_trail = NULL;

    /**
     * @var string
     */
    protected $id_recurring = NULL;
    /**
     * @var int
     */
    protected $no_recurring = NULL;
    /**
     * @var int
     */
    protected $pr_recurring = NULL;
    /**
     * @var string
     */
    protected $de_recurring = NULL;
    /**
     * @var int
     */
    protected $va_recurring = NULL;

    /**
     * @param int $key
     * @return array
     */
    public function toArrayByKey($key)
    {
        $data = array();
        $data['id_trail[' . $key . ']'] = $this->getIdTrail();
        $data['no_trail[' . $key . ']'] = $this->getNoTrail();
        $data['pr_trail[' . $key . ']'] = $this->getPrTrail();
        $data['de_trail[' . $key . ']'] = $this->getDeTrail();
        $data['va_trail[' . $key . ']'] = $this->getVaTrail();
        $data['id_recurring[' . $key . ']'] = $this->getIdRecurring();
        $data['no_recurring[' . $key . ']'] = $this->getNoRecurring();
        $data['pr_recurring[' . $key . ']'] = $this->getPrRecurring();
        $data['de_recurring[' . $key . ']'] = $this->getDeRecurring();
        $data['va_recurring[' . $key . ']'] = $this->getVaRecurring();
        return $data;
    }

    /**
     * @param string $de_recurring
     */
    public function setDeRecurring($de_recurring)
    {
        $this->de_recurring = $de_recurring;
    }

    /**
     * @return string
     */
    public function getDeRecurring()
    {
        return $this->de_recurring;
    }

    /**
     * @param string $de_trail
     */
    public function setDeTrail($de_trail)
    {
        $this->de_trail = $de_trail;
    }

    /**
     * @return string
     */
    public function getDeTrail()
    {
        return $this->de_trail;
    }

    /**
     * @param string $id_recurring
     */
    public function setIdRecurring($id_recurring)
    {
        $this->id_recurring = $id_recurring;
    }

    /**
     * @return string
     */
    public function getIdRecurring()
    {
        return $this->id_recurring;
    }

    /**
     * @param string $id_trail
     */
    public function setIdTrail($id_trail)
    {
        $this->id_trail = $id_trail;
    }

    /**
     * @return string
     */
    public function getIdTrail()
    {
        return $this->id_trail;
    }

    /**
     * @param int $no_recurring
     */
    public function setNoRecurring($no_recurring)
    {
        $this->no_recurring = $no_recurring;
    }

    /**
     * @return int
     */
    public function getNoRecurring()
    {
        return $this->no_recurring;
    }

    /**
     * @param int $no_trail
     */
    public function setNoTrail($no_trail)
    {
        $this->no_trail = $no_trail;
    }

    /**
     * @return int
     */
    public function getNoTrail()
    {
        return $this->no_trail;
    }

    /**
     * @param int $pr_recurring
     */
    public function setPrRecurring($pr_recurring)
    {
        $this->pr_recurring = $pr_recurring;
    }

    /**
     * @return int
     */
    public function getPrRecurring()
    {
        return $this->pr_recurring;
    }

    /**
     * @param int $pr_trail
     */
    public function setPrTrail($pr_trail)
    {
        $this->pr_trail = $pr_trail;
    }

    /**
     * @return int
     */
    public function getPrTrail()
    {
        return $this->pr_trail;
    }

    /**
     * @param int $va_recurring
     */
    public function setVaRecurring($va_recurring)
    {
        $this->va_recurring = $va_recurring;
    }

    /**
     * @return int
     */
    public function getVaRecurring()
    {
        return $this->va_recurring;
    }

    /**
     * @param int $va_trail
     */
    public function setVaTrail($va_trail)
    {
        $this->va_trail = $va_trail;
    }

    /**
     * @return int
     */
    public function getVaTrail()
    {
        return $this->va_trail;
    }

}
