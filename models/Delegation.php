<?php
/**
 * Delegation Model
 */

require_once __DIR__ . '/BaseModel.php';

class Delegation extends BaseModel
{
    protected static $table = 'delegations';
    protected static $primaryKey = 'delegation_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'delegator_user_id',
        'delegate_user_id',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * Get active delegations for a user
     * 
     * @param int $userId
     * @return array
     */
    public static function getActiveForUser($userId)
    {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM delegations 
                WHERE delegate_user_id = ? 
                AND status = 'active' 
                AND start_date <= ? 
                AND end_date >= ?";
        return Database::fetchAll($sql, [$userId, $now, $now]);
    }

    /**
     * Get active delegators for a user
     * 
     * @param int $userId
     * @return array List of delegator user IDs
     */
    public static function getActiveDelegators($userId)
    {
        $delegations = self::getActiveForUser($userId);
        return array_column($delegations, 'delegator_user_id');
    }
}
