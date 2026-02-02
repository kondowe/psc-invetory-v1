<?php
/**
 * RequestItem Model
 */
require_once __DIR__ . '/BaseModel.php';

class RequestItem extends BaseModel
{
    protected static $table = 'request_items';
    protected static $primaryKey = 'request_item_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'request_id',
        'item_id',
        'is_custom',
        'custom_item_name',
        'quantity_requested',
        'quantity_approved',
        'quantity_issued',
        'unit_cost_estimate',
        'justification',
        'status'
    ];

    public static function getByRequest($requestId)
    {
        $sql = "SELECT ri.*, i.item_name, i.sku, u.uom_code
                FROM " . static::$table . " ri
                LEFT JOIN items i ON ri.item_id = i.item_id
                LEFT JOIN units_of_measure u ON i.uom_id = u.uom_id
                WHERE ri.request_id = ?";
        return Database::fetchAll($sql, [$requestId]);
    }
}
