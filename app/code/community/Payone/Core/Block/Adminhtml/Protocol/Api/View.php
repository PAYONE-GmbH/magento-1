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
class Payone_Core_Block_Adminhtml_Protocol_Api_View
    extends Payone_Core_Block_Adminhtml_Widget_View_Container
{

    /**
     *
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'payone_core_api_view';
        $this->_controller = 'adminhtml_protocol_api';

        parent::__construct();

        $this->setId('protocol_api_view');

        $this->_removeButton('edit');
    }

    /**
     * @return string
     */
    public function getHeaderText()
    {
        $api = $this->getProtocolApi();
        $text = Mage::helper('payone_core')->__('Protocol - Api #%s', $api->getId());
        return $text;
    }

    /**
     * @return Payone_Core_Block_Adminhtml_Protocol_Api_View
     */
    protected function _prepareLayout()
    {
        foreach ($this->_buttons as $level => $buttons) {
            foreach ($buttons as $id => $data) {
                $childId = $this->_prepareButtonBlockId($id);
                $this->_addButtonChildBlock($childId);
            }
        }

        $this->setChild(
            'plane', $this->getLayout()
            ->createBlock('payone_core/adminhtml_protocol_api_view_plane', 'payone_core_adminhtml_protocol_api_view_plane')
        );
        return $this;
    }

    /**
     * @return Payone_Core_Model_Domain_Protocol_Api
     */
    public function getProtocolApi()
    {
        return Mage::registry('payone_core_protocol_api');
    }

}