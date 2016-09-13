#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment, add payone_cardexpiredate
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_cardexpiredate` VARCHAR(4) COMMENT 'Creditcard cardexpiredate' AFTER `payone_pseudocardpan`;

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment, add payone_cardexpiredate
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_cardexpiredate` VARCHAR(4) COMMENT 'Creditcard cardexpiredate' AFTER `payone_pseudocardpan`;