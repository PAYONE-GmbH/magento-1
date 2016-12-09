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
class Payone_Api_Request_Parameter_Vauthorization_PaymentMethod_DebitPayment
    extends Payone_Api_Request_Parameter_Vauthorization_PaymentMethod_Abstract
{
    /**
     * @var string
     */
    protected $bankcountry = NULL;
    /**
     * @var string
     */
    protected $bankaccount = NULL;
    /**
     * @var string
     */
    protected $bankcode = NULL;
    /**
     * @var string
     */
    protected $bankaccountholder = NULL;
    /**
     * @var string
     */
    protected $iban = NULL;
    /**
     * @var string
     */
    protected $bic = NULL;
    /**
     * @var string
     */
    protected $mandate_identification = NULL;

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
     * @param string $bankaccountholder
     */
    public function setBankaccountholder($bankaccountholder)
    {
        $this->bankaccountholder = $bankaccountholder;
    }

    /**
     * @return string
     */
    public function getBankaccountholder()
    {
        return $this->bankaccountholder;
    }

    /**
     * @param string $bankcode
     */
    public function setBankcode($bankcode)
    {
        $this->bankcode = $bankcode;
    }

    /**
     * @return string
     */
    public function getBankcode()
    {
        return $this->bankcode;
    }

    /**
     * @param string $bankcountry
     */
    public function setBankcountry($bankcountry)
    {
        $this->bankcountry = $bankcountry;
    }

    /**
     * @return string
     */
    public function getBankcountry()
    {
        return $this->bankcountry;
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

    /**
     * @param string $mandateIdentification
     */
    public function setMandateIdentification($mandateIdentification)
    {
        $this->mandate_identification = $mandateIdentification;
    }

    /**
     * @return string
     */
    public function getMandateIdentification()
    {
        return $this->mandate_identification;
    }
}
