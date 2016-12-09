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
 * @package         js
 * @subpackage      payone
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

PAYONE = {};
PAYONE.Migrator = {};

/**
 *
 * @param url
 * @constructor
 */
PAYONE.Migrator.Migration = function (url) {
    this.url = url;
    this.currentStep = '';

    this.nextStep = function (step) {
        this.currentStep = step;

        this.markStepLoading(step);

        var request = new Ajax.Request(
            this.url,
            {
                method:'post',
                onSuccess:migration.onSuccess,
                onFailure:migration.onFailure,
                parameters:{
                    'step':step
                }
            }
        );
    };

    this.onSuccess = function (responseRaw) {
        var response = (responseRaw.responseJSON);
        if (response.success == true) {
            migration.markStepSuccess(migration.currentStep);
            if (response.next_step !== 0) {
                migration.nextStep(response.next_step);
            }
            else {
                $('payone_wizard_form_buttons').setStyle({display:'block'});
            }
        }
        else {
            migration.markStepFailure(migration.currentStep);
        }
    };

    this.markStepLoading = function (step) {
        var stepHtmlId = 'payone_migration_' + step + '_status';
        $(stepHtmlId).removeClassName('hidden');
        $(stepHtmlId).addClassName('payone_migration_loading');
    };

    this.markStepSuccess = function (step) {
        var stepHtmlId = 'payone_migration_' + step + '_status';
        $(stepHtmlId).removeClassName('hidden');
        $(stepHtmlId).removeClassName('payone_migration_loading');
        $(stepHtmlId).addClassName('payone_migration_success');
    };

    this.markStepFailure = function (step) {
        var stepHtmlId = 'payone_migration_' + step + '_status';
        $(stepHtmlId).removeClassName('hidden');
        $(stepHtmlId).removeClassName('payone_migration_loading');
        $(stepHtmlId).addClassName('payone_migration_failure');
    };

};

