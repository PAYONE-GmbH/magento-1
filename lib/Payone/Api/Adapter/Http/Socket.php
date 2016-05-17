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
 * @subpackage      Adapter
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Adapter
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Adapter_Http_Socket extends Payone_Api_Adapter_Http_Abstract
{
    protected function doRequest()
    {
        $response = array();
        $errno = '';
        $errstr = '';
        $urlArray = $this->generateUrlArray();

        $urlHost = $urlArray['host'];
        $urlPath = isset($urlArray['path']) ? $urlArray['path'] : '';
        $urlScheme = $urlArray['scheme'];
        $urlQuery = $urlArray['query'];

        $socketScheme = '';
        $socketPort = 80;

        if ($urlScheme == 'https') {
            $socketScheme = 'ssl://';
            $socketPort = 443;
        }

        // Request - Method
        $method = 'Post';

        // Request - Header
        $headers = array();
        $headers[] = "POST " . $urlPath . " HTTP/1.1\r\n";
        $headers[] = "Host: " . $urlHost . "\r\n";
        $headers[] = "Content-Type: application/x-www-form-urlencoded\r\n";
        $headers[] = "Content-Length: " . strlen($urlQuery) . "\r\n";
        $headers[] = "Connection: close\r\n\r\n";

        // Request - Body
        $body = $urlArray['query'];

        // Request - Build
        $request = "{$method} {$urlPath} HTTP/1.1\r\n";
        foreach ($headers as $k => $v) {
            $request .= "$v\r\n";
        }

        $request .= "\r\n" . $body;

        // Socket - Connect
        $flags = STREAM_CLIENT_CONNECT;
        $context = stream_context_create();
        $socket = @stream_socket_client(
            $socketScheme . $urlHost . ':' . $socketPort,
            $errno,
            $errstr,
            self::DEFAULT_TIMEOUT,
            $flags,
            $context
        );

        // Socket - Write
        if (!@fwrite($socket, $request)) {
            throw new Payone_Api_Exception_WritingRequestToServer();
        }

        $gotStatus = false;
        while (($line = @fgets($socket)) !== false) {
            $gotStatus = $gotStatus || (strpos($line, 'HTTP') !== false);
            if ($gotStatus) {
                $response[] = $line;
                if (rtrim($line) === '') {
                    break;
                }
            }
        }

        if (count($response) == 0) {
            throw new Payone_Api_Exception_InvalidResponse();
        }

        return $response;
    }

}
