
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method
#-----------------------------------------------------------------
ALTER TABLE `{{payone_config_payment_method}}`
  ADD `cc_type_auto_recognition` INT(1) COMMENT 'Auto recognize credit card type' AFTER `customer_form_data_save`;

#-----------------------------------------------------------------
#-- Create Table payone_config_logos
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_config_logos}}` ;
CREATE TABLE `{{payone_config_logos}}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(50) NULL,
  `size` VARCHAR(3) NOT NULL DEFAULT 'm',
  `type` VARCHAR(50) NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `enabled` BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;