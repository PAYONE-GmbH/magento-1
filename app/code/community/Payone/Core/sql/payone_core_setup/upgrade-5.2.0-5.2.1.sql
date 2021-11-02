
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add sepa specific fields
#-----------------------------------------------------------------


ALTER TABLE `{{payone_config_payment_method}}`
  ADD `apl_merchant_id` VARCHAR(150) COMMENT 'Merchant ID for ApplePay';

ALTER TABLE `{{payone_config_payment_method}}`
ADD `apl_merchant_identification_certificate` VARCHAR(150) COMMENT 'Merchant certificate for ApplePay' AFTER 'apl_merchant_id';

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `apl_certificate_private_key` VARCHAR(150) COMMENT 'Certificate private key for ApplePay' AFTER 'apl_merchant_identification_certificate';

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `apl_certificate_key_password` VARCHAR(150) COMMENT 'Certificate Key password for ApplePay' AFTER 'apl_certificate_private_key';
