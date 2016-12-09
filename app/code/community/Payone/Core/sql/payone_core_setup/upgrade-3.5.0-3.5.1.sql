#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, change engine to InnoDB
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}` ENGINE = InnoDB;

#-----------------------------------------------------------------
#-- Alter Table payone_customer, change engine to InnoDB
#-----------------------------------------------------------------

ALTER TABLE `{{payone_customer}}` ENGINE = InnoDB;

#-----------------------------------------------------------------
#-- Alter Table payone_protocol_api, change engine to InnoDB
#-----------------------------------------------------------------

ALTER TABLE `{{payone_protocol_api}}` ENGINE = InnoDB;

#-----------------------------------------------------------------
#-- Alter Table payone_protocol_transactionstatus, change engine to InnoDB
#-----------------------------------------------------------------

ALTER TABLE `{{payone_protocol_transactionstatus}}` ENGINE = InnoDB;

#-----------------------------------------------------------------
#-- Alter Table payone_transaction, change engine to InnoDB
#-----------------------------------------------------------------

ALTER TABLE `{{payone_transaction}}` ENGINE = InnoDB;