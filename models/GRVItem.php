<?php
/**
 * GRVItem Model
 *
 * Manages line items for a Goods Received Voucher
 */

require_once __DIR__ . '/BaseModel.php';

class GRVItem extends BaseModel
{
    protected static $table = 'grv_items';
    protected static $primaryKey = 'grv_item_id';
    protected static $fillable = [
        'grv_id',
        'item_id',
        'quantity',
        'unit_cost',
        'batch_number',
        'expiry_date',
        'is_fuel_coupon',
        'fuel_type_id',
        'coupon_serial_from',
        'coupon_serial_to',
        'coupon_count',
        'coupon_value',
        'notes'
    ];
    protected static $softDelete = false;

    /**
     * Get items for a GRV
     *
     * @param int $grvId GRV ID
     * @return array
     */
    public static function getByGrv($grvId)
    {
        $sql = "SELECT gi.*, i.item_name, i.sku, u.uom_code
                FROM " . static::$table . " gi
                JOIN items i ON gi.item_id = i.item_id
                JOIN units_of_measure u ON i.uom_id = u.uom_id
                WHERE gi.grv_id = ?";
        
        return Database::fetchAll($sql, [$grvId]);
    }
}
