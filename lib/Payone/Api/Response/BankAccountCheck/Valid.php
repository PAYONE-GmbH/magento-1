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
class Payone_Api_Response_BankAccountCheck_Valid extends Payone_Api_Response_Abstract
{
    /**
     * @var int
     */
    protected $bankcode = NULL;
    /**
     * @var string
     */
    protected $bankaccount = NULL;
    /**
     * @var int
     */
    protected $bankbranchcode = NULL;
    /**
     * @var string
     */
    protected $bankcheckdigit = NULL;
    /**
     * @var string
     */
    protected $iban = NULL;
    /**
     * @var string
     */
    protected $bic = NULL;

    /**
     * @param int $bankcode
     */
    public function setBankcode($bankcode)
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return int
     */
    public function getBankcode()
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankaccount
     */
    public function setBankaccount($bankaccount)
    {
        $this->bankaccount = $bankaccount;
    }

    /**
     * @return string
     */
    public function getBankaccount()
    {
        return $this->bankaccount;
    }

    /**
     * @param string $bankbranchcode
     */
    public function setBankbranchcode($bankbranchcode)
    {
        $this->bankbranchcode = $bankbranchcode;
    }

    /**
     * @return int
     */
    public function getBankbranchcode()
    {
        return $this->bankbranchcode;
    }

    /**
     * @param string $bankcheckdigit
     */
    public function setBankcheckdigit($bankcheckdigit)
    {
        $this->bankcheckdigit = $bankcheckdigit;
    }

    /**
     * @return string
     */
    public function getBankcheckdigit()
    {
        return $this->bankcheckdigit;
    }

    /**
     * @param string $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }
}
