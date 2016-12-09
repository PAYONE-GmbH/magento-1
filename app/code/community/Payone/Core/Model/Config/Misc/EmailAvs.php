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
class Payone_Core_Model_Config_Misc_EmailAvs
    extends Payone_Core_Model_Config_Misc_Email_Abstract
{
    /**
     * @var array
     */
    protected $result_avs = null;

    /**
     * @param array $result_avs
     */
    public function setResultAvs($result_avs)
    {
        $this->result_avs = $result_avs;
    }

    /**
     * @return array
     */
    public function getResultAvs()
    {
        return $this->result_avs;
    }

    /**
     * @param $responseAvs
     * @return bool
     */
    public function isResultAvsInConfig($responseAvs)
    {
        $resultAvsArray = explode(',', $this->getResultAvs());
        $resultAvsArray = array_flip($resultAvsArray);
        if (array_key_exists($responseAvs, $resultAvsArray)) {
            return true;
        }

        return false;
    }
}
