#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add klarna_config
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `klarna_campaign_code` VARCHAR(50) COMMENT 'Klarna Campaign Code' AFTER `klarna_config`;