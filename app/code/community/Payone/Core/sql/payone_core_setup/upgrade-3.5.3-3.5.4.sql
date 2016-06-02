#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add company_name, b2b_mode
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `company_name` VARCHAR(255) DEFAULT NULL COMMENT 'company_name';

ALTER TABLE `{{payone_config_payment_method}}`
ADD `b2b_mode` INT(1) DEFAULT NULL COMMENT 'b2b_mode';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_payolution_type` VARCHAR(3) NOT NULL DEFAULT '',
ADD `payone_isb2b` TINYINT(1) NOT NULL DEFAULT 0,
ADD `payone_trade_registry_number` VARCHAR(64) NOT NULL DEFAULT '',
ADD `payone_workorder_id` VARCHAR(64) NOT NULL DEFAULT '';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_payolution_type` VARCHAR(3) NOT NULL DEFAULT '',
ADD `payone_isb2b` TINYINT(1) NOT NULL DEFAULT 0,
ADD `payone_trade_registry_number` VARCHAR(64) NOT NULL DEFAULT '',
ADD `payone_workorder_id` VARCHAR(64) NOT NULL DEFAULT '';