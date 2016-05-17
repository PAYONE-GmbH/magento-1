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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Block
 * @subpackage      Payment
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Block_Checkout_Onepage_Review_SepaMandate extends Mage_Core_Block_Template
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    /**
     * @return string
     */
    public function getMandateText()
    {
        $mandateText = $this->getFactory()->getSingletonCheckoutSession()->getPayoneSepaMandateText();
        return $mandateText;
    }

    /**
     * @return string
     */
    public function getMandateTextDecoded()
    {
        return urldecode($this->getMandateText());
    }

    /**
     * @return string
     */
    protected function getMandateStatus()
    {
        return $this->getFactory()->getSingletonCheckoutSession()->getPayoneSepaMandateStatus();
    }

    /**
     * @return bool
     */
    public function showMandate()
    {
        $mandateText = $this->getMandateText();
        if (!empty($mandateText) and $this->getMandateStatus() == Payone_Core_Model_Service_Management_ManageMandate::STATUS_PENDING) {
            return true;
        }

        return false;
    }

    /**
     * @param string $html
     * @return string
     */
    public function _afterToHtml($html)
    {
        /*
        * after block is rendered we should clear relevant checkout session fields to avoid
        * falsy interpretation on further checkout passes
        */
        // do not unset mandate status as we need it in further processing
        $this->getFactory()->getSingletonCheckoutSession()->unsetData('payone_sepa_mandate_text');
        return parent::_afterToHtml($html);
    }

    /**
     * @param Payone_Core_Model_Factory $factory
     */
    public function setFactory(Payone_Core_Model_Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }
        return $this->factory;
    }
}