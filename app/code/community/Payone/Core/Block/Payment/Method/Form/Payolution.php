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
 * @author          Robert Müller <robert.mueller@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

class Payone_Core_Block_Payment_Method_Form_Payolution extends Payone_Core_Block_Payment_Method_Form_Abstract
{
    
    protected $_sAcceptanceBaseUrl = 'https://payment.payolution.com/payolution-payment/infoport/dataprivacydeclaration?mId=';

    protected $hasTypes = true;
    
    protected $_sFallback = "<header>
  <strong>Zusätzliche Hinweise für die Datenschutzerklärung für Kauf auf Rechnung, Ratenzahlung und Zahlung mittels SEPA-Basis-Lastschrift von **company** (im Folgenden: „wir“)</strong></br>
  <span><i>(Stand: 17.03.2016)</i></span>
</header>
<ol>
  <li><p>Bei Kauf auf Rechnung oder Ratenzahlung oder SEPA-Basis-Lastschrift wird von Ihnen während des Bestellprozesses eine datenschutzrechtliche Einwilligung eingeholt. Folgend finden Sie eine Wiederholung dieser Bestimmungen, die lediglich informativen Charakter haben.</p></li>
  <li><p>Bei Auswahl von Kauf auf Rechnung oder Ratenzahlung oder Bezahlung mittels SEPA-Basis-Lastschrift werden für die Abwicklung dieser Zahlarten personenbezogene Daten (Vorname, Nachname, Adresse, Email, Telefonnummer, Geburtsdatum, IP-Adresse, Geschlecht) gemeinsam mit für die Transaktionsabwicklung erforderlichen Daten (Artikel, Rechnungsbetrag, Zinsen, Raten, Fälligkeiten, Gesamtbetrag, Rechnungsnummer, Steuern, Währung, Bestelldatum und Bestellzeitpunkt) an payolution übermittelt werden. payolution hat ein berechtigtes Interesse an den Daten und benötigt bzw. verwendet diese um Risikoüberprüfungen durchzuführen.</p></li>
  <li>
  	<p>Zur Überprüfung der Identität bzw. Bonität des Kunden werden Abfragen und Auskünfte bei öffentlich zugänglichen Datenbanken sowie Kreditauskunfteien durchgeführt. Bei nachstehenden Anbietern können Auskünfte und gegebenenfalls Bonitätsinformationen auf Basis mathematisch-statistischer Verfahren eingeholt werden:</p>
  	<ul>
		<li>CRIF GmbH, Diefenbachgasse 35, A-1150 Wien</li>
		<li>CRIF AG, Hagenholzstrasse 81, CH-8050 Zürich</li>
		<li>Deltavista GmbH, Dessauerstraße 9, D-80992 München</li>
		<li>SCHUFA Holding AG, Kormoranweg 5, D-65201 Wiesbaden</li>
		<li>KSV1870 Information GmbH, Wagenseilgasse 7, A-1120 Wien</li>
		<li>Bürgel Wirtschaftsinformationen GmbH & Co. KG, Gasstraße 18, D-22761 Hamburg</li>
		<li>Creditreform Boniversum GmbH, Hellersbergstr. 11, D-41460 Neuss</li>
		<li>infoscore Consumer Data GmbH, Rheinstraße 99, D-76532 Baden-Baden</li>
		<li>ProfileAddress Direktmarketing GmbH, Altmannsdorfer Strasse 311, A-1230 Wien</li>
		<li>Deutsche Post Direkt GmbH, Junkersring 57, D-53844 Troisdorf</li>
		<li>payolution GmbH, Am Euro Platz 2, A-1120 Wien</li>
	</ul>
	<p>payolution wird Ihre Angaben zur Bankverbindung (insbesondere Bankleitzahl und Kontonummer) zum Zwecke der Kontonummernprüfung an die SCHUFA Holding AG übermitteln. Die SCHUFA prüft anhand dieser Daten zunächst, ob die von Ihnen gemachten Angaben zur Bankverbindung plausibel sind. Die SCHUFA überprüft, ob die zur Prüfung verwendeten Daten ggf. in Ihrem Datenbestand gespeichert sind und übermittelt sodann das Ergebnis der Überprüfung an payolution zurück. Ein weiterer Datenaustausch wie die Bekanntgabe von Bonitätsinformationen oder eine Übermittlung abweichender Bankverbindungsdaten sowie Speicherung Ihrer Daten im SCHUFA-Datenbestand finden im Rahmen der Kontonummernprüfung nicht statt. Es wird aus Nachweisgründen allein die Tatsache der Überprüfung der Bankverbindungsdaten bei der SCHUFA gespeichert.</p>
	<p>payolution ist berechtigt, auch Daten zu etwaigem nicht-vertragsgemäßen Verhalten (z.B. unbestrittene offene Forderungen) zu speichern, zu verarbeiten, zu nutzen und an oben genannte Auskunfteien zu übermitteln.</p>
  </li>
  <li><p>Wir sind bereits nach den Bestimmungen des Bürgerlichen Gesetzbuches über Finanzierungshilfen zwischen Unternehmern und Verbrauchern, zu einer Prüfung Ihrer Kreditwürdigkeit gesetzlich verpflichtet.</p></li>
  <li><p>Im Fall eines Kaufs auf Rechnung oder Ratenkauf oder einer Bezahlung mittels SEPA-Basis-Lastschrift werden der payolution GmbH Daten über die Aufnahme (zu Ihrer Person, Kaufpreis, Laufzeit des Teilzahlungsgeschäfts, Ratenbeginn) und vereinbarungsgemäße Abwicklung (z.B. vorzeitige Rückzahlung, Laufzeitverlängerung, erfolgte Rückzahlungen) dieses Teilzahlungsgeschäfts übermittelt. Nach Abtretung der Kaufpreisforderung wird die forderungsübernehmende Bank die genannten Datenübermittlungen vornehmen. Wir bzw. die Bank, der die Kaufpreisforderung abgetreten wird, werden payolution GmbH auch Daten aufgrund nichtvertragsgemäßer Abwicklung (z.B. Kündigung des Teilzahlungsgeschäfts, Zwangsvollstreckungs-maßnahmen) melden. Diese Meldungen dürfen nach den datenschutzrechtlichen Bestimmungen nur erfolgen, soweit dies zur Wahrung berechtigter Interessen von Vertragspartnern der payolution GmbH oder der Allgemeinheit erforderlich ist und dadurch Ihre schutzwürdigen Belange nicht beeinträchtigt werden. payolution GmbH speichert die Daten, um ihren Vertragspartnern, die gewerbsmäßig Teilzahlungs- und sonstige Kreditgeschäfte an Verbraucher geben, Informationen zur Beurteilung der Kreditwürdigkeit von Kunden geben zu können. An Unternehmen, die gewerbsmäßig Forderungen einziehen und payolution GmbH vertraglich angeschlossen sind, können zum Zwecke der Schuldnerermittlung Adressdaten übermittelt werden. payolution GmbH stellt die Daten ihren Vertragspartnern nur zur Verfügung, wenn diese ein berechtigtes Interesse an der Datenübermittlung glaubhaft darlegen. payolution GmbH übermittelt nur objektive Daten ohne Angabe der Bank; subjektive Werturteile sowie persönliche Einkommens- und Vermögensverhältnisse sind in Auskünften der payolution GmbH nicht enthalten.</p></li>
  <li><p>Die im Bestellprozess durch Einwilligung erfolgte Zustimmung zur Datenweitergabe kann jederzeit, auch ohne Angabe von Gründen, uns gegenüber widerrufen können. Die oben genannten gesetzlichen Verpflichtungen zur Überprüfung Ihrer Kreditwürdigkeit bleiben von einem allfälligen Widerruf jedoch unberührt. Sie sind verpflichtet ausschließlich wahrheitsgetreue Angaben gegenüber uns zu machen.</p></li>
  <li><p>Sollten Sie Auskunft über die Erhebung, Nutzung, Verarbeitung oder Übermittlung von Sie betreffenden personenbezogenen Daten erhalten wollen oder Auskünfte, Berichtigungen, Sperrungen oder Löschung dieser Daten wünschen, können Sie sich an den Sachbearbeiter für Datenschutz bei payolution wenden:</p></li>
</ol>

<footer>Sachbearbeiter für Datenschutz<br />
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
        return $this->getUrl('payone_core/checkout_onepage/handlePayolutionInstallment');
    }
    
}