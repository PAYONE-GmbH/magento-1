#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_address - add payone_payment_fee
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_address}}`
ADD `payone_payment_fee` FLOAT NULL;