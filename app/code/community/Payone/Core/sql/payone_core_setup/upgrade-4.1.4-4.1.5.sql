#--------------------------------------------------------------------------------------------------------------
# Alter table sales_flat_order, add column for a flag to conditionally prevent the confirmation mail
#--------------------------------------------------------------------------------------------------------------

ALTER TABLE `{{sales_flat_order}}`
  ADD `payone_cancel_substitute_increment_id` VARCHAR(50) NULL COMMENT 'Increment ID of the cancled order which this is the substitute for' AFTER `payone_prevent_confirmation`;
