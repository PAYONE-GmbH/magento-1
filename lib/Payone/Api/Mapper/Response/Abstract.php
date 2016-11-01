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
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
abstract class Payone_Api_Mapper_Response_Abstract extends Payone_Api_Mapper_Abstract
{
    // @todo hs: Introduce constants for the various status´ , where? Settings?
    /**
     * @var array
     */
    protected $params = null;

    /**
     * @return bool
     */
    protected function isApproved()
    {
        $status = $this->getParam('status');
        if ($status === 'APPROVED') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isRedirect()
    {
        $status = $this->getParam('status');
        if ($status === 'REDIRECT') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isValid()
    {
        $status = $this->getParam('status');
        if ($status === 'VALID') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isInvalid()
    {
        $status = $this->getParam('status');
        if ($status === 'INVALID') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isBlocked()
    {
        $status = $this->getParam('status');
        if ($status === 'BLOCKED') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isEnrolled()
    {
        $status = $this->getParam('status');
        if ($status === 'ENROLLED') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isError()
    {
        $status = $this->getParam('status');
        if ($status === 'ERROR') {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function isOk()
    {
        $status = $this->getParam('status');
        if ($status === 'OK') {
            return true;
        }

        return false;
    }

    /**
     * @param array $responseRaw
     */
    protected function setParams($responseRaw)
    {
        $this->params = $responseRaw;
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return $this->params;
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function getParam($key)
    {
        if (is_array($this->params) and array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        else
        {
            return null;
        }
    }
}
