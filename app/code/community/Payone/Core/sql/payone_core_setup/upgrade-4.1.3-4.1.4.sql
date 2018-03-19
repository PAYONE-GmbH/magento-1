
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method
#-----------------------------------------------------------------
ALTER TABLE `{{payone_config_payment_method}}`
  ADD `cc_type_auto_recognition` INT(1) COMMENT 'Auto recognize credit card type' AFTER `customer_form_data_save`;