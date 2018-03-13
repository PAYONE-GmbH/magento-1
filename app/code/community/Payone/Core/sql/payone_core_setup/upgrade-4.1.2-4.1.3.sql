#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method
#-----------------------------------------------------------------
ALTER TABLE `{{payone_config_payment_method}}`
 ADD `wallet_paydirekt_enable_overcapture` INT(1) COMMENT 'Overcapture enabled';