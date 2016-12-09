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
class Payone_Api_Response_3dsCheck_Enrolled extends Payone_Api_Response_Abstract
{
    /**
     * @var string
     */
    protected $acsurl = NULL;
    /**
     * @var string
     */
    protected $termurl = NULL;
    /**
     * @var string
     */
    protected $pareq = NULL;
    /**
     * @var string
     */
    protected $xid = NULL;
    /**
     * @var string
     */
    protected $md = NULL;
    /**
     * @var string
     */
    protected $pseudocardpan = NULL;
    /**
     * @var string
     */
    protected $truncatedcardpan = NULL;

    /**
     * @param string $acsurl
     */
    public function setAcsurl($acsurl)
    {
        $this->acsurl = $acsurl;
    }

    /**
     * @return string
     */
    public function getAcsurl()
    {
        return $this->acsurl;
    }

    /**
     * @param string $md
     */
    public function setMd($md)
    {
        $this->md = $md;
    }

    /**
     * @return string
     */
    public function getMd()
    {
        return $this->md;
    }

    /**
     * @param string $pareq
     */
    public function setPareq($pareq)
    {
        $this->pareq = $pareq;
    }

    /**
     * @return string
     */
    public function getPareq()
    {
        return $this->pareq;
    }

    /**
     * @param string $pseudocardpan
     */
    public function setPseudocardpan($pseudocardpan)
    {
        $this->pseudocardpan = $pseudocardpan;
    }

    /**
     * @return string
     */
    public function getPseudocardpan()
    {
        return $this->pseudocardpan;
    }

    /**
     * @param string $termurl
     */
    public function setTermurl($termurl)
    {
        $this->termurl = $termurl;
    }

    /**
     * @return string
     */
    public function getTermurl()
    {
        return $this->termurl;
    }

    /**
     * @param string $truncatedcardpan
     */
    public function setTruncatedcardpan($truncatedcardpan)
    {
        $this->truncatedcardpan = $truncatedcardpan;
    }

    /**
     * @return string
     */
    public function getTruncatedcardpan()
    {
        return $this->truncatedcardpan;
    }

    /**
     * @param string $xid
     */
    public function setXid($xid)
    {
        $this->xid = $xid;
    }

    /**
     * @return string
     */
    public function getXid()
    {
        return $this->xid;
    }
}
