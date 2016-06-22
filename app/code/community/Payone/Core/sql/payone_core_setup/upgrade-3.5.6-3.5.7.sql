#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add sofortueberweisung_show_iban
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `sofortueberweisung_show_iban` INT(1) DEFAULT NULL COMMENT 'sofortueberweisung_show_iban';

ALTER TABLE `{{payone_config_payment_method}}`
ADD `sepa_request_bic` INT(1) DEFAULT NULL COMMENT 'sepa_request_bic';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment, add payone_payolution_iban, payone_payolution_bic
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_payolution_bic` VARCHAR(11) COMMENT 'PAYOLUTION BIC' AFTER `payone_sepa_bic`;

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_payolution_iban` VARCHAR(34) COMMENT 'PAYOLUTION IBAN' AFTER `payone_sepa_bic`;

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment, add payone_payolution_iban, payone_payolution_bic
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_payolution_bic` VARCHAR(11) COMMENT 'PAYOLUTION BIC' AFTER `payone_sepa_bic`;

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_payolution_iban` VARCHAR(34) COMMENT 'PAYOLUTION IBAN' AFTER `payone_sepa_bic`;