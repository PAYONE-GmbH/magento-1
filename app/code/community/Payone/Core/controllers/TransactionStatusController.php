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
 * @package         Payone_Core_controllers
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_TransactionStatusController extends Payone_Core_Controller_Abstract
{
    /**
     * Receives TransactionStatus from Payone, maps and saves it in database.
     * Reports TSOK response to Payone if successful.
     * Processing of saved TransactionStatus does not happen immediately.
     *
     * @return mixed
     * @throws Payone_Core_Exception_OrderNotFound
     */
    public function indexAction()
    {
        try {
            // Show no-route page if no Post Request
            if (!$this->getRequest()->isPost()) {
                $this->norouteAction();
                return;
            }

            // only retrieve Data from POST
            $this->getRequest()->setParamSources(array('_POST'));

            // Get Reference (order increment_id)
            $reference = $this->getRequest()->getParam('reference');

            // Load Order by Reference
            $order = $this->getFactory()->getModelSalesOrder();
            $order->loadByIncrementId($reference);

            if (!$order->hasData()) {
                throw new Payone_Core_Exception_OrderNotFound();
            }

            // Get used config for this order
            $configPaymentMethod = $this->getConfigPaymentMethod($order);
            $configTransactionStatusProcessing = $this->getConfigTransactionStatusProcessing($order->getStoreId());

            // Prepare Transaction Status handler
            $key = $configPaymentMethod->getKey();
            $validIps = $configTransactionStatusProcessing->getValidIps();
            $service = $this->getFactory()->getServiceTransactionStatusHandleRequest($key, $validIps);

            // Handle Request:
            $response = $service->handleByPost();

            // NEW forwarding handling
            $this->_forwardStatus($order);
            
            // Send Confirmation Message
            $this->getResponse()->setBody($response->getStatus());
        }
        catch(Payone_TransactionStatus_Exception_Validation $e)
        {
            // Throw generic error.
            $type = get_class($e);
            $message = 'ERROR='.$type;

            $this->getResponse()->setBody($message);
        }
        catch (Exception $e)
        {
            $type = get_class($e);

            $message = 'ERROR='.$type.'|MESSAGE='.$e->getMessage();

            // Send Confirmation Message
            $this->getResponse()->setBody($message);

            Mage::logException($e);
        }
    }

    /**
     * Detects used Config for this Order
     *
     * @param Mage_Sales_Model_Order $order
     * @return bool|Payone_Core_Model_Config_Payment_Method_Interface
     */
    protected function getConfigPaymentMethod(Mage_Sales_Model_Order $order)
    {
        return $this->helperConfig()->getConfigPaymentMethodByOrder($order);
    }

    protected function getConfigTransactionStatusProcessing($storeId)
    {
        return $this->helperConfig()->getConfigMisc($storeId)->getTransactionstatusProcessing();
    }
    
    protected function _forwardStatus($oOrder) 
    {
        $sAction = $this->getRequest()->getParam('txaction');

        $oMisc = $this->helperConfig()->getConfigMisc($oOrder->getStoreId());
        $oForwarding = $oMisc->getTransactionstatusForwarding();
        if (!$oForwarding->isActive() || !$oForwarding->canForwardTxAction($sAction)) {
            return;
        }

        $aForwardUrls = $oForwarding->getConfig($sAction);

        foreach ($aForwardUrls as $aHost) {
            $this->_forwardRequest($aHost);
        }
    }
    
    protected function _addParam($sKey, $mValue) 
    {
        $sParams = '';
        if(is_array($mValue)) {
            foreach ($mValue as $sKey2 => $mValue2) {
                $sParams .= $this->_addParam($sKey.'['.$sKey2.']', $mValue2);
            }
        } else {
            $sParams .= "&".$sKey."=".urlencode($mValue);
        }

        return $sParams;
    }
    
    protected function _forwardRequest($aHost) 
    {
        if(array_key_exists('url', $aHost) === false) {
            return;
        }

        $sUrl = $aHost['url'];
        
        $iTimeout = 15;
        if(array_key_exists('timeout', $aHost) !== false) {
            $iTimeout = $aHost['timeout'];
        }
        
        $sParams = '';
        $aRequest = Mage::app()->getRequest()->getParams();
        foreach($aRequest as $sKey => $mValue) {
            $sParams .= $this->_addParam($sKey, $mValue);
        }

        $sParams = substr($sParams, 1);

        $oCurl = curl_init($sUrl);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sParams);

        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($oCurl, CURLOPT_TIMEOUT, $iTimeout);

        $oResult = curl_exec($oCurl);

        $sCurlError = curl_error($oCurl);
        $sCurlErrorNr = curl_errno($oCurl);
        if(!empty($sCurlError) && !empty($sCurlErrorNr)) {
            $sLogDir = dirname(__FILE__).'/../../../../../../var/log/';
            $oLog = fopen($sLogDir.'payone_transaction_forwarding.log', "a");
            fwrite($oLog, date('[Y-m-d H:i:s]').' - Curl-Error-Nr: '.$sCurlErrorNr.' - Message: '.$sCurlError."\n");
            fclose($oLog);
        }
        
        curl_close($oCurl);
    }

}