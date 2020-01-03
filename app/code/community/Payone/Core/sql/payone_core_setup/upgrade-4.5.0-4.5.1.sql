#--------------------------------------------------------------------------------------------------------------
# Alter tables sales_flat_order, payone_transaction, payone_protocole_transactionstatus, adding some indexes
#--------------------------------------------------------------------------------------------------------------

CREATE INDEX 'IDX_SALES_FLAT_ORDER_PO_CANCEL_SUBSTITUTE_INCREMENT_ID' ON `{{sales_flat_order}}`('payone_cancel_substitute_increment_id');
CREATE INDEX 'IDX_PAYONE_TRANSACTION_TXID' ON `{{payone_transaction}}`('txid');
CREATE INDEX 'IDX_PAYONE_PROTOCOL_TRANSACTIONSTATUS_TXID' ON `{{payone_protocol_transactionstatus}}`('txid');
