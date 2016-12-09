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
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
interface Payone_Api_Request_Interface extends Payone_Protocol_Filter_Filterable
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
     * @return array
     */
    public function __toString();

    /**
     * @abstract
     * @param string $property
     */
    public function get($property);

    /**
     * @abstract
     * @param string $property
     * @param string $value
     */
    public function set($property, $value);

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding);

    /**
     * @return string
     */
    public function getEncoding();

    /**
     * @param string $key
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param int $mid
     */
    public function setMid($mid);

    /**
     * @return int
     */
    public function getMid();

    /**
     * @param string $mode
     */
    public function setMode($mode);

    /**
     * @return string
     */
    public function getMode();

    /**
     * @param int $portalid
     */
    public function setPortalid($portalid);

    /**
     * @return int
     */
    public function getPortalid();

    /**
     * @param string $request
     */
    public function setRequest($request);

    /**
     * @return string
     */
    public function getRequest();


    /**
     * @param string $integrator_name
     */
    public function setIntegratorName($integrator_name);

    /**
     * @return string
     */
    public function getIntegratorName();

    /**
     * @param string $integrator_version
     */
    public function setIntegratorVersion($integrator_version);

    /**
     * @return string
     */
    public function getIntegratorVersion();

    /**
     * @param string $solution_name
     */
    public function setSolutionName($solution_name);

    /**
     * @return string
     */
    public function getSolutionName();

    /**
     * @param string $solution_version
     */
    public function setSolutionVersion($solution_version);

    /**
     * @return string
     */
    public function getSolutionVersion();
}
