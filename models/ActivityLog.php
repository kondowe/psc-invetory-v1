<?php
/**
 * ActivityLog Model
 */

require_once __DIR__ . '/BaseModel.php';

class ActivityLog extends BaseModel
{
    protected static $table = 'activity_logs';
    protected static $primaryKey = 'activity_log_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'user_id',
        'activity_type',
        'module',
        'description',
        'ip_address',
        'created_at'
    ];

    /**
     * Get activity logs with user details
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function getAllWithUsers($limit = 100, $offset = 0)
    {
        $sql = "SELECT al.*, u.full_name, u.username
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.user_id
                ORDER BY al.created_at DESC
                LIMIT ? OFFSET ?";

        return Database::fetchAll($sql, [$limit, $offset]);
    }
}
