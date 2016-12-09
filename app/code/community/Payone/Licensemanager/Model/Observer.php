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
 * @package         Payone_Licensemanager_Model
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Licensemanager_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

class Payone_Licensemanager_Model_Observer
{

    protected $invoked = false;

    /**
     * @param Varien_Event_Observer $observer
     * @return Votum_SapCustomer_Model_Observer
     */
    public function checkLicense(Varien_Event_Observer $observer)
    {
        if(!$this->invoked) {
            $this->invoked = true;
            $helper = Mage::helper('payone_licensemanager');
            if(!$helper->isPayoneRegisterd())
            {
                $helper->setPayoneModuleDeactive();
            }
        }

        return $this;
    }
}