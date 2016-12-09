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
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Protocol
 * @subpackage      Filter
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Protocol
 * @subpackage      Filter
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Protocol_Filter_MaskValue
    extends Payone_Protocol_Filter_Abstract
{
    const MASK_CHAR = 'x';
    const FILTER_KEY = 'mask_value';
    protected $key = self::FILTER_KEY;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function filterValue($value)
    {
        $lengthValue = strlen($value);

        // Calc the number of chars to replace
        $percentage = $this->getConfigPercent();

        // Check if we have to replace anything at all
        if ($percentage == 0) {
            return $value;
        }

        $numReplaceChars = ($lengthValue / (100 / $percentage));

        $numReplaceChars = round($numReplaceChars);
        // If there are no chars to be replaced return
        if ($numReplaceChars == 0) {
            return $value;
        }

        // Calc the char pos to start with
        $start = round(($lengthValue - $numReplaceChars) / 2);

        // Generate the mask
        $mask = str_repeat(self::MASK_CHAR, $numReplaceChars);

        if ($numReplaceChars == $lengthValue) {
            $start = 0;
        }

        // Replace the calculated part with mask
        $maskedNumber = substr_replace($value, $mask, $start, $numReplaceChars);

        return $maskedNumber;
    }

    /**
     * @param int $value
     */
    public function setConfigPercent($value)
    {
        $this->setConfig('percent', $value);
    }

    /**
     * @return string
     */
    public function getConfigPercent()
    {
        return $this->getConfig('percent');
    }

}
