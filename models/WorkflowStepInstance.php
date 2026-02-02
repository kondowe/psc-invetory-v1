<?php
/**
 * WorkflowStepInstance Model
 */
require_once __DIR__ . '/BaseModel.php';

class WorkflowStepInstance extends BaseModel
{
    protected static $table = 'workflow_step_instances';
    protected static $primaryKey = 'workflow_step_instance_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'workflow_instance_id',
        'workflow_step_id',
        'step_order',
        'assigned_role_id',
        'assigned_user_id',
        'status',
        'action_taken_by_user_id',
        'action_date',
        'comments',
        'sla_due_date',
        'created_at'
    ];

    public static function getPendingForUser($userId, $roleId)
    {
        require_once __DIR__ . '/Delegation.php';
        $delegatorIds = Delegation::getActiveDelegators($userId);
        
        $sql = "SELECT wsi.*, r.request_id, r.request_number, r.request_type, r.purpose, u.full_name as requester_name, ws.step_name
                FROM workflow_step_instances wsi
                JOIN workflow_instances wi ON wsi.workflow_instance_id = wi.workflow_instance_id
                JOIN requests r ON wi.request_id = r.request_id
                JOIN users u ON r.requester_user_id = u.user_id
                JOIN workflow_steps ws ON wsi.workflow_step_id = ws.workflow_step_id
                WHERE wsi.status = 'pending'
                AND (
                    wsi.assigned_user_id = ? 
                    OR (wsi.assigned_user_id IS NULL AND wsi.assigned_role_id = ?)
                ";

        $params = [$userId, $roleId];

        if (!empty($delegatorIds)) {
            $placeholders = implode(',', array_fill(0, count($delegatorIds), '?'));
            $sql .= " OR (wsi.assigned_user_id IN ($placeholders))";
            $params = array_merge($params, $delegatorIds);
        }

        $sql .= ") 
                AND wi.status = 'in_progress'
                AND wsi.step_order = wi.current_step_order";
        
        return Database::fetchAll($sql, $params);
    }
}
