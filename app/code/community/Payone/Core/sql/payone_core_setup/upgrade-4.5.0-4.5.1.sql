#---------------------------------------------------------------------------------------------
#-- Add column process_retry_count to PAYONE transaction status protocol table.
#-- This allows us to track the processing retries for a particular transaction status entry.
#---------------------------------------------------------------------------------------------

ALTER TABLE `{{payone_protocol_transactionstatus}}`
  ADD `process_retry_count` INTEGER DEFAULT 0 NOT NULL COMMENT 'Count of processing retries';


#---------------------------------------------------------------------------------------------
#-- Add column processing_error_stacktrace to PAYONE transaction status protocol table.
#-- This provides more detailed error information about a failed transaction status processing.
#---------------------------------------------------------------------------------------------

ALTER TABLE `{{payone_protocol_transactionstatus}}`
    ADD `processing_error_stacktrace` TEXT NULL COMMENT 'Detailed error stacktrace';
