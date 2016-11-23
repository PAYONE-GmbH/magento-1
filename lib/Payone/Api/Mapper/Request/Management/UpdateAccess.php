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
class Payone_Api_Mapper_Request_Management_UpdateAccess
    extends Payone_Api_Mapper_Request_Abstract
    implements Payone_Api_Mapper_Request_Interface
{
    public function map(Payone_Api_Request_Interface $request)
    {
        /** @var $request Payone_Api_Request_UpdateAccess */
        $this->mapAmount($request);
        $this->mapInvoicingData($request);

        return $request->toArray();
    }

    /**
     * @param Payone_Api_Request_Interface $request
     * @return bool
     */
    protected function mapAmount(Payone_Api_Request_Interface $request)
    {
        /** @var $request Payone_Api_Request_UpdateAccess  */
        $currency = $request->getCurrency();

        $amountTrail = $request->getAmountTrail();
        $mappedAmountTrail = $this->getMapperCurrency()->mapAmountToSub($amountTrail, $currency);
        $request->setAmountTrail($mappedAmountTrail);

        $amountRecurring = $request->getAmountRecurring();
        $mappedAmountRecurring = $this->getMapperCurrency()->mapAmountToSub($amountRecurring, $currency);
        $request->setAmountRecurring($mappedAmountRecurring);

        return true;
    }

    /**
     * @param Payone_Api_Request_Interface $request
     * @return bool
     */
    protected function mapInvoicingData(Payone_Api_Request_Interface $request)
    {
        /** @var $invoicing Payone_Api_Request_Parameter_Invoicing_Transaction */
        /** @var $request Payone_Api_Request_UpdateAccess */
        $invoicing = $request->getInvoicing();
        if ($invoicing == null) {
            return false;
        }

        if ($invoicing->hasItems()) {
            $currency = $request->getCurrency();
            $invoicingItems = $invoicing->getItems();
            foreach ($invoicingItems as $item) {
                /** @var $item Payone_Api_Request_Parameter_Invoicing_Access_Item */
                $priceTrail = $item->getPrTrail();
                $mappedPriceTrail = $this->getMapperCurrency()->mapAmountToSub($priceTrail, $currency);
                $item->setPrTrail($mappedPriceTrail);

                $priceRecurring = $item->getPrRecurring();
                $mappedPriceRecurring = $this->getMapperCurrency()->mapAmountToSub($priceRecurring, $currency);
                $item->setPrRecurring($mappedPriceRecurring);
            }
        }

        return true;
    }

}
