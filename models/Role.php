<?php
/**
 * Role Model
 */

require_once __DIR__ . '/BaseModel.php';

class Role extends BaseModel
{
    protected static $table = 'roles';
    protected static $primaryKey = 'role_id';
    protected static $fillable = ['role_name', 'role_key', 'description'];
    protected static $softDelete = false;

    /**
     * Find role by key
     *
     * @param string $roleKey Role key
     * @return array|false
     */
    public static function findByKey($roleKey)
    {
        return self::first(['role_key' => $roleKey]);
    }

    /**
     * Get all roles with user count
     *
     * @return array
     */
    public static function getAllWithUserCount()
    {
        $sql = "SELECT r.*, COUNT(u.user_id) as user_count
                FROM roles r
                LEFT JOIN users u ON r.role_id = u.role_id AND u.deleted_at IS NULL
                GROUP BY r.role_id
                ORDER BY r.role_name";

        return Database::fetchAll($sql);
    }

    /**
     * Get role permissions
     *
     * @param int $roleId Role ID
     * @return array
     */
    public static function getPermissions($roleId)
    {
        $sql = "SELECT p.*
                FROM permissions p
                INNER JOIN role_permissions rp ON p.permission_id = rp.permission_id
                WHERE rp.role_id = ?
                ORDER BY p.module, p.action";

        return Database::fetchAll($sql, [$roleId]);
    }

    /**
     * Sync role permissions
     *
     * @param int $roleId Role ID
     * @param array $permissionIds Array of permission IDs
     * @return bool
     */
    public static function syncPermissions($roleId, $permissionIds)
    {
        Database::beginTransaction();
        try {
            // Remove existing permissions
            Database::query("DELETE FROM role_permissions WHERE role_id = ?", [$roleId]);

            // Add new permissions
            if (!empty($permissionIds)) {
                foreach ($permissionIds as $pId) {
                    Database::query(
                        "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)",
                        [$roleId, $pId]
                    );
                }
            }

            Database::commit();
            return true;
        } catch (Exception $e) {
            Database::rollBack();
            Logger::error("Failed to sync permissions for role $roleId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new role
     *
     * @param array $data Role data
     * @return int|false
     */
    public static function createRole($data)
    {
        return self::create($data);
    }
}
