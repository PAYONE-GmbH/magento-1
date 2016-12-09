#-----------------------------------------------------------------
#-- Alter Table payone_transaction, add frontend_api_call
#-----------------------------------------------------------------

ALTER TABLE `{{payone_transaction}}` ADD `frontend_api_call` INT( 11 ) NOT NULL DEFAULT '0'