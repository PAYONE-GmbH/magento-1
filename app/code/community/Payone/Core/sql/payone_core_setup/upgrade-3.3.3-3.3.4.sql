#-----------------------------------------------------------------
#-- Alter Table payone_protocol_transactionstatus, add raw_request
#-----------------------------------------------------------------

ALTER TABLE `{{payone_protocol_transactionstatus}}` ADD `raw_request` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL 