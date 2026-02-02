<?php
/**
 * Permission Model
 */

require_once __DIR__ . '/BaseModel.php';

class Permission extends BaseModel
{
    protected static $table = 'permissions';
    protected static $primaryKey = 'permission_id';
    protected static $fillable = ['permission_key', 'module', 'action', 'description'];
    protected static $softDelete = false;

    /**
     * Get permissions grouped by module
     *
     * @return array
     */
    public static function getAllGroupedByModule()
    {
        $sql = "SELECT * FROM permissions ORDER BY module, action";
        $permissions = Database::fetchAll($sql);

        $grouped = [];
        foreach ($permissions as $permission) {
            $module = $permission['module'];
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = $permission;
        }

        return $grouped;
    }

    /**
     * Find permission by key
     *
     * @param string $permissionKey Permission key
     * @return array|false
     */
    public static function findByKey($permissionKey)
    {
        return self::first(['permission_key' => $permissionKey]);
    }
}
