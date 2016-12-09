
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add sepa specific fields
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `sepa_country` TEXT COMMENT 'SEPA Country' AFTER `message_response_blocked`;

ALTER TABLE `{{payone_config_payment_method}}`
ADD `sepa_de_show_bank_data` INT(1) COMMENT 'SEPA Germany Show Bank Data' AFTER `sepa_country`;

ALTER TABLE `{{payone_config_payment_method}}`
ADD `sepa_mandate_enabled` INT(1) COMMENT 'SEPA Mandate Enabled' AFTER `sepa_de_show_bank_data`;

ALTER TABLE `{{payone_config_payment_method}}`
ADD `sepa_mandate_download_enabled` INT(1) COMMENT 'SEPA Mandate Download Enabled' AFTER `sepa_mandate_enabled`;

-- Update table sales_flat_order_payment
ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_sepa_bic` VARCHAR(11) COMMENT 'SEPA BIC' AFTER `payone_bank_group`;

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_sepa_iban` VARCHAR(34) COMMENT 'SEPA IBAN' AFTER `payone_bank_group`;

-- Update table sales_flat_quote_payment
ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_sepa_bic` VARCHAR(11) COMMENT 'SEPA BIC' AFTER `payone_bank_group`;

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_sepa_iban` VARCHAR(34) COMMENT 'SEPA IBAN' AFTER `payone_bank_group`;