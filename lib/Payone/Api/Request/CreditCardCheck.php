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
class Payone_Api_Request_CreditCardCheck extends Payone_Api_Request_Abstract
{
    protected $request = Payone_Api_Enum_RequestType::CREDITCARDCHECK;

    /**
     * @var int
     */
    protected $aid = NULL;
    /**
     * @var string
     */
    protected $cardpan = NULL;
    /**
     * @var string
     */
    protected $cardtype = NULL;
    /**
     * @var int
     */
    protected $cardexpiredate = NULL;
    /**
     * @var int
     */
    protected $cardcvc2 = NULL;
    /**
     * @var int
     */
    protected $cardissuenumber = NULL;
    /**
     * @var string
     */
    protected $storecarddata = NULL;
    /**
     * @var string
     */
    protected $language = NULL;

    /**
     * @param int $aid
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return int
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @param int $cardcvc2
     */
    public function setCardcvc2($cardcvc2)
    {
        $this->cardcvc2 = $cardcvc2;
    }

    /**
     * @return int
     */
    public function getCardcvc2()
    {
        return $this->cardcvc2;
    }

    /**
     * @param int $cardexpiredate
     */
    public function setCardexpiredate($cardexpiredate)
    {
        $this->cardexpiredate = $cardexpiredate;
    }

    /**
     * @return int
     */
    public function getCardexpiredate()
    {
        return $this->cardexpiredate;
    }

    /**
     * @param int $cardissuenumber
     */
    public function setCardissuenumber($cardissuenumber)
    {
        $this->cardissuenumber = $cardissuenumber;
    }

    /**
     * @return int
     */
    public function getCardissuenumber()
    {
        return $this->cardissuenumber;
    }

    /**
     * @param string $cardpan
     */
    public function setCardpan($cardpan)
    {
        $this->cardpan = $cardpan;
    }

    /**
     * @return string
     */
    public function getCardpan()
    {
        return $this->cardpan;
    }

    /**
     * @param string $cardtype
     */
    public function setCardtype($cardtype)
    {
        $this->cardtype = $cardtype;
    }

    /**
     * @return string
     */
    public function getCardtype()
    {
        return $this->cardtype;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $storecarddata
     */
    public function setStorecarddata($storecarddata)
    {
        $this->storecarddata = $storecarddata;
    }

    /**
     * @return string
     */
    public function getStorecarddata()
    {
        return $this->storecarddata;
    }
}
