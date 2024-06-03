#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method
#-----------------------------------------------------------------
ALTER TABLE `{{payone_config_payment_method}}`
 ADD `wallet_paydirekt_enable_secured_preorder` INT(1) COMMENT 'Secured pre-order enabled';

ALTER TABLE `{{payone_config_payment_method}}`
 ADD `wallet_paydirekt_secured_preorder_guarantee_period` INT(5) COMMENT 'Guarantee period for secured pre-order';