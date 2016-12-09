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
class Payone_Api_Request_Parameter_Invoicing_Item
    extends Payone_Api_Request_Parameter_Abstract
{
    /**
     * @var string
     */
    protected $id = NULL;
    /**
     * @var int
     */
    protected $pr = NULL;
    /**
     * @var int
     */
    protected $no = NULL;
    /**
     * @var string
     */
    protected $de = NULL;

    /**
     * Artikeltyp (Enum)
     * @var string */
    protected $it = NULL;

    /**
     * @var int
     */
    protected $va = NULL;
    /**
     * DeliveryDate (YYYYMMDD)
     *
     * @var string
     */
    protected $sd = NULL;
    /**
     * Lieferzeitraums-Ende (YYYYMMDD)
     *
     * @var string
     */
    protected $ed = NULL;

    /**
     * @param int $key
     * @return array
     */
    public function toArrayByKey($key)
    {
        $data = array();
        $data['id[' . $key . ']'] = $this->getId();
        $data['pr[' . $key . ']'] = $this->getPr();
        $data['no[' . $key . ']'] = $this->getNo();
        $data['de[' . $key . ']'] = $this->getDe();
        $data['it[' . $key . ']'] = $this->getIt();
        $data['va[' . $key . ']'] = $this->getVa();
        $data['sd[' . $key . ']'] = $this->getSd();
        $data['ed[' . $key . ']'] = $this->getEd();
        return $data;
    }

    /**
     * @param string $de
     */
    public function setDe($de)
    {
        $this->de = $de;
    }

    /**
     * @return string
     */
    public function getDe()
    {
        return $this->de;
    }

    /**
     * @param string $ed
     */
    public function setEd($ed)
    {
        $this->ed = $ed;
    }

    /**
     * @return string
     */
    public function getEd()
    {
        return $this->ed;
    }

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
     * @param int $no
     */
    public function setNo($no)
    {
        $this->no = $no;
    }

    /**
     * @return int
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * @param int $pr
     */
    public function setPr($pr)
    {
        $this->pr = $pr;
    }

    /**
     * @return int
     */
    public function getPr()
    {
        return $this->pr;
    }

    /**
     * @param string $sd
     */
    public function setSd($sd)
    {
        $this->sd = $sd;
    }

    /**
     * @return string
     */
    public function getSd()
    {
        return $this->sd;
    }

    /**
     * @param int $va
     */
    public function setVa($va)
    {
        $this->va = $va;
    }

    /**
     * @return int
     */
    public function getVa()
    {
        return $this->va;
    }

    /**
     * @param string $it
     */
    public function setIt($it)
    {
        $this->it = $it;
    }

    /**
     * @return string
     */
    public function getIt()
    {
        return $this->it;
    }
}
