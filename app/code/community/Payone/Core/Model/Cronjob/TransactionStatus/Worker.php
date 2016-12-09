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
 * @package         Payone_Core_Model
 * @subpackage      Cronjob
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Cronjob
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Cronjob_TransactionStatus_Worker
    extends Payone_Core_Model_Cronjob_Abstract
{
    /** @var Payone_Core_Model_Service_TransactionStatus_Process */
    protected $serviceProcessRequest = null;

    /**
     * @param Mage_Cron_Model_Schedule $schedule
     * @return void
     */
    public function execute(Mage_Cron_Model_Schedule $schedule)
    {
        $service = $this->getFactory()->getServiceTransactionStatusExecute();

        $count = $service->executePending();

        $schedule->setMessages($count . ' Transaction-Status processed');
    }

}