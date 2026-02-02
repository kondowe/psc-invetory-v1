<?php
/**
 * Supplier Model
 *
 * Manages suppliers and vendors
 */

require_once __DIR__ . '/BaseModel.php';

class Supplier extends BaseModel
{
    protected static $table = 'suppliers';
    protected static $primaryKey = 'supplier_id';
    protected static $fillable = [
        'supplier_name',
        'supplier_code',
        'contact_person',
        'email',
        'phone',
        'address',
        'supplier_type',
        'is_active',
        'created_at',
        'deleted_at'
    ];

    /**
     * Get active suppliers for dropdowns
     *
     * @param string|null $type Filter by type (general, fuel_vendor, both)
     * @return array
     */
    public static function getActive($type = null)
    {
        $conditions = ['is_active' => 1];
        if ($type && $type !== 'both') {
            // If type is both, it should show up in both fuel and general searches
            // But here we might want specific logic for 'both'
        }
        
        $sql = "SELECT * FROM " . static::$table . " WHERE is_active = 1 AND deleted_at IS NULL";
        $params = [];

        if ($type) {
            if ($type === 'general') {
                $sql .= " AND (supplier_type = 'general' OR supplier_type = 'both')";
            } elseif ($type === 'fuel_vendor') {
                $sql .= " AND (supplier_type = 'fuel_vendor' OR supplier_type = 'both')";
            }
        }

        $sql .= " ORDER BY supplier_name ASC";
        return Database::fetchAll($sql, $params);
    }

    /**
     * Validate supplier code uniqueness
     *
     * @param string $code Supplier code
     * @param int|null $excludeId Exclude this ID
     * @return bool
     */
    public static function validateCodeUnique($code, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM " . static::$table . " WHERE supplier_code = ? AND deleted_at IS NULL";
        $params = [$code];

        if ($excludeId) {
            $sql .= " AND supplier_id != ?";
            $params[] = $excludeId;
        }

        $result = Database::fetchOne($sql, $params);
        return $result['count'] == 0;
    }
}