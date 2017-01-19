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
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2015 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Robert M�ller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Core_Block_Payment_Method_Form_Payolution extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    
    protected $_sAcceptanceBaseUrl = 'https://payment.payolution.com/payolution-payment/infoport/dataprivacydeclaration?mId=';

    protected $hasTypes = true;
    
    protected $_sFallback = "<header>
  <strong>Zus�tzliche Hinweise f�r die Datenschutzerkl�rung f�r Kauf auf Rechnung, Ratenzahlung und Zahlung mittels SEPA-Basis-Lastschrift von **company** (im Folgenden: �wir�)</strong></br>
  <span><i>(Stand: 17.03.2016)</i></span>
</header>
<ol>
  <li><p>Bei Kauf auf Rechnung oder Ratenzahlung oder SEPA-Basis-Lastschrift wird von Ihnen w�hrend des Bestellprozesses eine datenschutzrechtliche Einwilligung eingeholt. Folgend finden Sie eine Wiederholung dieser Bestimmungen, die lediglich informativen Charakter haben.</p></li>
  <li><p>Bei Auswahl von Kauf auf Rechnung oder Ratenzahlung oder Bezahlung mittels SEPA-Basis-Lastschrift werden f�r die Abwicklung dieser Zahlarten personenbezogene Daten (Vorname, Nachname, Adresse, Email, Telefonnummer, Geburtsdatum, IP-Adresse, Geschlecht) gemeinsam mit f�r die Transaktionsabwicklung erforderlichen Daten (Artikel, Rechnungsbetrag, Zinsen, Raten, F�lligkeiten, Gesamtbetrag, Rechnungsnummer, Steuern, W�hrung, Bestelldatum und Bestellzeitpunkt) an payolution �bermittelt werden. payolution hat ein berechtigtes Interesse an den Daten und ben�tigt bzw. verwendet diese um Risiko�berpr�fungen durchzuf�hren.</p></li>
  <li>
  	<p>Zur �berpr�fung der Identit�t bzw. Bonit�t des Kunden werden Abfragen und Ausk�nfte bei �ffentlich zug�nglichen Datenbanken sowie Kreditauskunfteien durchgef�hrt. Bei nachstehenden Anbietern k�nnen Ausk�nfte und gegebenenfalls Bonit�tsinformationen auf Basis mathematisch-statistischer Verfahren eingeholt werden:</p>
  	<ul>
		<li>CRIF GmbH, Diefenbachgasse 35, A-1150 Wien</li>
		<li>CRIF AG, Hagenholzstrasse 81, CH-8050 Z�rich</li>
		<li>Deltavista GmbH, Dessauerstra�e 9, D-80992 M�nchen</li>
		<li>SCHUFA Holding AG, Kormoranweg 5, D-65201 Wiesbaden</li>
		<li>KSV1870 Information GmbH, Wagenseilgasse 7, A-1120 Wien</li>
		<li>B�rgel Wirtschaftsinformationen GmbH & Co. KG, Gasstra�e 18, D-22761 Hamburg</li>
		<li>Creditreform Boniversum GmbH, Hellersbergstr. 11, D-41460 Neuss</li>
		<li>infoscore Consumer Data GmbH, Rheinstra�e 99, D-76532 Baden-Baden</li>
		<li>ProfileAddress Direktmarketing GmbH, Altmannsdorfer Strasse 311, A-1230 Wien</li>
		<li>Deutsche Post Direkt GmbH, Junkersring 57, D-53844 Troisdorf</li>
		<li>payolution GmbH, Am Euro Platz 2, A-1120 Wien</li>
	</ul>
	<p>payolution wird Ihre Angaben zur Bankverbindung (insbesondere Bankleitzahl und Kontonummer) zum Zwecke der Kontonummernpr�fung an die SCHUFA Holding AG �bermitteln. Die SCHUFA pr�ft anhand dieser Daten zun�chst, ob die von Ihnen gemachten Angaben zur Bankverbindung plausibel sind. Die SCHUFA �berpr�ft, ob die zur Pr�fung verwendeten Daten ggf. in Ihrem Datenbestand gespeichert sind und �bermittelt sodann das Ergebnis der �berpr�fung an payolution zur�ck. Ein weiterer Datenaustausch wie die Bekanntgabe von Bonit�tsinformationen oder eine �bermittlung abweichender Bankverbindungsdaten sowie Speicherung Ihrer Daten im SCHUFA-Datenbestand finden im Rahmen der Kontonummernpr�fung nicht statt. Es wird aus Nachweisgr�nden allein die Tatsache der �berpr�fung der Bankverbindungsdaten bei der SCHUFA gespeichert.</p>
	<p>payolution ist berechtigt, auch Daten zu etwaigem nicht-vertragsgem��en Verhalten (z.B. unbestrittene offene Forderungen) zu speichern, zu verarbeiten, zu nutzen und an oben genannte Auskunfteien zu �bermitteln.</p>
  </li>
  <li><p>Wir sind bereits nach den Bestimmungen des B�rgerlichen Gesetzbuches �ber Finanzierungshilfen zwischen Unternehmern und Verbrauchern, zu einer Pr�fung Ihrer Kreditw�rdigkeit gesetzlich verpflichtet.</p></li>
  <li><p>Im Fall eines Kaufs auf Rechnung oder Ratenkauf oder einer Bezahlung mittels SEPA-Basis-Lastschrift werden der payolution GmbH Daten �ber die Aufnahme (zu Ihrer Person, Kaufpreis, Laufzeit des Teilzahlungsgesch�fts, Ratenbeginn) und vereinbarungsgem��e Abwicklung (z.B. vorzeitige R�ckzahlung, Laufzeitverl�ngerung, erfolgte R�ckzahlungen) dieses Teilzahlungsgesch�fts �bermittelt. Nach Abtretung der Kaufpreisforderung wird die forderungs�bernehmende Bank die genannten Daten�bermittlungen vornehmen. Wir bzw. die Bank, der die Kaufpreisforderung abgetreten wird, werden payolution GmbH auch Daten aufgrund nichtvertragsgem��er Abwicklung (z.B. K�ndigung des Teilzahlungsgesch�fts, Zwangsvollstreckungs-ma�nahmen) melden. Diese Meldungen d�rfen nach den datenschutzrechtlichen Bestimmungen nur erfolgen, soweit dies zur Wahrung berechtigter Interessen von Vertragspartnern der payolution GmbH oder der Allgemeinheit erforderlich ist und dadurch Ihre schutzw�rdigen Belange nicht beeintr�chtigt werden. payolution GmbH speichert die Daten, um ihren Vertragspartnern, die gewerbsm��ig Teilzahlungs- und sonstige Kreditgesch�fte an Verbraucher geben, Informationen zur Beurteilung der Kreditw�rdigkeit von Kunden geben zu k�nnen. An Unternehmen, die gewerbsm��ig Forderungen einziehen und payolution GmbH vertraglich angeschlossen sind, k�nnen zum Zwecke der Schuldnerermittlung Adressdaten �bermittelt werden. payolution GmbH stellt die Daten ihren Vertragspartnern nur zur Verf�gung, wenn diese ein berechtigtes Interesse an der Daten�bermittlung glaubhaft darlegen. payolution GmbH �bermittelt nur objektive Daten ohne Angabe der Bank; subjektive Werturteile sowie pers�nliche Einkommens- und Verm�gensverh�ltnisse sind in Ausk�nften der payolution GmbH nicht enthalten.</p></li>
  <li><p>Die im Bestellprozess durch Einwilligung erfolgte Zustimmung zur Datenweitergabe kann jederzeit, auch ohne Angabe von Gr�nden, uns gegen�ber widerrufen k�nnen. Die oben genannten gesetzlichen Verpflichtungen zur �berpr�fung Ihrer Kreditw�rdigkeit bleiben von einem allf�lligen Widerruf jedoch unber�hrt. Sie sind verpflichtet ausschlie�lich wahrheitsgetreue Angaben gegen�ber uns zu machen.</p></li>
  <li><p>Sollten Sie Auskunft �ber die Erhebung, Nutzung, Verarbeitung oder �bermittlung von Sie betreffenden personenbezogenen Daten erhalten wollen oder Ausk�nfte, Berichtigungen, Sperrungen oder L�schung dieser Daten w�nschen, k�nnen Sie sich an den Sachbearbeiter f�r Datenschutz bei payolution wenden:</p></li>
</ol>

<footer>Sachbearbeiter f�r Datenschutz<br />
	datenschutz@payolution.com<br />
	payolution GmbH<br />
	Am Euro Platz 2<br />
	1120 Wien<br />
	DVR: 4008655
</footer>";
    
    protected $_aBackendBlacklist = array(
        Payone_Api_Enum_PayolutionType::PYS
    );
    
    protected function _construct() 
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/form/payolution.phtml');
    }

    public function getPayolutionType() 
    {
        if($this->_sType === null) {
            $aTypes = $this->getMethod()->getConfig()->getTypes();
            $this->_sType = array_shift($aTypes);
        }

        return $this->_sType;
    }
    
    public function getPayolutionTypes() 
    {
        return $this->getMethod()->getConfig()->getTypes();
    }
    
    public function getPayolutionTypesBackend() 
    {
        $aTypes = $this->getPayolutionTypes();
        
        $aTypesReturn = array();
        foreach ($aTypes as $sType) {
            if(array_search($sType, $this->_aBackendBlacklist) === false) {
                $aTypesReturn[] = $sType;
            }
        }

        return $aTypesReturn;
    }
    
    /**
     * @return bool
     */
    public function isDobRequired()
    {
        // required for all countries
        // required only if customer didn't enter Dob in previous checkout step
        $customerDob = $this->getQuote()->getCustomerDob();
        if (empty($customerDob)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isTelephoneRequired()
    {
        // telephone is mandatory for any country in case of Klarna
        $telephone = $this->getQuote()->getBillingAddress()->getTelephone();
        if (empty($telephone)) {
            return true;
        }

        return false;
    }
    
    public function isB2BMode() 
    {
        if((bool)$this->getMethod()->getConfig()->getB2bMode() === true) {
            $sCompany = $this->getQuote()->getBillingAddress()->getCompany();
            if($sCompany) {
                return true;
            }
        }

        return false;
    }
    
    public function showBirthdayFields() 
    {
        if($this->isB2BMode() === false) {
            return true;
        }

        return false;
    }
    
    public function showDebitFields() 
    {
        if ($this->getPayolutionType() == Payone_Api_Enum_PayolutionType::PYD) {
            return true;
        }

        return false;
    }
    
    protected function _getFallbackText($sCompany) 
    {
        $sFallback = str_replace('**company**', $sCompany, $this->_sFallback);
        return $sFallback;
    }
    
    protected function _isUtf8EncodingNeeded($sString) 
    {
        if (preg_match('!!u', $sString)) {
            // this is utf-8
            return false;
        } else {
            // definitely not utf-8
            return true;
        }
    }
    
    public function getPayolutionAcceptanceText() 
    {
        $sCompany = $this->getMethod()->getConfig()->getCompanyName();
        $sUrl = $this->_sAcceptanceBaseUrl.base64_encode($sCompany);
        $sContent = file_get_contents($sUrl);
        $sPage = false;
        if(!empty($sContent) && stripos($sContent, 'payolution') !== false && stripos($sContent, '<header>') !== false) {
            //Parse content from HTML-body-tag from the given page
            $sRegex = "#<\s*?body\b[^>]*>(.*?)</body\b[^>]*>#s";
            preg_match($sRegex, $sContent, $aMatches);
            if(is_array($aMatches) && count($aMatches) > 1) {
                $sPage = $aMatches[1];
                //remove everything bevore the <header> tag ( a window.close link which wouldn't work in the given context )
                $sPage = substr($sPage, stripos($sPage, '<header>'));
            }
        }

        if(!$sPage) {
            $sPage = $this->_getFallbackText($sCompany);
        }

        if($this->_isUtf8EncodingNeeded($sPage)) {
            $sPage = utf8_encode($sPage);
        }

        return $sPage;
    }
    
    /**
     * @return array
     */
    protected function getSystemConfigMethodTypes()
    {
        return $this->getFactory()->getModelSystemConfigPayolutionType()->toSelectArray();
    }
    
    public function getHandleInstallmentUrl()
    {
        // are we in a secure environment?
        $isSecure = Mage::app()->getStore()->isCurrentlySecure();
        return $this->getUrl('payone_core/checkout_onepage/handlePayolutionInstallment', array('_secure' => $isSecure));
    }
    
}