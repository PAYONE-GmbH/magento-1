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
 * @package         Payone_SessionStatus
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_SessionStatus
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_SessionStatus_Mapper_Request
    implements Payone_SessionStatus_Mapper_RequestInterface
{
    public function mapByArray(array $data)
    {
        $request = new Payone_SessionStatus_Request();

        // @todo currently simple mapping, could be more complex
        $this->mapDefaultParameters($data, $request);
        $this->mapStatusItems($data, $request);

        return $request;
    }

    /**
     * @param array $data
     * @param Payone_SessionStatus_Request $request
     * @return bool
     */
    protected function mapDefaultParameters(array $data, Payone_SessionStatus_Request $request)
    {
        if (array_key_exists('key', $data)) {
            $request->setKey($data['key']);
        }

        return true;
    }

    /**
     * @param array $data
     * @param Payone_SessionStatus_Request $request
     * @return bool
     */
    protected function mapStatusItems(array $data, Payone_SessionStatus_Request $request)
    {
        unset($data['key']);
        $itemsData = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subkey => $subvalue) {
                    $itemsData[$subkey][$key] = $subvalue;
                }
            }
        }

        $items = array();
        foreach ($itemsData as $item) {
            $items[] = new Payone_SessionStatus_Request_Item($item);
        }

        $request->setSessionStatusItems($items);

        return true;
    }
}
