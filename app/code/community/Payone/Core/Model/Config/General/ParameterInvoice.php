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
 * Do not edit or add to this file if you wish to upgrade Payone_Core to newer
 * versions in the future. If you wish to customize Payone_Core for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Config_General_ParameterInvoice extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var int
     */
    protected $pdf_download_enabled = 0;
    /**
     * @var int
     */
    protected $transmit_enabled = 0;
    /**
     * @var string
     */
    protected $invoice_appendix = '';

    /** @var string */
    protected $invoice_appendix_refund = '';

    /**
     * @param int $pdf_download_enabled
     */
    public function setPdfDownloadEnabled($pdf_download_enabled)
    {
        $this->pdf_download_enabled = $pdf_download_enabled;
    }

    /**
     * @return int
     */
    public function getPdfDownloadEnabled()
    {
        return $this->pdf_download_enabled;
    }

    /**
     * @param string $invoice_appendix
     */
    public function setInvoiceAppendix($invoice_appendix)
    {
        $this->invoice_appendix = $invoice_appendix;
    }

    /**
     * @return string
     */
    public function getInvoiceAppendix()
    {
        return $this->invoice_appendix;
    }

    /**
     * @param int $transmit_enabled
     */
    public function setTransmitEnabled($transmit_enabled)
    {
        $this->transmit_enabled = $transmit_enabled;
    }

    /**
     * @return int
     */
    public function getTransmitEnabled()
    {
        return $this->transmit_enabled;
    }

    /**
     * @param string $invoice_appendix_refund
     */
    public function setInvoiceAppendixRefund($invoice_appendix_refund)
    {
        $this->invoice_appendix_refund = $invoice_appendix_refund;
    }

    /**
     * @return string
     */
    public function getInvoiceAppendixRefund()
    {
        return $this->invoice_appendix_refund;
    }
}
