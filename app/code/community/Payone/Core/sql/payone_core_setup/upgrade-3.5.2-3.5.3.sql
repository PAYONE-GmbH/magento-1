#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add ratepay_config
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `show_customermessage` INT(1) DEFAULT NULL COMMENT 'show_customermessage';
