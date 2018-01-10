#-----------------------------------------------------------------
#-- Create Table payone_payment_ban
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_payment_ban}}` ;
CREATE TABLE `{{payone_payment_ban}}` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `customer_id` INT NULL,
 `payment_method` VARCHAR(50) NULL,
 `from_date` DATETIME NULL,
 `to_date` DATETIME NULL,
 PRIMARY KEY (`id`),
 UNIQUE INDEX `UNQ_PAYONE_CUSTOMER_CUSTOMER_ID_PAYMENT_METHOD` (`customer_id` ASC, `payment_method` ASC)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment
#-----------------------------------------------------------------
ALTER TABLE `{{sales_flat_order_payment}}`
 ADD `payone_vat_id` VARCHAR(64) NOT NULL DEFAULT '';