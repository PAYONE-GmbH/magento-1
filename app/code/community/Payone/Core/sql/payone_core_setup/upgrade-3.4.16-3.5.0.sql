#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add ratepay_config
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `ratepay_config` TEXT COMMENT 'RatePay Config';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_ratepay_shop_id` VARCHAR(32) COMMENT 'RatePay ShopId';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_ratepay_shop_id` VARCHAR(32) COMMENT 'RatePay ShopId';

#-----------------------------------------------------------------
#-- Create Table payone_ratepay_config
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_ratepay_config}}` ;
CREATE TABLE `{{payone_ratepay_config}}`(
 `shop_id` VARCHAR(32) NOT NULL,
 `merchant_name` VARCHAR(32),
 `merchant_status` TINYINT(2),
 `shop_name` VARCHAR(32),
 `name` VARCHAR(32),
 `currency` VARCHAR(32),
 `type` VARCHAR(32),
 `activation_status_elv` TINYINT(2),
 `activation_status_installment` TINYINT(2),
 `activation_status_invoice` TINYINT(2),
 `activation_status_prepayment` TINYINT(2),
 `amount_min_longrun` DOUBLE,
 `b2b_pq_full` TINYINT(1),
 `b2b_pq_light` TINYINT(1),
 `b2b_elv` TINYINT(1),
 `b2b_installment` TINYINT(1),
 `b2b_invoice` TINYINT(1),
 `b2b_prepayment` TINYINT(1),
 `country_code_billing` VARCHAR(32),
 `country_code_delivery` VARCHAR(32),
 `delivery_address_pq_full` TINYINT(1),
 `delivery_address_pq_light` TINYINT(1),
 `delivery_address_elv` TINYINT(1),
 `delivery_address_installment` TINYINT(1),
 `delivery_address_invoice` TINYINT(1),
 `delivery_address_prepayment` TINYINT(1),
 `device_fingerprint_snippet_id` VARCHAR(32),
 `eligibility_device_fingerprint` TINYINT(1),
 `eligibility_ratepay_elv` TINYINT(1),
 `eligibility_ratepay_installment` TINYINT(1),
 `eligibility_ratepay_invoice` TINYINT(1),
 `eligibility_ratepay_pq_full` TINYINT(1),
 `eligibility_ratepay_pq_light` TINYINT(1),
 `eligibility_ratepay_prepayment` TINYINT(1),
 `interest_rate_merchant_towards_bank` DOUBLE,
 `interestrate_default` DOUBLE,
 `interestrate_max` DOUBLE,
 `interestrate_min` DOUBLE,
 `min_difference_dueday` TINYINT(2),
 `month_allowed` VARCHAR(32),
 `month_longrun` TINYINT(2),
 `month_number_max` TINYINT(2),
 `month_number_min` TINYINT(2),
 `payment_amount` DOUBLE,
 `payment_firstday` TINYINT(2),
 `payment_lastrate` DOUBLE,
 `rate_min_longrun` DOUBLE,
 `rate_min_normal` DOUBLE,
 `service_charge` DOUBLE,
 `tx_limit_elv_max` DOUBLE,
 `tx_limit_elv_min` DOUBLE,
 `tx_limit_installment_max` DOUBLE,
 `tx_limit_installment_min` DOUBLE,
 `tx_limit_invoice_max` DOUBLE,
 `tx_limit_invoice_min` DOUBLE,
 `tx_limit_prepayment_max` DOUBLE,
 `tx_limit_prepayment_min` DOUBLE,
 `valid_payment_firstdays` TINYINT(2),
 PRIMARY KEY (`shop_id`) ); 