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
 * @subpackage      Payment
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Payment_Data extends Mage_Payment_Helper_Data
{
    /**
     * @var array
     */
    protected $deletedMethodTitles = array(
        'payone_masterpass' => 'Masterpass',
    );

    /**
     * @var array
     */
    protected $deletedMethodClearingTypes = array(
        'masterpass' => 'wlt'
    );

    public function getMethodInstance($code)
    {
        $instance = parent::getMethodInstance($code);

        if (!$instance && isset($this->deletedMethodTitles[$code])) {
            $instance = Mage::getModel('payone_core/payment_method_pseudo');
            $instance->setName($this->deletedMethodTitles[$code]);
        }

        return $instance;
    }

    /**
     * @param string $code
     * @return string
     */
    public function getDeletedMethodClearingType($code)
    {
        return isset($this->deletedMethodClearingTypes[$code]) ? $this->deletedMethodClearingTypes[$code] : '';
    }
}