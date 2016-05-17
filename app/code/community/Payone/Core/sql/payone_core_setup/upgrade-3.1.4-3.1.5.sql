
#-----------------------------------------------------------------
#-- Alter Table payone_transaction
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
CHANGE `key` `key` varchar(255) DEFAULT NULL COMMENT 'Key' ;