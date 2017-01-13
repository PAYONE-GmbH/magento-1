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
 * @copyright       Copyright (c) 2016 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Core_Model_Config_General_CcHostedTranslations extends Payone_Core_Model_Config_AreaAbstract
{

    protected $cc_translation_months = '';
    protected $cc_translation_errors = '';
    protected $cc_translation_placeholders = '';
    
    protected function _formatTranslationArray($aTranslations, $sType = '')
    {
        $aReturn = array();
        foreach ($aTranslations as $aTranslation) {
            $sLanguage = array_shift($aTranslation['translation_language']);
            if(!isset($aReturn[$sLanguage])) {
                $aReturn[$sLanguage] = array();
            }

            $sVarName = 'Payone.ClientApi.Language.'.$sLanguage;
            if($sType) {
                $sVarName .= '.'.$sType;
            }

            $sVarName .= '.'.array_shift($aTranslation['translation_type']);
            
            $aReturn[$sLanguage][$sVarName] = $aTranslation['translation_message'];
        }

        return $aReturn;
    }
    
    public function setCcTranslationMonths($sValue)
    {
        $this->cc_translation_months = $sValue;
    }
    
    public function getCcTranslationMonths()
    {
        $aTranslations = unserialize($this->cc_translation_months);
        $aTranslations = $this->_formatTranslationArray($aTranslations, 'months');
        return $aTranslations;
    }
    
    public function setCcTranslationErrors($sValue)
    {
        $this->cc_translation_errors = $sValue;
    }
    
    public function getCcTranslationErrors()
    {
        $aTranslations = unserialize($this->cc_translation_errors);
        $aTranslations = $this->_formatTranslationArray($aTranslations);
        return $aTranslations;
    }
    
    public function setCcTranslationPlaceholders($sValue)
    {
        $this->cc_translation_placeholders = $sValue;
    }
    
    public function getCcTranslationPlaceholders()
    {
        $aTranslations = unserialize($this->cc_translation_placeholders);
        $aTranslations = $this->_formatTranslationArray($aTranslations, 'placeholders');
        return $aTranslations;
    }
    
    protected function _translationMerge($aArray1, $aArray2)
    {
        foreach ($aArray2 as $sLanguage => $aTranslations) {
            if(!isset($aArray1[$sLanguage])) {
                $aArray1[$sLanguage] = array();
            }

            $aArray1[$sLanguage] = array_merge($aArray1[$sLanguage], $aTranslations);
        }

        return $aArray1;
    }
    
    public function getAllCcTranslations() 
    {
        $aMonths = $this->getCcTranslationMonths();
        $aErrors = $this->getCcTranslationErrors();
        $aPlaceholders = $this->getCcTranslationPlaceholders();

        $aTranslations = $this->_translationMerge($aMonths, $aErrors);
        $aTranslations = $this->_translationMerge($aTranslations, $aPlaceholders);

        return $aTranslations;
    }
    
}
