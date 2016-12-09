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
interface Payone_Api_Response_Interface extends Payone_Protocol_Filter_Filterable
{
    /**
     * @abstract
     * @param array $data
     */
    public function init(array $data = array());

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function __toString();
    /**
     * @param string $status
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return bool
     */
    public function isApproved();

    /**
     * @return bool
     */
    public function isRedirect();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return bool
     */
    public function isInvalid();

    /**
     * @return bool
     */
    public function isBlocked();

    /**
     * @return bool
     */
    public function isEnrolled();

    /**
     * @return bool
     */
    public function isError();

    /** @return string */
    public function getRawResponse();

    /** @param string $rawResponse */
    public function setRawResponse($rawResponse);
}