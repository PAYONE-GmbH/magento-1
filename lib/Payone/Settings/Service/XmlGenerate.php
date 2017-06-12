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
 * @package         Payone_Settings
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Settings
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Settings_Service_XmlGenerate
{
    const TAG_CONFIG_ROOT = 'config';
    const CLASS_PREFIX = 'Payone_Settings_Data_ConfigFile_';

    /** @var DOMDocument */
    private $dom;

    // @todo neue Methode generate mit gleichen Parametern
    // @todo wandelt anhand Shop "gruppiert" um und fügt mehrere Shops umwandlungen zusammen in ein config-root
    // @todo innerhalb von mapShop wird dann ein mapSystem, mapGlobal, mapClearingtypes und mapProtect gerufen
    // @todo innerhalb dieser methoden je nach bedarf weitere Methoden

    /**
     * Generates an XML string from a Root config object, including all settings (global, shop, payment, protect)
     *
     * @api
     *
     * @param Payone_Settings_Data_ConfigFile_Root $config
     * @return mixed @see SimpleXMLElement::asXml()
     */
    public function generate(Payone_Settings_Data_ConfigFile_Root $config)
    {
        // @todo wandelt anhand Shop "gruppiert" um und fügt mehrere Shops umwandlungen zusammen in ein config-root
        $this->dom = new DOMDocument('1.0', 'UTF-8');

        $root = $this->appendElement($this->dom, $config->getKey());

        foreach ($config->getShop() as $key => $value) {
            $shop = $this->mapShop($value, $root);
        }

        $this->dom->formatOutput = true;
        $xmlString = $this->dom->saveXML();

        return $xmlString;
    }

    /**
     * Generates an XML string from a Root config object, including all settings (global, shop, payment, protect)
     *
     * @api
     *
     * @param Payone_Settings_Data_ConfigFile_Root $config
     * @return mixed @see SimpleXMLElement::asXml()
     */
    public function execute(Payone_Settings_Data_ConfigFile_Root $config)
    {
        // Recursively add the arrays to a SimpleXMLElement, forming a tree:
        $arrayData = $config->toArray();
        $xml = $this->simpleXmlFromNestedArray(self::TAG_CONFIG_ROOT, $arrayData);

        return $xml->asXML();
    }

    /**
     * @param string $name                tag name
     * @param array $array                data
     * @param null|SimpleXMLElement $root IF not set, $name will form the root element
     * @return SimpleXMLElement
     */
    public function simpleXmlFromNestedArray($name, $array, SimpleXMLElement $root = null)
    {
        if ($root === null) {
            $root = new SimpleXMLElement('<' . $name . '>' . '</' . $name . '>');
        }

        /** @var $parent SimpleXMLElement */
        $parent = $root->addChild($name);
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (array_key_exists('attribute', $value)) {
                    //add node
                    $node = $parent->addChild($value['node']);
                    //add all attributes
                    foreach ($value['attribute'] as $attributKey => $attributData) {
                        $node->addAttribute($attributKey, $attributData);
                    }
                }
                else {
                    $this->simpleXmlFromNestedArray($key, $value, $parent);
                }
            }
            else {
                $parent->addChild($key, $value);
            }
        }

        return $parent;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop $shopConfig
     * @param DOMElement $configXml
     * @return string
     */
    protected function mapShop(Payone_Settings_Data_ConfigFile_Shop $shopConfig, DOMElement $configXml)
    {
        $shopXml = $this->appendElement($configXml, $shopConfig->getKey());

        $this->addChild($shopXml, $shopConfig, 'code');
        $this->addChild($shopXml, $shopConfig, 'name', true);

        $shopXml = $this->mapSystem($shopConfig->getSystem(), $shopXml);

        $shopXml = $this->mapGlobal($shopConfig->getGlobal(), $shopXml);
        $shopXml = $this->mapClearingtypes($shopConfig->getClearingtypes(), $shopXml);
        $shopXml = $this->mapProtect($shopConfig->getProtect(), $shopXml);
        $shopXml = $this->mapMisc($shopConfig->getMisc(), $shopXml);

        return $shopXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_System $systemConfig
     * @param DOMElement $shopXml
     * @return DOMElement
     */
    protected function mapSystem(Payone_Settings_Data_ConfigFile_Shop_System $systemConfig, DOMElement $shopXml)
    {
        $systemXml = $this->appendElement($shopXml, $systemConfig->getKey());

        $this->addChild($systemXml, $systemConfig, 'name');
        $this->addChild($systemXml, $systemConfig, 'version');
        $this->addChild($systemXml, $systemConfig, 'edition');
        $this->addChild($systemXml, $systemConfig, 'modules');
        return $shopXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig
     * @param DOMElement $shopXml
     * @return DOMElement
     */
    protected function mapGlobal(Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig, DOMElement $shopXml)
    {
        $globalXml = $this->appendElement($shopXml, $globalConfig->getKey());

        $this->addChild($globalXml, $globalConfig, 'mid');
        $this->addChild($globalXml, $globalConfig, 'aid');
        $this->addChild($globalXml, $globalConfig, 'portalid');
        $this->addChild($globalXml, $globalConfig, 'request_type');
        $this->mapParameterInvoice($globalConfig, $globalXml);
        $this->addStatusMapping($globalConfig, $globalXml);
        $this->mapPaymentCreditcard($globalConfig, $globalXml);
        return $shopXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_ClearingTypes $clearingTypes
     * @param DOMElement $shopXml
     * @return DOMElement
     */
    protected function mapClearingTypes(Payone_Settings_Data_ConfigFile_Shop_ClearingTypes $clearingTypes, DOMElement $shopXml)
    {
        $clearingTypesXml = $this->appendElement($shopXml, $clearingTypes->getKey());

        foreach ($clearingTypes->getClearingtypes() as $keyClearingType => $valueClearingType) {
            $clearingTypeNode = $this->appendElement($clearingTypesXml, $valueClearingType->getKey());

            $this->addChild($clearingTypeNode, $valueClearingType, 'title', true);
            $this->addChild($clearingTypeNode, $valueClearingType, 'id');
            $this->addChild($clearingTypeNode, $valueClearingType, 'mid');
            $this->addChild($clearingTypeNode, $valueClearingType, 'aid');
            $this->addChild($clearingTypeNode, $valueClearingType, 'portalid');
            $this->addFeeConfig($clearingTypeNode, $valueClearingType);
            $this->addChild($clearingTypeNode, $valueClearingType, 'min_order_total');
            $this->addChild($clearingTypeNode, $valueClearingType, 'max_order_total');

            $this->addTypesOrGlobalInfo($clearingTypeNode, $valueClearingType);
        }

        return $shopXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Protect $protectConfig
     * @param DOMElement $shopXml
     * @return DOMElement
     */
    protected function mapProtect(Payone_Settings_Data_ConfigFile_Shop_Protect $protectConfig, DOMElement $shopXml)
    {
        $protectXml = $this->appendElement($shopXml, $protectConfig->getKey());

        $protectXml = $this->mapConsumerscore($protectConfig->getConsumerscore(), $protectXml);
        $protectXml = $this->mapAddresscheck($protectConfig->getAddresscheck(), $protectXml);

        return $shopXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Misc $miscConfig
     * @param DOMElement $shopXml
     * @return DOMElement
     */
    protected function mapMisc(Payone_Settings_Data_ConfigFile_Shop_Misc $miscConfig, DOMElement $shopXml)
    {
        $miscXml = $this->appendElement($shopXml, $miscConfig->getKey());

        $this->addTransactionstatusForwarding($miscConfig, $miscXml);
        $this->addChild($miscXml, $miscConfig, 'shipping_costs');
        return $shopXml;

    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Protect_Consumerscore $consumerscoreConfig
     * @param DOMElement $protectXml
     * @return DOMElement
     */
    protected function mapConsumerscore(Payone_Settings_Data_ConfigFile_Protect_Consumerscore $consumerscoreConfig, DOMElement $protectXml)
    {
        $consumerscoreXml = $this->appendElement($protectXml, $consumerscoreConfig->getKey());

        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'active');
        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'mode');
        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'min_order_total');
        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'max_order_total');
        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'addresscheck');
        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'red');
        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'yellow');
        $this->addChild($consumerscoreXml, $consumerscoreConfig, 'duetime');

        return $protectXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Protect_Addresscheck $addresscheckConfig
     * @param DOMElement $protectXml
     * @return DOMElement
     */
    protected function mapAddresscheck(Payone_Settings_Data_ConfigFile_Protect_Addresscheck $addresscheckConfig, DOMElement $protectXml)
    {
        $addresscheckXml = $this->appendElement($protectXml, $addresscheckConfig->getKey());

        $this->addChild($addresscheckXml, $addresscheckConfig, 'active');
        $this->addChild($addresscheckXml, $addresscheckConfig, 'mode');
        $this->addChild($addresscheckXml, $addresscheckConfig, 'min_order_total');
        $this->addChild($addresscheckXml, $addresscheckConfig, 'max_order_total');
        $this->addChild($addresscheckXml, $addresscheckConfig, 'checkbilling');
        $this->addChild($addresscheckXml, $addresscheckConfig, 'checkshipping');
        $this->addChild($addresscheckXml, $addresscheckConfig, 'personstatusmapping');

        return $protectXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig
     * @param DOMElement $globalXml
     * @return DOMElement
     */
    protected function mapParameterInvoice(Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig, DOMElement $globalXml)
    {
        $parameterInvoice = $globalConfig->getParameterInvoice();
        $parameterInvoiceXml = $this->appendElement($globalXml, 'parameter_invoice');

        $this->appendElement($parameterInvoiceXml, 'invoice_appendix', $parameterInvoice['invoice_appendix'], true);
        $this->appendElement($parameterInvoiceXml, 'invoice_appendix_refund', $parameterInvoice['invoice_appendix_refund'], true);
        $this->appendElement($parameterInvoiceXml, 'pdf_download_enabled', $parameterInvoice['pdf_download_enabled']);
        $this->appendElement($parameterInvoiceXml, 'transmit_enabled', $parameterInvoice['transmit_enabled']);

        return $globalXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig
     * @param DOMElement $globalXml
     * @return DOMElement
     */
    protected function mapPaymentCreditcard(Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig, DOMElement $globalXml)
    {
        $paymentCreditcard = $globalConfig->getPaymentCreditcard();
        $paymentCreditcardXml = $this->appendElement($globalXml, 'payment_creditcard');

        $this->appendElement($paymentCreditcardXml, 'min_validity_period', $paymentCreditcard['min_validity_period']);

        return $globalXml;
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Misc $miscConfig
     * @param DOMElement $miscXml
     */
    public function addTransactionstatusForwarding(Payone_Settings_Data_ConfigFile_Shop_Misc $miscConfig, DOMElement $miscXml)
    {
        $tasForwarding = $miscConfig->getTransactionstatusforwarding();
        $tasXml = $this->appendElement($miscXml, $tasForwarding->getKey());

        foreach ($tasForwarding->getTransactionstatusForwarding() as $keyTas => $config) {
            $configNode = $this->appendElement($tasXml, 'config');

            foreach ($config as $key => $value) {
                $configNode->setAttribute($key, $value);
            }
        }
    }

    /**
     * @param Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig
     * @param DOMElement $globalXml
     */
    public function addStatusMapping(Payone_Settings_Data_ConfigFile_Shop_Global $globalConfig, DOMElement $globalXml)
    {
        $statusMapping = $globalConfig->getStatusMapping();
        $tasXml = $this->appendElement($globalXml, $statusMapping->getKey());

        foreach ($statusMapping->getStatusMapping() as $keyStatusMapping => $valueStatusMapping) {
            $parent = $this->appendElement($tasXml, $keyStatusMapping);

            foreach ($valueStatusMapping as $key => $value) {
                $mapNode = $this->appendElement($parent, 'map');

                $this->addAttribute($mapNode, $key, $value);
            }
        }
    }

    /**
     * @param DOMElement $cleatringTypeNode
     * @param Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract $valueClearingType
     */
    public function addTypesOrGlobalInfo(DOMElement $cleatringTypeNode, Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract $valueClearingType)
    {
        if ($valueClearingType->getTypes() !== NULL && $valueClearingType->getTypes() !== FALSE) {
            if ($valueClearingType instanceof Payone_Settings_Data_ConfigFile_PaymentMethod_Creditcard) {
                /** @var $valueClearingType Payone_Settings_Data_ConfigFile_PaymentMethod_Creditcard */
                $this->addChild($cleatringTypeNode, $valueClearingType, 'cvc2');
                $this->addChild($cleatringTypeNode, $valueClearingType, 'hideCvc');
            }

            $this->addChild($cleatringTypeNode, $valueClearingType, 'types');
        }

        $this->addGlobal($cleatringTypeNode, $valueClearingType);
    }

    public function addGlobal($parent, $type)
    {

        $this->addChild($parent, $type, 'active');
        // Currently not in use
        //$this->addChild($parent, $type, 'neworderstatus');
        $this->addChild($parent, $type, 'countries');
        $this->addChild($parent, $type, 'authorization');
        $this->addChild($parent, $type, 'mode');
    }

    /**
     * @param DOMElement $cleatringTypeNode
     * @param Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract $valueClearingType
     */
    public function addFeeConfig(DOMElement $cleatringTypeNode, Payone_Settings_Data_ConfigFile_PaymentMethod_Abstract $valueClearingType)
    {
        $feeConfig = $valueClearingType->getFeeConfig();
        if (!empty($feeConfig)) {
            $feeConfigNode = $this->appendElement($cleatringTypeNode, 'fee_config');

            foreach ($feeConfig as $keyFeeConfig => $valueFeeConfig) {
                if (array_key_exists('value', $valueFeeConfig) && array_key_exists('attribute', $valueFeeConfig)) {
                    $feeNode = $this->appendElement($feeConfigNode, 'fee', $valueFeeConfig['value']);
                    foreach ($valueFeeConfig['attribute'] as $keyFee => $valueFee) {
                        $feeNode->setAttribute($keyFee, $valueFee);
                    }
                }
            }
        }
    }

    /**
     * @param DOMElement $parent
     * @param $object
     * @param $property
     * @param bool $withCdata
     * @return DOMElement
     */
    protected function addChild(DOMElement $parent, $object, $property, $withCdata = false)
    {
        $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
        $data = $object->$getter();
        $child = $parent;
        if (is_array($data)) {
            $parentNode = $this->appendElement($parent, $property);
            foreach ($data as $key => $value) {
                $child = $this->appendElement($parentNode, $key, $value, $withCdata);
            }
        }
        else {
            if (isset($data)) {
                $child = $this->appendElement($parent, $property, $data, $withCdata);
            }
        }

        return $child;
    }

    /**
     * @param DOMElement $mapNode
     * @param $name
     * @param $value
     * @return DOMElement
     */
    protected function addAttribute(DOMElement $mapNode, $name, $value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $data) {
                $mapNode->setAttribute($key, $data);
            }
        }
        else {
            if (!empty($data)) {
                $mapNode->setAttribute($name, $value);
            }
        }

        return $mapNode;
    }

    /**
     * @param DOMElement|DOMDocument $parent
     * @param $key
     * @param $value
     * @param bool $asCdata
     * @return DOMElement
     */
    protected function appendElement( $parent, $key, $value = null, $asCdata = false)
    {
        if($asCdata === true)
        {
            $cdata = $this->dom->createCDATASection($value);
            $child = $this->dom->createElement($key);
            $child->appendChild($cdata);
        }
        else
        {
            $child = $this->dom->createElement($key);
            if($value !== null)
            {
                $domValue = $this->dom->createTextNode($value);
                $child->appendChild($domValue);
            }
        }

        $parent->appendChild($child);
        return $child;
    }
}
