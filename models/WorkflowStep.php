<?php
/**
 * WorkflowStep Model
 */
require_once __DIR__ . '/BaseModel.php';

class WorkflowStep extends BaseModel
{
    protected static $table = 'workflow_steps';
    protected static $primaryKey = 'workflow_step_id';
    protected static $softDelete = true;
    protected static $fillable = [
        'workflow_template_id',
        'step_order',
        'step_name',
        'approver_role_id',
        'is_mandatory',
        'is_system_step',
        'can_be_removed',
        'condition_type',
        'condition_value',
        'action_on_approval',
        'action_on_rejection',
        'sla_hours',
        'created_at',
        'deleted_at'
    ];

    public static function getByTemplate($templateId)
    {
        return static::where(['workflow_template_id' => $templateId], false, 'step_order ASC');
    }
}
