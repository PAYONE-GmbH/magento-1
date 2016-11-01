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
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

require_once 'Mage' . DS . 'Checkout' . DS . 'controllers' . DS . 'OnepageController.php';

/**
 *
 * @category        Payone
 * @package         Payone_Core_controllers
 * @subpackage      Checkout
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
    protected $eventPrefix = 'payone_core_checkout_onepage';

    /** @var Payone_Core_Model_Config_Payment_Method_Interface */
    protected $paymentConfig = null;
    
    protected $helperConfig = null;
    
    protected $factory = null;
    
    protected static $_aLoadedScopes = array();
    
    protected static function _addLoadedScope($sScope) 
    {
        self::$_aLoadedScopes[] = $sScope;
    }
    
    /**
     * Predispatch: should set layout area
     *
     * @return Mage_Checkout_OnepageController
     */
    public function preDispatch()
    {
        // only extend the function in compiler-mode
        if (defined('COMPILER_INCLUDE_PATH')) {
            /*
             * This basically disables the usage ob the /includes/src/__checkout.php file, because there were autoloader problems. ( Mage-205 )
             * The files will be loaded "normally" from the single files, when the verifyPaymentAction from the Payone controller was called
             */
            
            $sRouteName = $this->getRequest()->getRouteName();
            if($sRouteName == 'payone_core') {
                $sRouteName = 'checkout';
            }

            if(array_search($sRouteName, self::$_aLoadedScopes) === false) {
                self::_addLoadedScope($sRouteName);
            } else {
                $this->setFlag('', self::FLAG_NO_PRE_DISPATCH, 1);
            }
        }

        return parent::preDispatch();
    }
    
    /**
     * verfiy payment ajax action
     *
     * Sets either redirect or a JSON response
     */
    public function verifyPaymentAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }

            // Dispatch Event
            $settings = $this->dispatchEvent();

            $result = array();
            if ($settings->getData('have_to_filter_methods') == true) {
                // register Allowed methods
                $allowedMethods = $settings->getData('allowed_methods');

                Mage::register('payment_methods_allowed_methods', $allowedMethods, true);

                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            else {
                $this->_forward('savePayment', 'onepage', 'checkout');
                return;
            }
        }
        catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }

            $result['error'] = $e->getMessage();
        }
        catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        }
        catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = $this->__('Unable to set Payment Method.');
        }

        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($result)
        );
    }

    /**
     * @return Varien_Object
     */
    protected function dispatchEvent()
    {
        $settings = new Varien_Object();
        $settings->setData('have_to_filter_methods', false);

        $allowedMethods = new Varien_Object();
        $settings->setData('allowed_methods', $allowedMethods);

        $paymentData = $this->getRequest()->getPost('payment', array());
        $selectedMethod = $paymentData['method'];

        $parameters = array(
            'settings' => $settings,
            'quote' => $this->getOnepage()->getQuote(),
            'selected_method' => $selectedMethod,
            'payment_data' => $paymentData,
            'full_action_name' => $this->getFullActionName('/'),
        );

        // Dispatch Event
        Mage::dispatchEvent($this->eventPrefix . '_verify_payment', $parameters);

        return $settings;
    }
    
    protected function _init()
    {
        $oQuote = $this->getOnepage()->getQuote();
        $aPost = $this->getRequest()->getPost();
        $sPaymentMethodCodeId = $aPost['payone_config_payment_method_id'];
        if (empty($sPaymentMethodCodeId)) {
            throw new Payone_Core_Exception_PaymentMethodConfigNotFound();
        }

        $oPaymentConfig = $this->helperConfig()->getConfigPaymentMethodById($sPaymentMethodCodeId, $oQuote->getStoreId());
        $this->setPaymentConfig($oPaymentConfig);
    }
    
    protected function _handlePayolutionPreCheck()
    {
        $oService = $this->getFactory()->getServicePaymentGenericpayment($this->getPaymentConfig());
        $oMapper = $oService->getMapper();
        $oRequest = $oMapper->addPayolutionPreCheckParameters($this->getOnepage()->getQuote(), $this->getRequest()->getPost());
        $oResponse = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($oRequest);
        
        if($oResponse instanceof Payone_Api_Response_Genericpayment_Ok) {
            $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
            $checkoutSession->setPayoneWorkorderId($oResponse->getWorkorderId());
            return true;
        }

        return false;
    }

    protected function _setInstallmentDraftDownloadLinks($aInstallments)
    {
        $aDownloadLinks = array();
        foreach ($aInstallments as $aInstallment) {
            $aDownloadLinks[$aInstallment['duration']] = $aInstallment['standardcreditinformationurl'];
        }

        $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
        $checkoutSession->setInstallmentDraftLinks($aDownloadLinks);
    }
    
    protected function _handlePayolutionCalculation()
    {
        $oService = $this->getFactory()->getServicePaymentGenericpayment($this->getPaymentConfig());
        $oMapper = $oService->getMapper();
        $oRequest = $oMapper->addPayolutionCalculationParameters($this->getOnepage()->getQuote());
        $oResponse = $this->getFactory()->getServiceApiPaymentGenericpayment()->request($oRequest);
        
        if($oResponse instanceof Payone_Api_Response_Genericpayment_Ok) {
            $checkoutSession = $this->getFactory()->getSingletonCheckoutSession();
            $checkoutSession->setPayoneWorkorderId($oResponse->getWorkorderId());
            $aInstallments = $oResponse->getInstallmentData();
            
            $this->_setInstallmentDraftDownloadLinks($aInstallments);
            
            return $aInstallments;
        }

        return false;
    }
    
    /**
     * Get shipping method step html
     *
     * @return string
     */
    protected function _getInstallmentPlanHtml($aInstallments)
    {
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('payone_core_payolution_installmentplan');
        $layout->generateXml();
        $layout->generateBlocks();
        
        foreach ($layout->getAllBlocks() as $blockName => $oBlock) {
            if($oBlock instanceof Payone_Core_Block_Checkout_Onepage_Payolution_Installmentplan) {
                $oBlock->setInstallmentData($aInstallments);
                $oBlock->setCode($this->_getCode());
                $oBlock->setPaymentConfigId($this->_getPaymentConfigId());
            }
        }
        
        $output = $layout->getOutput();
        return $output;
    }
    
    protected function _getCode()
    {
        $aPost = $this->getRequest()->getPost();
        return $aPost['code'];
    }
    
    protected function _getPaymentConfigId()
    {
        $aPost = $this->getRequest()->getPost();
        return $aPost['payone_config_payment_method_id'];        
    }
    
    public function handlePayolutionInstallmentAction()
    {
        if ($this->_expireAjax()) {
            return;
        }

        $aInstallments = false;
        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }

            $this->_init();
            
            $blSuccess = $this->_handlePayolutionPreCheck();
            if($blSuccess) {
                $aInstallments = $this->_handlePayolutionCalculation();
                
                if(!$aInstallments || count($aInstallments) == 0) {
                    $blSuccess = false;
                    $aInstallments = false;
                }
            }
        } catch(Exception $oEx) {
        }

        $aReturn = array(
            'success' => $blSuccess, 
            'goto_section' => 'payment',
            'code' => $this->_getCode(),
            'update_section' => array(
                'name' => 'payment-method',
                'html' => $this->_getInstallmentPlanHtml($aInstallments),
            ),
        );

        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($aReturn)
        );
    }
    
    /**
     * @param Payone_Core_Model_Config_Payment_Method_Interface $paymentConfig
     */
    public function setPaymentConfig($paymentConfig)
    {
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    public function getPaymentConfig()
    {
        return $this->paymentConfig;
    }
    
    /**
     *
     * @return Payone_Core_Model_Factory
     */
    public function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = new Payone_Core_Model_Factory();
        }

        return $this->factory;
    }
    
    /**
     * @return Payone_Core_Helper_Config
     */
    protected function helperConfig()
    {
        if ($this->helperConfig === null) {
            $this->helperConfig = $this->getFactory()->helperConfig();
        }

        return $this->helperConfig;
    }

}