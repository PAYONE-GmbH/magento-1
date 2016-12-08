#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_wallet_type` VARCHAR(3) NOT NULL DEFAULT '',
ADD `payone_ratepay_type` VARCHAR(3) NOT NULL DEFAULT '';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_wallet_type` VARCHAR(3) NOT NULL DEFAULT '',
ADD `payone_ratepay_type` VARCHAR(3) NOT NULL DEFAULT '';