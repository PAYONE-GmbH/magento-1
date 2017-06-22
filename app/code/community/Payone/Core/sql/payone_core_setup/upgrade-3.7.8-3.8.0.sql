#-----------------------------------------------------------------------
#-- Alter table payone_config_payment_method, add columns for Amazon Pay
#-----------------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}` ADD `request_type_amazon` VARCHAR(50) NULL COMMENT 'request_type_amazon';
ALTER TABLE `{{payone_config_payment_method}}` ADD `amz_client_id` VARCHAR(255) NULL COMMENT 'amz_client_id';
ALTER TABLE `{{payone_config_payment_method}}` ADD `amz_seller_id` VARCHAR(255) NULL COMMENT 'amz_seller_id';
ALTER TABLE `{{payone_config_payment_method}}` ADD `amz_button_type` INT NULL COMMENT 'amz_button_type';
ALTER TABLE `{{payone_config_payment_method}}` ADD `amz_button_color` INT NULL COMMENT 'amz_button_color';
ALTER TABLE `{{payone_config_payment_method}}` ADD `amz_button_lang` INT NULL COMMENT 'amz_button_lang';
ALTER TABLE `{{payone_config_payment_method}}` ADD `amz_sync_mode` INT NULL COMMENT 'amz_sync_mode';
