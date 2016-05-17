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
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Wizard
    extends Payone_Core_Helper_Abstract
{
    /**
     * @var array
     */
    protected $config = array(
        'migrator' => array(
            'header_text' => '',
            'previous_page' => '',
            'next_page' => 'index',
        ),
        'index' => array(
            'header_text' => '',
            'previous_page' => '',
            'next_page' => 'generalGlobal',
        ),
        'generalGlobal' => array(
            'group_name' => 'global',
            'header_text' => 'Global Settings',
            'codes' => array(
                'page' => 'generalGlobal',
                'section' => 'payone_general',
            ),
            'previous_page' => 'index',
            'next_page' => 'payment',
        ),
        'payment' => array(
            'url' => '*/payonecore_configuration_wizard_page_payment/index',
            'group_name' => 'payment',
            'header_text' => 'Payment-Method Configuration',
            'codes' => array(
                'page' => 'payment',
                'section' => 'payone_payment',
            ),
            'previous_page' => 'generalGlobal',
            'next_page' => 'riskAddressCheck',
        ),
        'riskAddressCheck' => array(
            'group_name' => 'address_check',
            'header_text' => 'Addresscheck',
            'codes' => array(
                'page' => 'riskAddressCheck',
                'section' => 'payone_protect',
            ),
            'previous_page' => 'payment',
            'next_page' => 'riskCreditrating',
        ),
        'riskCreditrating' => array(
            'group_name' => 'creditrating',
            'header_text' => 'Creditrating',
            'codes' => array(
                'page' => 'riskCreditrating',
                'section' => 'payone_protect',
            ),
            'previous_page' => 'riskAddressCheck',
            'next_page' => 'finish'
        ),
        'finish' => array(
            'header_text' => '',
            'previous_page' => 'riskCreditrating',
            'next_page' => '',
        ),
    );

    /**
     * @param $actionName
     * @param $request
     * @return Varien_Object
     */
    public function initConfig($actionName, $request)
    {
        $config = new Varien_Object($this->config);
        $this->registerConfig($config);

        $configPages = $this->initConfigPage($actionName);
        $configPages = new Varien_Object($configPages);

        $codes = $configPages->getData('codes');

        $codes['website'] = $request->getParam('website');
        $codes['store'] = $request->getParam('store');

        $configPages->setData('codes', $codes);

        $this->registerConfigPage($configPages);
        return $configPages;
    }

    /**
     * @return Varien_Object
     */
    public function getConfig()
    {
        /** @var $config Varien_Object */
        return Mage::registry('payone_wizard_config');
    }

    /**
     * @param Varien_Object $config
     */
    public function registerConfig(Varien_Object $config)
    {
        Mage::register('payone_wizard_config', $config);
    }

    /**
     * @return Varien_Object
     */
    public function getConfigPage()
    {
        /** @var $config Varien_Object */
        $config = Mage::registry('payone_wizard_config_page');
        return $config;
    }

    /**
     * @param Varien_Object $config
     */
    public function registerConfigPage(Varien_Object $config)
    {
        Mage::register('payone_wizard_config_page', $config);
    }

    /**
     * @param $page
     * @return array|null
     */
    public function initConfigPage($page)
    {
        if (!array_key_exists($page, $this->config)) {
            return null;
        }
        return $this->config[$page];
    }

    /**
     * @return array
     */
    public function initConfigArray()
    {
        return $this->config;
    }

    public function getNextPageUrlAsString()
    {
        $pageName = $this->getConfigPage()->getData('next_page');
        $url = $this->getPageUrlAsString($pageName);
        return $url;
    }

    public function getPreviousPageUrlAsString()
    {
        $pageName = $this->getConfigPage()->getData('previous_page');
        $url = $this->getPageUrlAsString($pageName);
        return $url;
    }

    public function getPageUrlAsString($nextPageName)
    {
        $config = $this->getConfig();
        $nextPage = $config->getData($nextPageName);
        if (array_key_exists('url', $nextPage)) {
            $url = $nextPage['url'];
        }
        else {
            $url = '*/payonecore_configuration_wizard_page/' . $nextPageName;
        }
        return $url;
    }

    public function getNextPageCode($pageCode)
    {
        $pages = $this->initConfigPage($pageCode);

        if ($pages === null) {
            return 'index';
        }
        if (!array_key_exists('next_page', $pages)) {
            return 'index';
        }

        $nextPageKey = $pages['next_page'];

        return $nextPageKey;
    }

    public function getConfigButton($label, $onClick)
    {
        return array(
            'label' => $this->__($label),
            'class' => 'default',
            'onclick' => $onClick,
        );
    }

    public function getConfigButtonFinish()
    {
        $onClick = 'parent.window.wizardPopup.close()';
        return $this->getConfigButton('Finish', $onClick);
    }

}