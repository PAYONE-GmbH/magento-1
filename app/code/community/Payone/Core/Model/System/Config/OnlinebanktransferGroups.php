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
 * @package         Payone_Enum
 * @copyright       Copyright (c) 2018 <kontakt@fatchip.de> - www.fatchip.de
 * @author          FATCHIP GmbH <kontakt@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.de
 */

class Payone_Core_Model_System_Config_OnlinebanktransferGroups extends Payone_Core_Model_System_Config_Abstract
{
    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            Payone_Enum_OnlineBankTransferType::EPS => array(
                'ARZ_OAB' => 'Apothekerbank',
                'ARZ_BAF' => 'Ärztebank',
                'BA_AUS' => 'Bank Austria',
                'ARZ_BCS' => 'Bankhaus Carl Spängler & Co.AG',
                'EPS_SCHEL' => 'Bankhaus Schelhammer & Schattera AG',
                'BAWAG_PSK' => 'BAWAG P.S.K. AG',
                'BAWAG_ESY' => 'Easybank AG',
                'SPARDAT_EBS' => 'Erste Bank und Sparkassen',
                'ARZ_HAA' => 'Hypo Alpe-Adria-Bank International AG',
                'ARZ_VLH' => 'Hypo Landesbank Vorarlberg',
                'HRAC_OOS' => 'HYPO Oberösterreich,Salzburg,Steiermark',
                'ARZ_HTB' => 'Hypo Tirol Bank AG',
                'ARZ_IMB' => 'Immo-Bank',
                'EPS_OBAG' => 'Oberbank AG',
                'RAC_RAC' => 'Raiffeisen Bankengruppe Österreich',
                'EPS_SCHOELLER' => 'Schoellerbank AG',
                'ARZ_OVB' => 'Volksbank Gruppe',
                'EPS_VRBB' => 'VR-Bank Braunau',
                'EPS_AAB' => 'Austrian Anadi Bank AG',
                'EPS_BKS' => 'BKS Bank AG',
                'EPS_BKB' => 'Brüll Kallmus Bank AG',
                'EPS_VLB' => 'BTV VIER LÄNDER BANK',
                'EPS_CBGG' => 'Capital Bank Grawe Gruppe AG',
                'EPS_DB' => 'Dolomitenbank',
                'EPS_NOEGB' => 'HYPO NOE Gruppe Bank AG',
                'EPS_NOELB' => 'HYPO NOE Landesbank AG',
                'EPS_HBL' => 'HYPO-BANK BURGENLAND Aktiengesellschaft',
                'EPS_MFB' => 'Marchfelder Bank',
                'EPS_SPDBW' => 'Sparda Bank Wien',
                'EPS_SPDBA' => 'SPARDA-BANK AUSTRIA',
                'EPS_VKB' => 'Volkskreditbank AG',
            ),
            Payone_Enum_OnlineBankTransferType::IDL => array(
                'ABN_AMRO_BANK' => 'ABN Amro',
                'ASN_BANK' => 'ASN Bank',
                'BUNQ_BANK' => 'Bunq',
                'ING_BANK' => 'ING Bank',
                'KNAB_BANK' => 'Knab Bank',
                'RABOBANK' => 'Rabobank',
                'REVOLUT' => 'Revolut',
                'SNS_BANK' => 'SNS Bank',
                'SNS_REGIO_BANK' => 'SNS Regio Bank',
                'TRIODOS_BANK' => 'Triodos Bank',
                'VAN_LANSCHOT_BANKIERS' => 'van Lanschot Bank',
                'YOURSAFE' => 'Yoursafe B.V',
            ),
        );
    }
}
