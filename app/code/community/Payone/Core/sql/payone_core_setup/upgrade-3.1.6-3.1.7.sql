
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add klarna_config
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `klarna_config` TEXT COMMENT 'Klarna Config' AFTER `types`;

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_customer_dob` DATETIME COMMENT 'Customer Date Of Birth',
ADD `payone_customer_gender` INT(11) COMMENT 'Customer Gender',
ADD `payone_customer_personalid` VARCHAR(255) COMMENT 'Customer Personalid',
ADD `payone_billing_addressaddition` VARCHAR(255) COMMENT 'Billing Address Addition',
ADD `payone_shipping_addressaddition` VARCHAR(255) COMMENT 'Shipping Address Addition',
ADD `payone_customer_telephone` VARCHAR(255) COMMENT 'Customer Telephone';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_customer_dob` DATETIME COMMENT 'Customer Date Of Birth',
ADD `payone_customer_gender` INT(11) COMMENT 'Customer Gender',
ADD `payone_customer_personalid` VARCHAR(255) COMMENT 'Customer Personalid',
ADD `payone_billing_addressaddition` VARCHAR(255) COMMENT 'Billing Address Addition',
ADD `payone_shipping_addressaddition` VARCHAR(255) COMMENT 'Shipping Address Addition',
ADD `payone_customer_telephone` VARCHAR(255) COMMENT 'Customer Telephone';