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
 * @subpackage      Repository
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Repository
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Repository_TransactionStatus
    implements Payone_TransactionStatus_Persistence_Interface
{
    /** @var Payone_Core_Model_Factory */
    protected $factory = null;

    const KEY = 'p1_magento_ts';

    /**
     * @return string
     */
    public function getKey()
    {
        return self::KEY;
    }

    /**
     * @param Payone_TransactionStatus_Request_Interface $request
     * @param Payone_TransactionStatus_Response_Interface $response
     * @return boolean
     */
    public function save(
        Payone_TransactionStatus_Request_Interface $request,
        Payone_TransactionStatus_Response_Interface $response
    )
    {
        $factory = $this->getFactory();
        $domainObject = $factory->getModelTransactionStatus();

        /* map request to domain object */
        $data = $request->toArray();

        // UTF-8 encoding, PAYONE sends ISO-encoded TransactionStatus, we want to preserve special characters (e.g. Umlauts in clearing parameters)
        foreach($data as $key => $value)
        {
            $data[$key] = utf8_encode($value);
        }

        $aRequest = Mage::app()->getRequest()->getParams();
        $data['raw_request'] = serialize($aRequest);

        $domainObject->setData($data);

        $domainObject->save();
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