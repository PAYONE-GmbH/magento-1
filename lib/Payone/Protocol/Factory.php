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
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Protocol_Factory
{
    /**
     * @return Payone_Protocol_Service_ApplyFilters
     */
    public function buildServiceApplyFilters()
    {
        $serviceAF = new Payone_Protocol_Service_ApplyFilters();

        $filters = $this->buildFiltersDefault();

        $serviceAF->setFilters($filters);

        return $serviceAF;
    }

    /**
     * @return Payone_Protocol_Filter_Interface[]
     */
    protected function buildFiltersDefault()
    {
        $filters = array();
        $filterMaskValue = new Payone_Protocol_Filter_MaskValue();
        $filterMaskValue->setConfigPercent(50); // @todo hs: This default value should be defined somewhere else, configuration?.
        array_push($filters, $filterMaskValue);

        $filterMaskAllValue = new Payone_Protocol_Filter_MaskAllValue();
        array_push($filters, $filterMaskAllValue);

        return $filters;
    }
}
