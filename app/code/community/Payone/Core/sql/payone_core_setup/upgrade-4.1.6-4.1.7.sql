
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `ratepay_debit_type` TEXT COMMENT 'installment debit type' AFTER `ratepay_config`;

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `ratepay_directdebit_allowspecific` INT(1) COMMENT 'Allow specific countries for installment direct debit' AFTER `ratepay_debit_type`;

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `ratepay_directdebit_specificcountry` TEXT COMMENT 'List of countries for installment direct debit' AFTER `ratepay_directdebit_allowspecific`;
