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
class Payone_Api_Response_Management_ManageMandate_Approved extends Payone_Api_Response_Abstract
{
    /**
     * @var string
     */
    protected $mandate_identification = NULL;
    /**
     * @var string
     */
    protected $mandate_status = NULL;
    /**
     * @var string
     */
    protected $mandate_text = NULL;
    /**
     * @var string
     */
    protected $creditor_identifier = NULL;
    /**
     * @var string
     */
    protected $iban = NULL;
    /**
     * @var string
     */
    protected $bic = NULL;

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

    /**
     * @param string $mandateStatus
     */
    public function setMandateStatus($mandateStatus)
    {
        $this->mandate_status = $mandateStatus;
    }

    /**
     * @return string
     */
    public function getMandateStatus()
    {
        return $this->mandate_status;
    }

    /**
     * @param string $mandateText
     */
    public function setMandateText($mandateText)
    {
        $this->mandate_text = $mandateText;
    }

    /**
     * @return string
     */
    public function getMandateText()
    {
        return $this->mandate_text;
    }

    /**
     * @param string $creditorIdentifier
     */
    public function setCreditorIdentifier($creditorIdentifier)
    {
        $this->creditor_identifier = $creditorIdentifier;
    }

    /**
     * @return string
     */
    public function getCreditorIdentifier()
    {
        return $this->creditor_identifier;
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
