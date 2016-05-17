#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add paypal_express_visible_on_cart, paypal_express_address, paypal_express_image
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `paypal_express_visible_on_cart` INT(1) COMMENT 'Paypal Express Visible on Cart' AFTER `klarna_campaign_code`;

ALTER TABLE `{{payone_config_payment_method}}`
ADD `paypal_express_address` INT(1) COMMENT 'Paypal Express Address' AFTER `paypal_express_visible_on_cart`;

ALTER TABLE `{{payone_config_payment_method}}`
ADD `paypal_express_image` VARCHAR(250) COMMENT 'Paypal Express Image' AFTER `paypal_express_address`;