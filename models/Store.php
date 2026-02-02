<?php
/**
 * Store Model
 *
 * Manages warehouse/store locations
 */

require_once __DIR__ . '/BaseModel.php';

class Store extends BaseModel
{
    protected static $table = 'stores';
    protected static $primaryKey = 'store_id';
    protected static $fillable = [
        'store_name',
        'store_code',
        'location',
        'store_type',
        'is_active',
        'created_at',
        'deleted_at'
    ];

    /**
     * Get all active stores
     *
     * @return array
     */
    public static function getActiveStores()
    {
        return static::where(['is_active' => 1], false);
    }

    /**
     * Find store by code
     *
     * @param string $code Store code
     * @return array|false
     */
    public static function findByCode($code)
    {
        return static::first(['store_code' => $code]);
    }

    /**
     * Get stores filtered by type
     *
     * @param string $type Store type (main, branch, department)
     * @return array
     */
    public static function getStoresByType($type)
    {
        return static::where(['store_type' => $type, 'is_active' => 1]);
    }
}
