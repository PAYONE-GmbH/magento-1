
#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_financing_type` VARCHAR(3) NOT NULL DEFAULT '' COMMENT 'Financing: Type',
ADD `payone_safe_invoice_type` VARCHAR(3) NOT NULL DEFAULT '' COMMENT 'Safe Invoice: Type',
ADD `payone_clearing_legalnote` VARCHAR(500) NOT NULL DEFAULT '' COMMENT 'Legal note | Hinweistext zur Forderungsabtretung',
ADD `payone_clearing_duedate` VARCHAR(8) NOT NULL DEFAULT '' COMMENT 'Due date | Zahlungsziel | YYYYMMDD',
ADD `payone_clearing_reference` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Reason for payment | Verwendungszweck',
ADD `payone_clearing_instructionnote` VARCHAR(200) NOT NULL DEFAULT '' COMMENT 'Help text for payment clearing | Hinweistext zur Zahlungsabwicklung';


#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_financing_type` VARCHAR(3) NOT NULL DEFAULT '' COMMENT 'Financing: Type',
ADD `payone_safe_invoice_type` VARCHAR(3) NOT NULL DEFAULT '' COMMENT 'Safe Invoice: Type';


#-----------------------------------------------------------------
#-- Alter Table payone_protocol_transactionstatus, add clearing parameter columns
#-----------------------------------------------------------------

ALTER TABLE `{{payone_protocol_transactionstatus}}`
ADD `clearing_bankaccountholder` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Accountholder',
ADD `clearing_bankcountry` VARCHAR(2) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Country',
ADD `clearing_bankaccount` VARCHAR(14) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Account',
ADD `clearing_bankcode` INT(11) NOT NULL DEFAULT 0 COMMENT 'Recipient Bank Code',
ADD `clearing_bankiban` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank IBAN',
ADD `clearing_bankbic` VARCHAR(11) NOT NULL DEFAULT '' COMMENT 'Recipient Bank BIC',
ADD `clearing_bankcity` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank City',
ADD `clearing_bankname` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Name',
ADD `clearing_legalnote` VARCHAR(500) NOT NULL DEFAULT '' COMMENT 'Legal note | Hinweistext zur Forderungsabtretung',
ADD `clearing_duedate` VARCHAR(8) NOT NULL DEFAULT '' COMMENT 'Due date | Zahlungsziel | YYYYMMDD',
ADD `clearing_reference` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Reason for payment | Verwendungszweck',
ADD `clearing_instructionnote` VARCHAR(200) NOT NULL DEFAULT '' COMMENT 'Help text for payment clearing | Hinweistext zur Zahlungsabwicklung';