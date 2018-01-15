#--------------------------------------------------------------------------------------------------------------
# Alter tables payone_config_payment_method and sales_flat_order_payment, add columns for Amazon Pay
# Alter tables payone_transaction and payone_protocol_transactionstatus, add columns for the transaction status
# Alter table sales_flat_order, add column for a flag to conditionally prevent the confirmation mail
#--------------------------------------------------------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `request_type_amazon` VARCHAR(50) NULL COMMENT 'request_type_amazon',
  ADD `amz_client_id` VARCHAR(255) NULL COMMENT 'amz_client_id',
  ADD `amz_seller_id` VARCHAR(255) NULL COMMENT 'amz_seller_id',
  ADD `amz_button_type` INT NULL COMMENT 'amz_button_type',
  ADD `amz_button_color` INT NULL COMMENT 'amz_button_color',
  ADD `amz_button_lang` INT NULL COMMENT 'amz_button_lang',
  ADD `amz_sync_mode` INT NULL COMMENT 'amz_sync_mode';

ALTER TABLE `{{payone_transaction}}`
  ADD `transaction_status` VARCHAR(64) NULL COMMENT 'Transaction status',
  ADD `reasoncode` VARCHAR(64) NULL COMMENT 'Reasoncode';

ALTER TABLE `{{payone_protocol_transactionstatus}}`
  ADD `transaction_status` VARCHAR(64) NULL COMMENT 'Transaction status',
  ADD `reasoncode` VARCHAR(64) NULL COMMENT 'Reasoncode';

ALTER TABLE `{{sales_flat_order_payment}}`
  ADD `payone_amz_order_reference` VARCHAR(255) NULL COMMENT 'Amazon Order Reference';

ALTER TABLE `{{sales_flat_order}}`
  ADD `payone_prevent_confirmation` INT(1) UNSIGNED NULL COMMENT 'Flag to prevent confirmation mail';
