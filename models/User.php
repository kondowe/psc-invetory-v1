<?php
/**
 * User Model
 */

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{
    protected static $table = 'users';
    protected static $primaryKey = 'user_id';
    protected static $fillable = [
        'username',
        'email',
        'password_hash',
        'full_name',
        'role_id',
        'department_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get user with role and department info
     *
     * @param int $userId User ID
     * @return array|false
     */
    public static function findWithDetails($userId)
    {
        $sql = "SELECT u.*, r.role_name, r.role_key,
                       d.department_name, d.department_code
                FROM users u
                INNER JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN departments d ON u.department_id = d.department_id
                WHERE u.user_id = ?
                AND u.deleted_at IS NULL
                LIMIT 1";

        return Database::fetchOne($sql, [$userId]);
    }

    /**
     * Get all users with role and department info
     *
     * @param array $filters Filters (role_id, department_id, status)
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array
     */
    public static function getAllWithDetails($filters = [], $page = 1, $perPage = 20)
    {
        $whereClauses = ["u.deleted_at IS NULL"];
        $params = [];

        if (isset($filters['role_id'])) {
            $whereClauses[] = "u.role_id = ?";
            $params[] = $filters['role_id'];
        }

        if (isset($filters['department_id'])) {
            $whereClauses[] = "u.department_id = ?";
            $params[] = $filters['department_id'];
        }

        if (isset($filters['status'])) {
            $whereClauses[] = "u.status = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $whereClauses[] = "(u.username LIKE ? OR u.full_name LIKE ? OR u.email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $whereClause = implode(" AND ", $whereClauses);

        // Count total
        $countSql = "SELECT COUNT(*) as count
                     FROM users u
                     WHERE {$whereClause}";
        $countResult = Database::fetchOne($countSql, $params);
        $total = $countResult['count'];

        // Get paginated data
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT u.*, r.role_name, r.role_key,
                       d.department_name, d.department_code
                FROM users u
                INNER JOIN roles r ON u.role_id = r.role_id
                LEFT JOIN departments d ON u.department_id = d.department_id
                WHERE {$whereClause}
                ORDER BY u.created_at DESC
                LIMIT ? OFFSET ?";

        $params[] = $perPage;
        $params[] = $offset;

        $data = Database::fetchAll($sql, $params);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Find user by username
     *
     * @param string $username Username
     * @return array|false
     */
    public static function findByUsername($username)
    {
        return self::first(['username' => $username]);
    }

    /**
     * Find user by email
     *
     * @param string $email Email
     * @return array|false
     */
    public static function findByEmail($email)
    {
        return self::first(['email' => $email]);
    }

    /**
     * Create user with hashed password
     *
     * @param array $data User data
     * @return int User ID
     */
    public static function createUser($data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password_hash'] = Security::hashPassword($data['password']);
            unset($data['password']);
        }

        return self::create($data);
    }

    /**
     * Update user
     *
     * @param int $userId User ID
     * @param array $data Data
     * @return int Affected rows
     */
    public static function updateUser($userId, $data)
    {
        // Hash password if being updated
        if (isset($data['password'])) {
            $data['password_hash'] = Security::hashPassword($data['password']);
            unset($data['password']);
        }

        return self::update($userId, $data);
    }

    /**
     * Get users by role
     *
     * @param string $roleKey Role key
     * @return array
     */
    public static function getByRole($roleKey)
    {
        $sql = "SELECT u.*
                FROM users u
                INNER JOIN roles r ON u.role_id = r.role_id
                WHERE r.role_key = ?
                AND u.status = ?
                AND u.deleted_at IS NULL
                ORDER BY u.full_name";

        return Database::fetchAll($sql, [$roleKey, USER_STATUS_ACTIVE]);
    }

    /**
     * Get users by department
     *
     * @param int $departmentId Department ID
     * @return array
     */
    public static function getByDepartment($departmentId)
    {
        return self::where([
            'department_id' => $departmentId,
            'status' => USER_STATUS_ACTIVE
        ]);
    }

    /**
     * Activate user
     *
     * @param int $userId User ID
     * @return int
     */
    public static function activate($userId)
    {
        return self::update($userId, ['status' => USER_STATUS_ACTIVE]);
    }

    /**
     * Deactivate user
     *
     * @param int $userId User ID
     * @return int
     */
    public static function deactivate($userId)
    {
        return self::update($userId, ['status' => USER_STATUS_INACTIVE]);
    }

    /**
     * Suspend user
     *
     * @param int $userId User ID
     * @return int
     */
    public static function suspend($userId)
    {
        return self::update($userId, ['status' => USER_STATUS_SUSPENDED]);
    }

    /**
     * Check if username exists
     *
     * @param string $username Username
     * @param int|null $exceptUserId Except this user ID
     * @return bool
     */
    public static function usernameExists($username, $exceptUserId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM users
                WHERE username = ? AND deleted_at IS NULL";
        $params = [$username];

        if ($exceptUserId) {
            $sql .= " AND user_id != ?";
            $params[] = $exceptUserId;
        }

        $result = Database::fetchOne($sql, $params);
        return $result['count'] > 0;
    }

    /**
     * Check if email exists
     *
     * @param string $email Email
     * @param int|null $exceptUserId Except this user ID
     * @return bool
     */
    public static function emailExists($email, $exceptUserId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM users
                WHERE email = ? AND deleted_at IS NULL";
        $params = [$email];

        if ($exceptUserId) {
            $sql .= " AND user_id != ?";
            $params[] = $exceptUserId;
        }

        $result = Database::fetchOne($sql, $params);
        return $result['count'] > 0;
    }
}
