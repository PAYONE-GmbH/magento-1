#-----------------------------------------------------------------
#-- Create Table payone_protocol_api
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_protocol_api}}` ;
CREATE TABLE `{{payone_protocol_api}}` (
  `id`                  int(11) UNSIGNED NOT NULL auto_increment,
  `order_id`            int(11) UNSIGNED NULL COMMENT 'Order_id',
  `store_id`            int(11) UNSIGNED NOT NULL,
  `reference`           VARCHAR(20) NOT NULL DEFAULT '',
  `request`             VARCHAR(255) NOT NULL DEFAULT '',
  `response`            VARCHAR(255) NOT NULL DEFAULT '',
  `mode`                VARCHAR(16) NOT NULL DEFAULT '',
  `mid`                 BIGINT(12) UNSIGNED NOT NULL,
  `aid`                 BIGINT(12) UNSIGNED NOT NULL,
  `portalid`            BIGINT(12) UNSIGNED NOT NULL,
  `raw_request`         TEXT NOT NULL DEFAULT '',
  `raw_response`        TEXT NOT NULL DEFAULT '',
  `stacktrace`          TEXT NOT NULL DEFAULT '',
  `created_at`          datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

#-----------------------------------------------------------------
#-- Create Table payone_transaction
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_transaction}}` ;

CREATE TABLE `{{payone_transaction}}` (
  `id`                  int(11) UNSIGNED NOT NULL auto_increment,
  `store_id`            int(11) UNSIGNED NOT NULL,
  `order_id`            int(11) UNSIGNED NOT NULL,
  `txid`                BIGINT(12) NOT NULL DEFAULT 0,
  `txtime`              BIGINT(12) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Unix_Timestamp',
  `reference`           VARCHAR(20) NOT NULL DEFAULT '',
  `last_txaction`       VARCHAR(64) NOT NULL DEFAULT '',
  `last_sequencenumber` int(2) NOT NULL DEFAULT 0,
  `clearingtype`        VARCHAR(64) NOT NULL DEFAULT '',
  `mode`                VARCHAR(16) NOT NULL DEFAULT '',
  `mid`                 BIGINT(12) UNSIGNED NOT NULL,
  `aid`                 BIGINT(12) UNSIGNED NOT NULL,
  `portalid`            BIGINT(12) UNSIGNED NOT NULL,
  `productid`           int(7) UNSIGNED NOT NULL,
  `currency`            VARCHAR(3) NOT NULL DEFAULT '',
  `receivable`          FLOAT NOT NULL DEFAULT 0,
  `balance`             FLOAT NOT NULL DEFAULT 0,
  `customerid`          BIGINT(12) UNSIGNED NOT NULL,
  `userid`              int(8) UNSIGNED NOT NULL,
  `reminderlevel`       VARCHAR(2) NOT NULL DEFAULT '',
  `failedcause`         VARCHAR(4) NOT NULL DEFAULT '',
  `accessid`            BIGINT(12) UNSIGNED NOT NULL,
  `created_at`          datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at`          datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

#-----------------------------------------------------------------
#-- Create Table payone_protocol_transactionstatus
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_protocol_transactionstatus}}` ;

CREATE TABLE `{{payone_protocol_transactionstatus}}` (
  `id`                      int(11) UNSIGNED NOT NULL auto_increment,
  `store_id`                int(11) UNSIGNED DEFAULT NULL,
  `order_id`                int(11) UNSIGNED DEFAULT NULL,
  `txid`                    BIGINT(12) DEFAULT NULL,
  `txtime`                  BIGINT(12) UNSIGNED DEFAULT NULL COMMENT 'Unix_Timestamp',
  `reference`               VARCHAR(20) DEFAULT NULL,
  `key`                     VARCHAR(32) DEFAULT NULL,
  `txaction`                VARCHAR(64) DEFAULT NULL,
  `mode`                    VARCHAR(16) DEFAULT NULL,
  `mid`                     BIGINT(12) UNSIGNED DEFAULT NULL,
  `aid`                     BIGINT(12) UNSIGNED DEFAULT NULL,
  `portalid`                BIGINT(12) UNSIGNED DEFAULT NULL,
  `clearingtype`            VARCHAR(64) DEFAULT NULL,
  `sequencenumber`          int(2) DEFAULT NULL,
  `balance`                 FLOAT DEFAULT NULL,
  `receivable`              FLOAT DEFAULT NULL,
  `failedcause`             VARCHAR(4) DEFAULT NULL,
  `currency`                VARCHAR(3) DEFAULT NULL,
  `userid`                  int(8) UNSIGNED DEFAULT NULL,
  `customerid`              BIGINT(12) UNSIGNED DEFAULT NULL,
  `param`                   VARCHAR(255) DEFAULT NULL,
  `productid`               int(7) UNSIGNED DEFAULT NULL COMMENT 'Parameter Contract',
  `accessid`                BIGINT(20) UNSIGNED DEFAULT NULL COMMENT 'Parameter Contract',
  `reminderlevel`           VARCHAR(1) DEFAULT NULL COMMENT 'Parameter Collect',
  `invoiceid`               VARCHAR(20) DEFAULT NULL COMMENT 'Parameter Invoicing',
  `invoice_grossamount`     FLOAT DEFAULT NULL COMMENT 'Parameter Invoicing',
  `invoice_date`            datetime DEFAULT NULL COMMENT 'Parameter Invoicing',
  `invoice_deliverydate`    datetime DEFAULT NULL COMMENT 'Parameter Invoicing',
  `invoice_deliveryenddate` DATETIME DEFAULT NULL COMMENT 'Parameter Invoicing',
  `vaid`                    int(12) UNSIGNED DEFAULT NULL COMMENT 'Parameter Billing',
  `vreference`              VARCHAR(20) DEFAULT NULL COMMENT 'Parameter Billing',
  `vxid`                    int(12) UNSIGNED DEFAULT NULL COMMENT 'Parameter Billing',
  `processing_status`       VARCHAR(16) DEFAULT NULL,
  `processed_at`            DATETIME DEFAULT '0000-00-00 00:00:00',
  `created_at`              datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at`              datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;


#-----------------------------------------------------------------
#-- Create Table payone_config_payment_method
#-----------------------------------------------------------------
DROP TABLE IF EXISTS `{{payone_config_payment_method}}` ;

CREATE TABLE `{{payone_config_payment_method}}` (
  `id`                  INT(11) UNSIGNED NOT NULL auto_increment,
  `scope`               VARCHAR(50) NOT NULL DEFAULT '',
  `scope_id`            INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `code`                VARCHAR(50) NOT NULL DEFAULT '',
  `name`                VARCHAR(255) DEFAULT NULL,
  `sort_order`          INT(11) DEFAULT NULL,
  `enabled`             INT(1) DEFAULT NULL,
  `fee_config`          TEXT,
  `mode`                VARCHAR(16) DEFAULT NULL,
  `use_global`          INT(1) DEFAULT NULL,
  `mid`                 BIGINT(12) UNSIGNED DEFAULT NULL,
  `aid`                 BIGINT(12) UNSIGNED DEFAULT NULL,
  `portalid`            BIGINT(12) UNSIGNED DEFAULT NULL,
  `key`                 VARCHAR(32) DEFAULT NULL,
  `request_type`        VARCHAR(50) DEFAULT NULL,
  `allowspecific`       INT(1) DEFAULT NULL,
  `specificcountry`     TEXT,
  `invoice_transmit`    INT(1) DEFAULT NULL,
  `types`               TEXT,
  `check_cvc`           INT(1) DEFAULT NULL,
  `check_bankaccount`   INT(1) DEFAULT NULL,
  `bankaccountcheck_type` VARCHAR(2) DEFAULT '',
  `message_response_blocked` VARCHAR(1024) DEFAULT '',
  `min_order_total`     FLOAT DEFAULT NULL,
  `max_order_total`     FLOAT DEFAULT NULL,
  `parent_default_id`   INT(11) UNSIGNED DEFAULT NULL,
  `parent_websites_id`  INT(11) UNSIGNED DEFAULT NULL,
  `is_deleted`          INT(1) NOT NULL DEFAULT 0,
  `created_at`          datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at`          datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;


#-----------------------------------------------------------------
#-- Alter Table sales_flat_order
#-----------------------------------------------------------------

ALTER TABLE  `{{sales_flat_order}}`
ADD `payone_transaction_status`        VARCHAR(16) NOT NULL DEFAULT '',
ADD `payone_dunning_status`            VARCHAR(16) NOT NULL DEFAULT '',
ADD `payone_payment_method_type`       VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Method Type that was used. Only filled for CreditCard and OnlineBankTransfer';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_grid
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_grid}}`
ADD `payone_transaction_status`        VARCHAR(16) NOT NULL DEFAULT '',
ADD `payone_dunning_status`            VARCHAR(16) NOT NULL DEFAULT '',
ADD `payone_payment_method`            VARCHAR(255) NOT NULL DEFAULT '',
ADD `payone_payment_method_type`       VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Method Type that was used. Only filled for CreditCard and OnlineBankTransfer',
ADD INDEX ( `payone_payment_method` );

#-----------------------------------------------------------------
#-- Alter Table sales_flat_order_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_order_payment}}`
ADD `payone_config_payment_method_id` INT(11) NOT NULL DEFAULT 0,
ADD `payone_payment_method_type`      VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Method Type that was used. Only filled for CreditCard and OnlineBankTransfer',
ADD `payone_payment_method_name`      VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Config-Name the Customer Provided when the order was placed',
ADD `payone_onlinebanktransfer_type`        VARCHAR(3) NOT NULL DEFAULT '' COMMENT 'OnlineBankTransfer : Type',
ADD `payone_bank_country`        VARCHAR(2) NOT NULL DEFAULT '',
ADD `payone_account_number`            VARCHAR(14) NOT NULL DEFAULT '',
ADD `payone_account_owner`            VARCHAR(50) NOT NULL DEFAULT '',
ADD `payone_bank_code`        INT(8) NOT NULL DEFAULT 0,
ADD `payone_bank_group`        VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'OnlineBankTransfer : Bank Group Type',
ADD `payone_pseudocardpan` VARCHAR(19) NOT NULL DEFAULT '' COMMENT 'Pseudo Card PAN',
ADD `payone_clearing_bank_accountholder` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Accountholder',
ADD `payone_clearing_bank_country` VARCHAR(2) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Country',
ADD `payone_clearing_bank_account` VARCHAR(14) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Account',
ADD `payone_clearing_bank_code` INT(11) NOT NULL DEFAULT 0 COMMENT 'Recipient Bank Code',
ADD `payone_clearing_bank_iban` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank IBAN',
ADD `payone_clearing_bank_bic` VARCHAR(11) NOT NULL DEFAULT '' COMMENT 'Recipient Bank BIC',
ADD `payone_clearing_bank_city` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank City',
ADD `payone_clearing_bank_name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Recipient Bank Name';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_payment
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_payment}}`
ADD `payone_config_payment_method_id` INT(11) NOT NULL DEFAULT 0,
ADD `payone_onlinebanktransfer_type`        VARCHAR(3) NOT NULL DEFAULT '' COMMENT 'OnlineBankTransfer : Type',
ADD `payone_bank_country`        VARCHAR(2) NOT NULL DEFAULT '',
ADD `payone_account_number`            VARCHAR(14) NOT NULL DEFAULT '',
ADD `payone_account_owner`            VARCHAR(50) NOT NULL DEFAULT '',
ADD `payone_bank_code`        INT(8) NOT NULL DEFAULT 0,
ADD `payone_bank_group`        VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'Bank Group Type',
ADD `payone_pseudocardpan` VARCHAR(19) NOT NULL DEFAULT '' COMMENT 'Pseudo Card PAN' ;

#-----------------------------------------------------------------
#-- Alter Table sales_flat_quote_address
#-----------------------------------------------------------------

ALTER TABLE `{{sales_flat_quote_address}}`
ADD `payone_addresscheck_score` VARCHAR(1) NOT NULL DEFAULT '' COMMENT 'AddressCheck Person Status Score (G, Y, R)',
ADD `payone_addresscheck_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Addresscheck Date',
ADD `payone_addresscheck_hash` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'Addresscheck Hash',
ADD `payone_protect_score` VARCHAR(1) NOT NULL DEFAULT '' COMMENT 'Creditrating Score (G, Y, R)',
ADD `payone_protect_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Creditrating Date',
ADD `payone_protect_hash` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'Creditrating address Hash';

#-----------------------------------------------------------------
#-- Alter Table sales_flat_invoice
#-----------------------------------------------------------------

ALTER TABLE  `{{sales_flat_invoice}}`
ADD `payone_sequencenumber`           smallint(6) DEFAULT NULL COMMENT 'Sequencenumber';
