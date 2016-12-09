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
class Payone_Api_Request_3dsCheck extends Payone_Api_Request_Abstract
{
    protected $request = Payone_Api_Enum_RequestType::CHECK3DS;

    /**
     * @var int
     */
    protected $aid = NULL;
    /**
     * @var int
     */
    protected $amount = NULL;
    /**
     * @var string
     */
    protected $currency = NULL;
    /**
     * @var string
     */
    protected $clearingtype = NULL;
    /**
     * @var string
     */
    protected $exiturl = NULL;
    /**
     * @var string
     */
    protected $cardpan = NULL;
    /**
     * @var string
     */
    protected $cardtype = NULL;
    /**
     * @var string
     */
    protected $cardexpiredate = NULL;
    /**
     * @var int
     */
    protected $cardcvc2 = NULL;
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
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
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
     * @param string $cardexpiredate
     */
    public function setCardexpiredate($cardexpiredate)
    {
        $this->cardexpiredate = $cardexpiredate;
    }

    /**
     * @return string
     */
    public function getCardexpiredate()
    {
        return $this->cardexpiredate;
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
     * @param string $clearingtype
     */
    public function setClearingtype($clearingtype)
    {
        $this->clearingtype = $clearingtype;
    }

    /**
     * @return string
     */
    public function getClearingtype()
    {
        return $this->clearingtype;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $exiturl
     */
    public function setExiturl($exiturl)
    {
        $this->exiturl = $exiturl;
    }

    /**
     * @return string
     */
    public function getExiturl()
    {
        return $this->exiturl;
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
