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
class Payone_Api_Response_Consumerscore_Valid extends Payone_Api_Response_Abstract
{
    /**
     * @var int
     */
    protected $secstatus = NULL;
    /**
     * @var string
     */
    protected $score = NULL;
    /**
     * @var int
     */
    protected $scorevalue = NULL;
    /**
     * @var string
     */
    protected $secscore = NULL;
    /**
     * @var string
     */
    protected $divergence = NULL;
    /**
     * @var string
     */
    protected $personstatus = NULL;
    /**
     * @var string
     */
    protected $firstname = NULL;
    /**
     * @var string
     */
    protected $lastname = NULL;
    /**
     * @var string
     */
    protected $street = NULL;
    /**
     * @var string
     */
    protected $streetname = NULL;
    /**
     * @var string
     */
    protected $streetnumber = NULL;
    /**
     * @var string
     */
    protected $zip = NULL;
    /**
     * @var string
     */
    protected $city = NULL;

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $divergence
     */
    public function setDivergence($divergence)
    {
        $this->divergence = $divergence;
    }

    /**
     * @return string
     */
    public function getDivergence()
    {
        return $this->divergence;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $personstatus
     */
    public function setPersonstatus($personstatus)
    {
        $this->personstatus = $personstatus;
    }

    /**
     * @return string
     */
    public function getPersonstatus()
    {
        return $this->personstatus;
    }

    /**
     * @param string $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param int $scorevalue
     */
    public function setScorevalue($scorevalue)
    {
        $this->scorevalue = $scorevalue;
    }

    /**
     * @return int
     */
    public function getScorevalue()
    {
        return $this->scorevalue;
    }

    /**
     * @param string $secscore
     */
    public function setSecscore($secscore)
    {
        $this->secscore = $secscore;
    }

    /**
     * @return string
     */
    public function getSecscore()
    {
        return $this->secscore;
    }

    /**
     * @param int $secstatus
     */
    public function setSecstatus($secstatus)
    {
        $this->secstatus = $secstatus;
    }

    /**
     * @return int
     */
    public function getSecstatus()
    {
        return $this->secstatus;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $streetname
     */
    public function setStreetname($streetname)
    {
        $this->streetname = $streetname;
    }

    /**
     * @return string
     */
    public function getStreetname()
    {
        return $this->streetname;
    }

    /**
     * @param string $streetnumber
     */
    public function setStreetnumber($streetnumber)
    {
        $this->streetnumber = $streetnumber;
    }

    /**
     * @return string
     */
    public function getStreetnumber()
    {
        return $this->streetnumber;
    }

    /**
     * @param string $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }
}
