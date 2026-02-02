<?php
/**
 * Department Model
 */

require_once __DIR__ . '/BaseModel.php';

class Department extends BaseModel
{
    protected static $table = 'departments';
    protected static $primaryKey = 'department_id';
    protected static $fillable = [
        'department_name',
        'department_code',
        'supervisor_user_id',
        'parent_department_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get department with supervisor details
     *
     * @param int $departmentId Department ID
     * @return array|false
     */
    public static function findWithSupervisor($departmentId)
    {
        $sql = "SELECT d.*,
                       u.full_name as supervisor_name,
                       u.username as supervisor_username
                FROM departments d
                LEFT JOIN users u ON d.supervisor_user_id = u.user_id
                WHERE d.department_id = ?
                AND d.deleted_at IS NULL
                LIMIT 1";

        return Database::fetchOne($sql, [$departmentId]);
    }

    /**
     * Get all departments with supervisor details
     *
     * @return array
     */
    public static function getAllWithSupervisors()
    {
        $sql = "SELECT d.*,
                       u.full_name as supervisor_name,
                       u.username as supervisor_username,
                       COUNT(DISTINCT users.user_id) as user_count
                FROM departments d
                LEFT JOIN users u ON d.supervisor_user_id = u.user_id
                LEFT JOIN users ON users.department_id = d.department_id AND users.deleted_at IS NULL
                WHERE d.deleted_at IS NULL
                GROUP BY d.department_id
                ORDER BY d.department_name";

        return Database::fetchAll($sql);
    }

    /**
     * Get active departments
     *
     * @return array
     */
    public static function getActive()
    {
        return self::where(['status' => 'active']);
    }

    /**
     * Find by code
     *
     * @param string $code Department code
     * @return array|false
     */
    public static function findByCode($code)
    {
        return self::first(['department_code' => $code]);
    }

    /**
     * Get department users
     *
     * @param int $departmentId Department ID
     * @param bool $activeOnly Only active users
     * @return array
     */
    public static function getUsers($departmentId, $activeOnly = true)
    {
        $sql = "SELECT u.*, r.role_name, r.role_key
                FROM users u
                INNER JOIN roles r ON u.role_id = r.role_id
                WHERE u.department_id = ?
                AND u.deleted_at IS NULL";

        $params = [$departmentId];

        if ($activeOnly) {
            $sql .= " AND u.status = ?";
            $params[] = USER_STATUS_ACTIVE;
        }

        $sql .= " ORDER BY u.full_name";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get sub-departments
     *
     * @param int $parentId Parent department ID
     * @return array
     */
    public static function getSubDepartments($parentId)
    {
        return self::where(['parent_department_id' => $parentId, 'status' => 'active']);
    }
}
