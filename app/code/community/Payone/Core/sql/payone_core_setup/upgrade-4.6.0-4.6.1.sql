#---------------------------------------------------------------------------------------------
#-- Change the type of column 'valid_payment_firstdays' from INT to VARCHAR
#-- because the value can actually be a string format ("2,28")
#---------------------------------------------------------------------------------------------

ALTER TABLE `{{payone_ratepay_config}}` MODIFY COLUMN `valid_payment_firstdays` VARCHAR(10);
