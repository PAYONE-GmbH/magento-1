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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Protocol
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Protocol_Config_Filter
{
    /**
     * @var array
     */
    protected $mapping = array(
        'Payone_Api_Request_Preauthorization' => array(
            'payment/bankaccount' => 'mask_value',
            'payment/cardpan' => 'mask_value'
        ),
        'Payone_Api_Request_Authorization' => array(
            'payment/bankaccount' => 'mask_value',
            'payment/cardpan' => 'mask_value'
        ),
        'Payone_Api_Request_Refund' => array(
            'payment/bankaccount' => 'mask_value'
        ),
        'Payone_Api_Request_Debit' => array(
            'payment/bankaccount' => 'mask_value',
            'payment/cardpan' => 'mask_value'
        ),
        'Payone_Api_Request_BankAccountCheck' => array(
            'bankaccount' => 'mask_value'
        ),
        'Payone_Api_Request_CreditCardCheck' => array(
            'cardpan' => 'mask_value'
        ),
        'Payone_Api_Request_3dsCheck' => array(
            'cardpan' => 'mask_value'
        ),
        'Payone_Api_Request_CreateAccess' => array(
            'payment/cardpan' => 'mask_value',
            'payment/bankaccount' => 'mask_value'
        ),
        'Payone_Api_Request_Vauthorization' => array(
            'payment/cardpan' => 'mask_value',
            'payment/bankaccount' => 'mask_value'
        )
    );

    /**
     * @param array $mapping
     */
    public function __construct(array $mapping = array())
    {
        if (count($mapping) > 0) {
            $this->setMapping($mapping);
        }
    }

    /**
     * @param string $class
     * @return mixed
     */
    public function getFiltersByClass($class)
    {
        if (array_key_exists($class, $this->mapping)) {
            return $this->mapping[$class];
        }
    }

    /**
     * @param array $mapping
     */
    public function setMapping(array $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }
}
