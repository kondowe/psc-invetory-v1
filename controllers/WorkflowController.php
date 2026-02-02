<?php
/**
 * WorkflowController
 */
require_once __DIR__ . '/../models/WorkflowTemplate.php';
require_once __DIR__ . '/../models/WorkflowStep.php';
require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/Role.php';

class WorkflowController
{
    /**
     * Configure department workflows
     */
    public function configure()
    {
        Auth::requireAuth();
        RBAC::require('workflow.configure');

        $departmentId = Auth::departmentId();
        
        // If user can view all requests, allow them to manage all departments
        if (RBAC::can('request.view_all') && isset($_GET['dept_id'])) {
            $departmentId = (int)$_GET['dept_id'];
        }

        $templates = WorkflowTemplate::where(['department_id' => $departmentId], false);
        $globalTemplates = WorkflowTemplate::where(['template_type' => 'global'], false);
        
        $departments = [];
        if (RBAC::can('request.view_all')) {
            $departments = Department::all();
        }

        Response::view('workflow/configure', [
            'templates' => $templates,
            'globalTemplates' => $globalTemplates,
            'departments' => $departments,
            'currentDeptId' => $departmentId
        ]);
    }

    /**
     * Create new template
     */
    public function createTemplate()
    {
        Auth::requireAuth();
        RBAC::require('workflow.configure');
        Security::checkCsrfToken();

        $departmentId = Auth::departmentId();
        if (RBAC::can('request.view_all') && !empty($_POST['department_id'])) {
            $departmentId = (int)$_POST['department_id'];
        }

        $data = [
            'template_name' => Security::sanitize($_POST['template_name'] ?? '', 'string'),
            'template_type' => $departmentId ? 'department' : 'global',
            'department_id' => $departmentId,
            'request_type' => $_POST['request_type'] ?? 'both',
            'is_active' => 1,
            'created_by_user_id' => Auth::id()
        ];

        if (empty($data['template_name'])) {
            Session::flash('error', 'Template name is required');
            Response::back();
        }

        try {
            $templateId = WorkflowTemplate::create($data);
            Session::flash('success', 'Template created successfully');
            Response::redirect(Security::url('/workflow/viewSteps/' . $templateId));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to create template: ' . $e->getMessage());
            Response::back();
        }
    }

    /**
     * Delete template
     */
    public function deleteTemplate($templateId)
    {
        Auth::requireAuth();
        RBAC::require('workflow.configure');
        Security::checkCsrfToken();

        $template = WorkflowTemplate::find($templateId);
        if (!$template) Response::notFound();

        // System templates cannot be deleted normally
        if ($template['template_type'] == 'global' && !RBAC::can('request.view_all')) {
            Session::flash('error', 'Only system administrators can delete global templates');
            Response::back();
        }

        try {
            WorkflowTemplate::delete($templateId);
            Session::flash('success', 'Template deleted');
            Response::redirect(Security::url('/workflow/configure' . ($template['department_id'] ? '?dept_id=' . $template['department_id'] : '')));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to delete template: ' . $e->getMessage());
            Response::back();
        }
    }

    /**
     * View template steps
     */
    public function viewSteps($templateId)
    {
        Auth::requireAuth();
        RBAC::require('workflow.view');

        $template = WorkflowTemplate::find($templateId);
        if (!$template) Response::notFound();

        $steps = WorkflowStep::getByTemplate($templateId);
        $roles = Role::all();

        Response::view('workflow/steps', [
            'template' => $template,
            'steps' => $steps,
            'roles' => $roles
        ]);
    }

    public function addStep($templateId)
    {
        Auth::requireAuth();
        RBAC::require('workflow.configure');
        Security::checkCsrfToken();

        $data = [
            'workflow_template_id' => (int)$templateId,
            'step_order' => (int)$_POST['step_order'],
            'step_name' => Security::sanitize($_POST['step_name'] ?? '', 'string'),
            'approver_role_id' => (int)$_POST['approver_role_id'],
            'is_mandatory' => isset($_POST['is_mandatory']) ? 1 : 0,
            'is_system_step' => 0,
            'can_be_removed' => 1,
            'condition_type' => $_POST['condition_type'] ?? 'none',
            'action_on_approval' => 'proceed',
            'action_on_rejection' => 'end',
            'sla_hours' => (int)($_POST['sla_hours'] ?? 24)
        ];

        try {
            WorkflowStep::create($data);
            Session::flash('success', 'Step added');
        } catch (Exception $e) {
            Session::flash('error', 'Failed to add step: ' . $e->getMessage());
        }
        Response::back();
    }

    public function deleteStep($stepId)
    {
        Auth::requireAuth();
        RBAC::require('workflow.configure');
        Security::checkCsrfToken();

        $step = WorkflowStep::find($stepId);
        if (!$step) Response::notFound();

        if ($step['is_system_step'] && !RBAC::can('request.view_all')) {
            Session::flash('error', 'System steps cannot be deleted');
            Response::back();
        }

        try {
            WorkflowStep::delete($stepId);
            Session::flash('success', 'Step removed');
        } catch (Exception $e) {
            Logger::error('Failed to remove step ID ' . $stepId . ': ' . $e->getMessage());
            Session::flash('error', 'Failed to remove step: ' . $e->getMessage());
        }
        Response::back();
    }

    /**
     * API: Get steps for a template
     */
    public function apiGetSteps($templateId)
    {
        Auth::requireAuth();
        $steps = WorkflowStep::getByTemplate($templateId);
        Response::success($steps);
    }
}
