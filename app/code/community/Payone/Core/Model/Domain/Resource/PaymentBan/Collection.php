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
 * @copyright       Copyright (c) 2017 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Domain
 * @copyright       Copyright (c) 2012 <info@votum.de> - www.votum.de
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

class Payone_Core_Model_Domain_Resource_PaymentBan_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('payone_core/domain_paymentBan');
    }
}
