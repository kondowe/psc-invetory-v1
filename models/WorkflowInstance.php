<?php
/**
 * WorkflowInstance Model
 */
require_once __DIR__ . '/BaseModel.php';

class WorkflowInstance extends BaseModel
{
    protected static $table = 'workflow_instances';
    protected static $primaryKey = 'workflow_instance_id';
    protected static $softDelete = false;
    protected static $fillable = [
        'request_id',
        'workflow_template_id',
        'current_step_order',
        'status',
        'started_at',
        'completed_at'
    ];
}
