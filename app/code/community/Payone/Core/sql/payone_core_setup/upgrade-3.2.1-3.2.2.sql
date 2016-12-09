#-----------------------------------------------------------------
#-- Create Table payone_customer
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_customer}}` ;
CREATE TABLE `{{payone_customer}}` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_id` INT NULL,
  `code` VARCHAR(50) NULL,
  `customer_data` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UNQ_PAYONE_CUSTOMER_CUSTOMER_ID_CODE` (`customer_id` ASC, `code` ASC)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add sepa specific fields
#-----------------------------------------------------------------
ALTER TABLE `{{payone_config_payment_method}}`
ADD `customer_form_data_save` INT(1) COMMENT 'Save payment data for logged in customer' AFTER `sepa_mandate_download_enabled`;