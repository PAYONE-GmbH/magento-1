#-----------------------------------------------------------------
#-- delete old payolution vales from payone_config_payment_method
#-----------------------------------------------------------------


UPDATE  `{{payone_config_payment_method}}` SET is_deleted = 1 WHERE code = 'payolution';