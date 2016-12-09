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
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Response_Vauthorization_Approved extends Payone_Api_Response_Abstract
{
    /**
     * @var int
     */
    protected $vxid = NULL;
    /**
     * @var string
     */
    protected $vaid = NULL;
    /**
     * @var string
     */
    protected $userid = NULL;

    /**
     * @param string $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return string
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param string $vaid
     */
    public function setVaid($vaid)
    {
        $this->vaid = $vaid;
    }

    /**
     * @return string
     */
    public function getVaid()
    {
        return $this->vaid;
    }

    /**
     * @param int $vxid
     */
    public function setVxid($vxid)
    {
        $this->vxid = $vxid;
    }

    /**
     * @return int
     */
    public function getVxid()
    {
        return $this->vxid;
    }
}
