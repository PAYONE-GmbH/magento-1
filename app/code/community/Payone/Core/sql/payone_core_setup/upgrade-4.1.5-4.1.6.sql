#--------------------------------------------------------------------------------------------------------------
# Alter table payone_transaction, add columns for workorder id and reservation txid
#--------------------------------------------------------------------------------------------------------------

ALTER TABLE `{{payone_transaction}}`
  ADD `workorderid` VARCHAR(64) NULL COMMENT 'workorder id';
ALTER TABLE `{{payone_transaction}}`
  ADD `reservation_txid` VARCHAR(64) NULL COMMENT 'reservation txid';
