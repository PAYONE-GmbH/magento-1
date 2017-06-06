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
 * @subpackage      Service
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Core_Model_Service_Amazon_Pay_Checkout
{
    /**
     * @var \Mage_Customer_Model_Session|null
     */
    protected $_customerSession = null;

    /**
     * @var \Payone_Core_Model_Config_Payment_Method|null
     */
    protected $_config = null;

    /**
     * @var \Mage_Sales_Model_Quote|null
     */
    protected $_quote = null;

    /**
     * @param array $params
     * @throws \Exception
     */
    public function __construct($params = [])
    {
        if (isset($params['quote']) && $params['quote'] instanceof \Mage_Sales_Model_Quote) {
            $this->_quote = $params['quote'];
        } else {
            throw new \Exception('Quote object is required.');
        }
        if (isset($params['config']) && $params['config'] instanceof \Payone_Core_Model_Config_Payment_Method) {
            $this->_config = $params['config'];
        } else {
            throw new \Exception('Configuration object is required.');
        }
        $this->_customerSession = Mage::getSingleton('customer/session');
    }
}
