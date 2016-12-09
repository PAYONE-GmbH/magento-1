
#-----------------------------------------------------------------
#-- Alter Table payone_transaction
#-----------------------------------------------------------------

ALTER TABLE `{{payone_transaction}}`
CHANGE `customerid` `customerid` varchar(40) NOT NULL DEFAULT '' COMMENT 'Customerid';

#-----------------------------------------------------------------
#-- Alter Table payone_protocol_transactionstatus
#-----------------------------------------------------------------

ALTER TABLE `{{payone_protocol_transactionstatus}}`
CHANGE `customerid` `customerid` varchar(40) NOT NULL DEFAULT '' COMMENT 'Customerid';
