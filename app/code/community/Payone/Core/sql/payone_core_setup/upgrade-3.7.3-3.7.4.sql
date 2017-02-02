#-----------------------------------------------------------------
#-- DELETE old payoltion vales from payone_config_payment_method
#-----------------------------------------------------------------


DELETE FROM `payone_config_payment_method` WHERE code = 'payolution';