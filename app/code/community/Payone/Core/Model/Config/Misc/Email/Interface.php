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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_Core_Model_Config_Misc_Email_Interface
{
    /**
     * @param string $bcc
     */
    public function setBcc($bcc);

    /**
     * @return string
     */
    public function getBcc();

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param int $enabled
     */
    public function setEnabled($enabled);

    /**
     * @return int
     */
    public function getEnabled();

    /**
     * @param string $from
     */
    public function setFrom($from);

    /**
     * @return string
     */
    public function getFrom();

    /**
     * @param string $template
     */
    public function setTemplate($template);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @param string $to
     */
    public function setTo($to);

    /**
     * @return string
     */
    public function getTo();
}
