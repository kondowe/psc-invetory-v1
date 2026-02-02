<?php
/**
 * SlaController
 */
require_once __DIR__ . '/../models/SystemConfig.php';
require_once __DIR__ . '/../models/WorkflowStep.php';
require_once __DIR__ . '/../models/WorkflowTemplate.php';

class SlaController
{
    /**
     * Display SLA management page
     */
    public function index()
    {
        Auth::requireAuth();
        RBAC::require('user.view'); // Admin only

        // Global SLA Settings
        $globalSlas = [
            'default_sla_hours' => SystemConfig::first(['config_key' => 'default_sla_hours']),
            'enable_workflow_sla_reminders' => SystemConfig::first(['config_key' => 'enable_workflow_sla_reminders']),
            'sla_reminder_hours_before' => SystemConfig::first(['config_key' => 'sla_reminder_hours_before'])
        ];

        // Workflow Step SLAs
        $sql = "SELECT ws.*, wt.template_name, r.role_name
                FROM workflow_steps ws
                JOIN workflow_templates wt ON ws.workflow_template_id = wt.workflow_template_id
                JOIN roles r ON ws.approver_role_id = r.role_id
                ORDER BY wt.template_name, ws.step_order";
        $stepSlas = Database::fetchAll($sql);

        Response::view('sla/index', [
            'globalSlas' => $globalSlas,
            'stepSlas' => $stepSlas
        ]);
    }

    /**
     * Update global SLA settings
     */
    public function updateGlobal()
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        foreach ($_POST['config'] as $key => $value) {
            SystemConfig::set($key, $value);
        }

        Session::flash('success', 'Global SLA settings updated');
        Response::redirect(Security::url('/sla'));
    }

    /**
     * Update specific step SLA
     */
    public function updateStep()
    {
        Auth::requireAuth();
        RBAC::require('user.edit');
        Security::checkCsrfToken();

        $stepId = (int)$_POST['workflow_step_id'];
        $slaHours = (int)$_POST['sla_hours'];

        if (WorkflowStep::update($stepId, ['sla_hours' => $slaHours])) {
            Session::flash('success', 'Step SLA updated');
        } else {
            Session::flash('error', 'Failed to update step SLA');
        }

        Response::redirect(Security::url('/sla'));
    }
}
