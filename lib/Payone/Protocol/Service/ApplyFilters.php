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
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Protocol
 * @subpackage      Service
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Protocol_Service_ApplyFilters
{
    /**
     * @var Payone_Protocol_Filter_Interface[]
     */
    protected $filters = array();

    protected $filters_config = array(
        Payone_Protocol_Filter_MaskAllValue::FILTER_KEY => array(
            'bankaccount',
            'iban',
            'cardpan',
            'cardcvc2',
            'cardexpiredate',
            'key'
        ),
        Payone_Protocol_Filter_MaskValue::FILTER_KEY => array(
            'bankaccountholder',
            'cardholder',
            'firstname',
            'lastname',
            'company',
            'street',
            'streetname',
            'streetnumber',
            'addressaddition',
            'email',
            'telephonenumber',
            'birthday',
            'shipping_firstname',
            'shipping_lastname',
            'shipping_company',
            'shipping_street',
            'shipping_addressaddition',
        ),
    );

    /**
     * @param array $objectArray
     * @return string
     */
    public function apply(array $objectArray)
    {
        $stringArray = array();

        foreach ($objectArray as $key => $value) {

            if($value instanceof Payone_Api_Response_Parameter_Interface){
                $stringArray[] = $this->apply($value->toArray());
            } elseif ($value !== null) {
                $filter = $this->getFilterConfig($key);

                if ($filter != null) {
                    $value = $filter->filterValue($value);
                }
            }

            $stringArray[] = $key . '=' . $value;
        }

        return implode('|', $stringArray);
    }

    /**
     * @param Payone_Protocol_Filter_Interface $filter
     */
    public function addFilter(Payone_Protocol_Filter_Interface $filter)
    {
        $this->filters[$filter->getKey()] = $filter;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function removeFilter($key)
    {
        if (array_key_exists($key, $this->filters)) {
            unset($this->filters[$key]);
            return true;
        }
        return false;
    }

    /**
     * @param Payone_Protocol_Filter_Interface[] $filters
     */
    public function setFilters(array $filters)
    {
        $this->filters = array();
        foreach($filters as $key => /** @var $value Payone_Protocol_Filter_Interface */ $value)
        {
            $this->addFilter($value);
        }
    }

    /**
     * @return Payone_Protocol_Filter_Interface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param $key
     * @return Payone_Protocol_Filter_Interface
     */
    public function getFilter($key)
    {
        if (array_key_exists($key, $this->filters)) {
            return $this->filters[$key];
        }
        // if the requested filter was not found return Payone_Protocol_Filter_MaskAllValue
        return new Payone_Protocol_Filter_MaskAllValue();
    }

    /**
     * @param $key
     * @return null|Payone_Protocol_Filter_Interface
     */
    public function getFilterConfig($key)
    {
        foreach($this->filters_config as $filter => $config) {
            if(in_array($key, $config)) {
                return $this->getFilter($filter);
            }
        }
        return null;
    }

}
