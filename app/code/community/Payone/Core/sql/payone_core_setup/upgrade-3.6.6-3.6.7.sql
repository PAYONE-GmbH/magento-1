#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add company_name, b2b_mode
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `installment_draft_user` VARCHAR(255) DEFAULT NULL COMMENT 'installment_draft_user';

ALTER TABLE `{{payone_config_payment_method}}`
ADD `installment_draft_password` VARCHAR(255) DEFAULT NULL COMMENT 'installment_draft_password';


#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment, add payone_payolution_installment_duration
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_payolution_installment_duration` VARCHAR(4) COMMENT 'Installment duration' AFTER `payone_payolution_type`;

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment, add payone_payolution_installment_duration
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_payolution_installment_duration` VARCHAR(4) COMMENT 'Installment duration' AFTER `payone_payolution_type`;

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order, add payone_payment_fee
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order}}`
ADD `payone_payment_fee` FLOAT NULL;