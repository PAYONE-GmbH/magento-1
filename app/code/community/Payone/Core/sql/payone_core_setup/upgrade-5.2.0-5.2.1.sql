
#-----------------------------------------------------------------
#-- Alter Table payone_config_payment_method, add sepa specific fields
#-----------------------------------------------------------------

ALTER TABLE `{{payone_config_payment_method}}`
ADD `apl_merchant_identification_certificate` TEXT COMMENT 'Merchant certificate for ApplePay';

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `apl_certificate_private_key` TEXT COMMENT 'Certificate private key for ApplePay' AFTER 'apl_merchant_identification_certificate';

ALTER TABLE `{{payone_config_payment_method}}`
  ADD `apl_certificate_password` TEXT COMMENT 'Certificate Key password for ApplePay' AFTER 'apl_certificate_private_key';
