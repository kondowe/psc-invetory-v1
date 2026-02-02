<?php
/**
 * AuditLog Model
 */

require_once __DIR__ . '/BaseModel.php';

class AuditLog extends BaseModel
{
    protected static $table = 'audit_logs';
    protected static $primaryKey = 'audit_log_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'table_name',
        'record_id',
        'action',
        'user_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    /**
     * Get audit logs with user details
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function getAllWithUsers($limit = 100, $offset = 0)
    {
        $sql = "SELECT al.*, u.full_name, u.username
                FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.user_id
                ORDER BY al.created_at DESC
                LIMIT ? OFFSET ?";

        return Database::fetchAll($sql, [$limit, $offset]);
    }
}
