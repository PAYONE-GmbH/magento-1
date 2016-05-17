
#-----------------------------------------------------------------
#-- Alter Table payone_protocol_transactionstatus
#-----------------------------------------------------------------

ALTER TABLE `{{payone_protocol_transactionstatus}}` 
ADD `processing_error` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `processing_status`;