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
 * @package         Payone_TransactionStatus
 * @subpackage      Validator
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_TransactionStatus
 * @subpackage      Validator
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_TransactionStatus_Validator_DefaultParameters
    extends Payone_TransactionStatus_Validator_Abstract
{
    /**
     * @var string
     */
    protected $key = '';

    /**
     * @param Payone_TransactionStatus_Request_Interface $request
     * @return bool
     * @throws Payone_TransactionStatus_Exception_MissmatchingKeys
     */
    public function validateRequest(Payone_TransactionStatus_Request_Interface $request)
    {
        if ($request->getKey() !== $this->getKey()) {
            throw new Payone_TransactionStatus_Exception_MissmatchingKeys();
        }

        return true;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}
