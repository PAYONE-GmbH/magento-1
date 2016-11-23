#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add narrative_text
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `narrative_text` VARCHAR(255) DEFAULT NULL COMMENT 'narrative_text';