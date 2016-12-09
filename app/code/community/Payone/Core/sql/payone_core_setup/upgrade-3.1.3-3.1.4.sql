
#-----------------------------------------------------------------
#-- Alter Table payone_transaction
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
CHANGE `bankaccountcheck_type` `bankaccountcheck_type` varchar(2) DEFAULT NULL ;

UPDATE `{{payone_config_payment_method}}` SET `bankaccountcheck_type` = NULL
WHERE `bankaccountcheck_type` = '' AND `code` = 'debit_payment';
