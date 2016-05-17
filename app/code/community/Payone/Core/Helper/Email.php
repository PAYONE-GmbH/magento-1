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
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Helper
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Helper_Email extends Payone_Core_Helper_Abstract
{
    /**
     * @var Mage_Core_Model_Email_Template
     */
    protected $emailTemplate;

    /** @var mixed */
    protected $storeId = null;

    /**
     * @param string $errorName
     * @param string $errorMessage
     * @param string $stacktrace
     * @param array $additional
     * @return bool
     */
    public function sendEmailError($errorName, $errorMessage, $stacktrace = '', $additional = array())
    {
        $parameters = array(
            'error_name' => $errorName,
            'error_message' => $errorMessage,
            'error_stacktrace' => $stacktrace,
        );
        if (count($additional)) {
            $parameters = array_merge($additional, $parameters);
        }

        $storeId = $this->getStoreId();
        $config = $this->helperConfig()->getConfigMisc($storeId)->getEmailError();

        return $this->send($config, $parameters);
    }

    /**
     *
     * @param Payone_Core_Model_Config_Misc_Email_Interface $config
     * @param array $parameters
     * @return bool
     */
    public function send(Payone_Core_Model_Config_Misc_Email_Interface $config, array $parameters = array())
    {
        if (!$config->isEnabled()) {
            return false;
        }
        if ($config->getFrom() == '' or $config->getTo() == '') {
            return false;
        }

        /**
         * @var $emailTemplate Mage_Core_Model_Email_Template
         */
        $emailTemplate = $this->getEmailTemplate();

        // From
        $identFrom = $this->getTransEmailIdentity($config->getFrom());
        $emailTemplate->setSenderName($identFrom['name']);
        $emailTemplate->setSenderEmail($identFrom['email']);

        // To
        $identTo = $this->getTransEmailIdentity($config->getTo());
        $names = array(
            $identTo['name']
        );
        $emails = array(
            $identTo['email']
        );

        // Bcc
        $bcc = $config->getBcc();
        if ($bcc != '') {
            $bccArray = explode(',', $bcc);

            foreach ($bccArray as $key => $bccEmail) {
                if ($bccEmail == '') {
                    continue;
                }
                array_push($emails, $bccEmail);
            }
        }

        // Template
        $template = $config->getTemplate();
        $emailTemplate->loadDefault($template);

        // Send Mail
        return $emailTemplate->send($emails, $names, $parameters);
    }

    /**
     * @param $identity
     * @return array
     */
    protected function getTransEmailIdentity($identity)
    {
        $storeId = $this->getStoreId();

        $name = $this->helperConfig()->getStoreConfig('trans_email/ident_' . $identity . '/name', $storeId);
        $email = $this->helperConfig()->getStoreConfig('trans_email/ident_' . $identity . '/email', $storeId);

        return array(
            'name' => $name,
            'email' => $email,
        );
    }

    /**
     * @param Mage_Core_Model_Email_Template $emailTemplate
     */
    public function setEmailTemplate(Mage_Core_Model_Email_Template $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * @return Mage_Core_Model_Email_Template
     */
    public function getEmailTemplate()
    {
        if($this->emailTemplate === null){
            $this->emailTemplate = $this->getFactory()->getModelEmailTemplate();
        }
        return $this->emailTemplate;
    }

    /**
     * @param mixed $storeId
     */
    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->storeId;
    }
}