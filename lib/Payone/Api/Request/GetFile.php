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
class Payone_Api_Request_GetFile extends Payone_Api_Request_Abstract
{
    protected $request = Payone_Api_Enum_RequestType::GETFILE;

    /**
     * @var int
     */
    protected $file_reference = NULL;
    /**
     * @var string
     */
    protected $file_type = NULL;
    /**
     * @var string
     */
    protected $file_format = NULL;


    /**
     * @param int $fileReference
     */
    public function setFileReference($fileReference)
    {
        $this->file_reference = $fileReference;
    }

    /**
     * @return int
     */
    public function getFileReference()
    {
        return $this->file_reference;
    }

    /**
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->file_type = $fileType;
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->file_type;
    }

    /**
     * @param string $fileFormat
     */
    public function setFileFormat($fileFormat)
    {
        $this->file_format = $fileFormat;
    }

    /**
     * @return string
     */
    public function getFileFormat()
    {
        return $this->file_format;
    }
}
