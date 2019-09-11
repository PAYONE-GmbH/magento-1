#--------------------------------------------------------------------------------------------------------------
# Alter tables payone_config_payment_method, add columns for shipping method (related to PaydirektExpress)
#--------------------------------------------------------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `associated_shipping_method` VARCHAR(50) NULL COMMENT 'associated_shipping_method';