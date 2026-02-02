<?php
/**
 * IssueVoucherItem Model
 */
require_once __DIR__ . '/BaseModel.php';

class IssueVoucherItem extends BaseModel
{
    protected static $table = 'issue_voucher_items';
    protected static $primaryKey = 'issue_voucher_item_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'issue_voucher_id',
        'request_item_id',
        'item_id',
        'quantity_issued',
        'unit_cost',
        'batch_number',
        'notes'
    ];
}
