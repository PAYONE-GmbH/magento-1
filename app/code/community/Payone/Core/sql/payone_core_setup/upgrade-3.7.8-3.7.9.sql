#----------------------------------------------------------------------------------------------------
# Alter table sales_flat_order, add column for a flag to conditionally prevent the confirmation mail
#----------------------------------------------------------------------------------------------------

ALTER TABLE `{{sales_flat_order}}`
  ADD `payone_prevent_confirmation` TINYINT(1) UNSIGNED NULL COMMENT 'Flag to prevent confirmation mail';
