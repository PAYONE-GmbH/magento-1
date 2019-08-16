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
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */
class Payone_Api_Request_AmazonPayConfirmOrderReference extends Payone_Api_Request_Genericpayment
{
    /** @var string */
    protected $successurl = '';

    /** @var string */
    protected $errorurl = '';

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getSuccessurl()
    {
        return $this->successurl;
    }

    /**
     * @param string $successurl
     */
    public function setSuccessurl($successurl)
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string
     */
    public function getErrorurl()
    {
        return $this->errorurl;
    }

    /**
     * @param string $errorurl
     */
    public function setErrorurl($errorurl)
    {
        $this->errorurl = $errorurl;
    }
}
