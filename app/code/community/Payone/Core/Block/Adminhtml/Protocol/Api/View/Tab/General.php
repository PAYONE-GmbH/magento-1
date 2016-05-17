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
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Adminhtml_Protocol
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Adminhtml_Protocol_Api_View_Tab_General extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->helperPayoneCore()->__('General');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return $this->helperPayoneCore()->__('General');
    }

    /**
     * Converts timezone from "GMT" to locale timezone
     * @param $string
     * @return string|null
     */
    public function getLocaleDatetime($string)
    {
        $helper = $this->helperPayoneCore();
        return $helper->getLocaleDatetime($string);
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_Api
     */
    public function getApi()
    {
        return Mage::registry('payone_core_protocol_api');
    }

    /**
     *
     * @return Payone_Core_Helper_Data
     */
    protected function helperPayoneCore()
    {
        return Mage::helper('payone_core');
    }

    /**
     * @return string
     */
    public function getPreparedRawRequest()
    {
        $api = $this->getApi();
        $rawRequestArray = $api->getRawRequestAsArray();

        return $rawRequestArray;
    }

    /**
     * @return string
     */
    public function getPreparedRawResponse()
    {
        $api = $this->getApi();
        $rawResponseArray = $api->getRawResponseAsArray();

        return $rawResponseArray;
    }
}