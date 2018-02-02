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
class Payone_Core_Model_Config_General_PaymentCreditcard extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var int
     */
    protected $min_validity_period = 0;

    protected $sCCTemplate = '';
    protected $sCCRequestType = 'AJAX';
    protected $iCCTypeAutoRecognition = 0;

    /**
     * @param int $min_validity_period
     */
    public function setMinValidityPeriod($min_validity_period)
    {
        $this->min_validity_period = $min_validity_period;
    }

    /**
     * @return int
     */
    public function getMinValidityPeriod()
    {
        return $this->min_validity_period;
    }
    
    public function setCcTemplate($sTemplate) 
    {
        $this->sCCTemplate = $sTemplate;
    }
    
    public function getCcTemplate() 
    {
        return $this->sCCTemplate;
    }
    
    public function setCcRequestType($sRequestType) 
    {
        $this->sCCRequestType = $sRequestType;
    }
    
    public function getCcRequestType() 
    {
        return $this->sCCRequestType;
    }

    public function setCcTypeAutoRecognition($iCCTypeAutoRecognition)
    {
        $this->iCCTypeAutoRecognition = $iCCTypeAutoRecognition;
    }

    public function getCcTypeAutoRecognition()
    {
        return $this->iCCTypeAutoRecognition;
    }
    
}
