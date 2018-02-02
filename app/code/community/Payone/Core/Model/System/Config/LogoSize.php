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
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_System_Config_LogoSize extends Payone_Core_Model_System_Config_Abstract
{
    const EXTRA_SMALL = 'xs';
    const SMALL = 's';
    const MEDIUM = 'm';
    const LARGE = 'l';
    const EXTRA_LARGE = 'xl';

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            self::EXTRA_SMALL => Mage::helper('payone_core')->__('Extra small') . ' (xs)',
            self::SMALL => Mage::helper('payone_core')->__('Small') . ' (s)',
            self::MEDIUM => Mage::helper('payone_core')->__('Medium') . ' (m)',
            self::LARGE => Mage::helper('payone_core')->__('Large') . ' (l)',
            self::EXTRA_LARGE => Mage::helper('payone_core')->__('Extra large') . ' (xl)',
        );
    }

    /**
     * @param bool $landscape
     * @return array
     */
    public function toPixelSize($landscape = true)
    {
        if (!$landscape) {
            return array(
                self::EXTRA_SMALL => array(11, 35),
                self::SMALL => array(24, 75),
                self::MEDIUM => array(34, 105),
                self::LARGE => array(105, 325),
                self::EXTRA_LARGE => array(210, 650)
            );
        }

        return array(
            self::EXTRA_SMALL => array(35, 11),
            self::SMALL => array(75, 24),
            self::MEDIUM => array(105, 34),
            self::LARGE => array(325, 105),
            self::EXTRA_LARGE => array(650, 210)
        );
    }
}
