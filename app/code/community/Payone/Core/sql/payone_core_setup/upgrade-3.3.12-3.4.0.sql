
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}` 
CHANGE `check_cvc` `check_cvc` VARCHAR( 40 ) NULL DEFAULT NULL COMMENT 'no, only_first or always';