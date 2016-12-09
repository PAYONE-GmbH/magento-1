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
 * @package         Payone_Api
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Response
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Response_Invalid extends Payone_Api_Response_Abstract
{
    /**
     * @var string
     */
    protected $status = NULL;
    /**
     * @var string
     */
    protected $errorcode = NULL;
    /**
     * @var string
     */
    protected $errormessage = NULL;
    /**
     * @var string
     */
    protected $customermessage = NULL;

    /**
     * @param string $customermessage
     */
    public function setCustomermessage($customermessage)
    {
        $this->customermessage = $customermessage;
    }

    /**
     * @return string
     */
    public function getCustomermessage()
    {
        return $this->customermessage;
    }

    /**
     * @param string $errorcode
     */
    public function setErrorcode($errorcode)
    {
        $this->errorcode = $errorcode;
    }

    /**
     * @return string
     */
    public function getErrorcode()
    {
        return $this->errorcode;
    }

    /**
     * @param string $errormessage
     */
    public function setErrormessage($errormessage)
    {
        $this->errormessage = $errormessage;
    }

    /**
     * @return string
     */
    public function getErrormessage()
    {
        return $this->errormessage;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

}
