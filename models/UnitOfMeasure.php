<?php
/**
 * UnitOfMeasure Model
 *
 * Manages units of measure (UOM) for inventory items
 */

require_once __DIR__ . '/BaseModel.php';

class UnitOfMeasure extends BaseModel
{
    protected static $table = 'units_of_measure';
    protected static $primaryKey = 'uom_id';
    protected static $fillable = [
        'uom_name',
        'uom_code',
        'description'
    ];
    protected static $softDelete = false;

    /**
     * Find unit of measure by code
     *
     * @param string $code UOM code
     * @return array|false
     */
    public static function findByCode($code)
    {
        return static::first(['uom_code' => $code]);
    }

    /**
     * Get all active units for dropdowns
     *
     * @return array
     */
    public static function getActiveUnits()
    {
        $sql = "SELECT * FROM " . static::$table . "
                ORDER BY uom_name ASC";

        return Database::fetchAll($sql);
    }
}
