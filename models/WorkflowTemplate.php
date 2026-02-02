<?php
/**
 * WorkflowTemplate Model
 */
require_once __DIR__ . '/BaseModel.php';

class WorkflowTemplate extends BaseModel
{
    protected static $table = 'workflow_templates';
    protected static $primaryKey = 'workflow_template_id';
    protected static $fillable = [
        'template_name',
        'template_type',
        'department_id',
        'request_type',
        'is_active',
        'created_by_user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function getForDepartment($departmentId, $requestType = 'both')
    {
        $sql = "SELECT * FROM " . static::$table . " 
                WHERE (department_id = ? OR template_type = 'global') 
                AND (request_type = ? OR request_type = 'both')
                AND is_active = 1 AND deleted_at IS NULL 
                ORDER BY template_type DESC LIMIT 1";
        return Database::fetchOne($sql, [$departmentId, $requestType]);
    }
}
