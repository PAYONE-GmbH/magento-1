<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2016 <support@e3n.de> - www.e3n.de, Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Tim Rein <tim.rein@e3n.de>, Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.e3n.de, http://www.fatchip.com
 */

/**
 * Class Payone_Core_Block_Checkout_RatePayInstallmentplan
 */
class Payone_Core_Block_Checkout_RatePayInstallmentplan extends Mage_Core_Block_Template
{
    /** @var bool */
    protected $isAdmin = false;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payone/core/payment/method/ratepay_installment_plan_result.phtml');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }
}
