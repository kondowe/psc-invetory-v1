<?php
/**
 * WorkflowService
 * 
 * Logic for managing request workflows
 */

require_once __DIR__ . '/../models/WorkflowTemplate.php';
require_once __DIR__ . '/../models/WorkflowStep.php';
require_once __DIR__ . '/../models/WorkflowInstance.php';
require_once __DIR__ . '/../models/WorkflowStepInstance.php';
require_once __DIR__ . '/../models/Request.php';
require_once __DIR__ . '/../models/RequestItem.php';
require_once __DIR__ . '/../models/StockLevel.php';
require_once __DIR__ . '/NotificationService.php';

class WorkflowService
{
    /**
     * Initialize workflow for a request
     */
    public static function initialize($requestId)
    {
        $request = Request::getWithDetails($requestId);
        if (!$request) throw new Exception("Request not found");

        $allSteps = [];
        $primaryTemplateId = null;

        // 1. Get Department Template
        $sql = "SELECT * FROM workflow_templates 
                WHERE department_id = ? 
                AND (request_type = ? OR request_type = 'both')
                AND is_active = 1 AND deleted_at IS NULL 
                LIMIT 1";
        $deptTemplate = Database::fetchOne($sql, [$request['department_id'], $request['request_type']]);

        if ($deptTemplate) {
            $primaryTemplateId = $deptTemplate['workflow_template_id'];
            $deptSteps = WorkflowStep::getByTemplate($primaryTemplateId);
            foreach ($deptSteps as $step) {
                $allSteps[] = $step;
            }
        }

        // 2. Always append Global Template steps
        $globalTemplate = WorkflowTemplate::first(['template_type' => 'global', 'is_active' => 1]);
        if ($globalTemplate) {
            if (!$primaryTemplateId) $primaryTemplateId = $globalTemplate['workflow_template_id'];
            
            $globalSteps = WorkflowStep::getByTemplate($globalTemplate['workflow_template_id']);
            foreach ($globalSteps as $step) {
                if ($deptTemplate && $deptTemplate['workflow_template_id'] == $globalTemplate['workflow_template_id']) {
                    continue;
                }
                $allSteps[] = $step;
            }
        }

        if (empty($allSteps)) throw new Exception("No workflow steps found for this request type");

        // 3. Create Instance
        $instanceId = WorkflowInstance::create([
            'request_id' => $requestId,
            'workflow_template_id' => $primaryTemplateId,
            'current_step_order' => 1,
            'status' => 'in_progress',
            'started_at' => date('Y-m-d H:i:s')
        ]);

        // 4. Create Step Instances with sequential ordering & conditions
        $seqOrder = 1;
        $firstPendingSet = false;

        foreach ($allSteps as $step) {
            $status = 'pending';
            
            // Check condition
            if (self::shouldSkipStep($step, $request)) {
                $status = 'skipped';
            }

            $wsiId = WorkflowStepInstance::create([
                'workflow_instance_id' => $instanceId,
                'workflow_step_id' => $step['workflow_step_id'],
                'step_order' => $seqOrder++,
                'assigned_role_id' => $step['approver_role_id'],
                'status' => $status
            ]);

            // If this is the first non-skipped step, notify
            if (!$firstPendingSet && $status === 'pending') {
                WorkflowInstance::update($instanceId, ['current_step_order' => $seqOrder - 1]);
                self::notifyApprover($wsiId, $request);
                $firstPendingSet = true;
            }
        }

        // 5. Update Request
        Request::update($requestId, [
            'workflow_instance_id' => $instanceId,
            'status' => 'pending',
            'submitted_at' => date('Y-m-d H:i:s')
        ]);

        // Notify requester
        NotificationService::send($request['requester_user_id'], 'info', 'Request Submitted', "Your request #{$request['request_number']} has been submitted for approval.", 'request', $requestId);

        return $instanceId;
    }

    /**
     * Process an approval action
     */
    public static function approve($stepInstanceId, $userId, $comments = '')
    {
        $stepInstance = WorkflowStepInstance::find($stepInstanceId);
        if (!$stepInstance) throw new Exception("Step instance not found");

        $instance = WorkflowInstance::find($stepInstance['workflow_instance_id']);
        $request = Request::getWithDetails($instance['request_id']);

        Database::beginTransaction();
        try {
            // 1. Update current step
            WorkflowStepInstance::update($stepInstanceId, [
                'status' => 'approved',
                'action_taken_by_user_id' => $userId,
                'action_date' => date('Y-m-d H:i:s'),
                'comments' => $comments
            ]);

            // 2. Find next non-skipped step
            $nextStep = Database::fetchOne(
                "SELECT * FROM workflow_step_instances 
                 WHERE workflow_instance_id = ? AND step_order > ? AND status = 'pending'
                 ORDER BY step_order ASC LIMIT 1",
                [$instance['workflow_instance_id'], $stepInstance['step_order']]
            );

            if ($nextStep) {
                // Move to next step
                WorkflowInstance::update($instance['workflow_instance_id'], [
                    'current_step_order' => $nextStep['step_order']
                ]);
                self::notifyApprover($nextStep['workflow_step_instance_id'], $request);
            } else {
                // Workflow complete
                WorkflowInstance::update($instance['workflow_instance_id'], [
                    'status' => 'completed',
                    'completed_at' => date('Y-m-d H:i:s')
                ]);

                Request::update($request['request_id'], [
                    'status' => 'approved'
                ]);

                // Notify requester
                NotificationService::send($request['requester_user_id'], 'success', 'Request Approved', "Your request #{$request['request_number']} has been fully approved.", 'request', $request['request_id']);
                
                // Notify stores
                $storesRole = Database::fetchOne("SELECT role_id FROM roles WHERE role_key = 'stores_officer'");
                if ($storesRole) {
                    NotificationService::notifyRole($storesRole['role_id'], 'info', 'New Release Instruction', "Request #{$request['request_number']} is approved and ready for issuance.", 'request', $request['request_id']);
                }

                self::reserveStockForRequest($request['request_id']);
            }

            Database::commit();
            return true;
        } catch (Exception $e) {
            Database::rollBack();
            throw $e;
        }
    }

    /**
     * Process a rejection action
     */
    public static function reject($stepInstanceId, $userId, $comments = '')
    {
        $stepInstance = WorkflowStepInstance::find($stepInstanceId);
        $instance = WorkflowInstance::find($stepInstance['workflow_instance_id']);
        $request = Request::find($instance['request_id']);
        
        Database::beginTransaction();
        try {
            WorkflowStepInstance::update($stepInstanceId, [
                'status' => 'rejected',
                'action_taken_by_user_id' => $userId,
                'action_date' => date('Y-m-d H:i:s'),
                'comments' => $comments
            ]);

            WorkflowInstance::update($instance['workflow_instance_id'], [
                'status' => 'rejected',
                'completed_at' => date('Y-m-d H:i:s')
            ]);

            Request::update($instance['request_id'], [
                'status' => 'rejected'
            ]);

            // Notify requester
            NotificationService::send($request['requester_user_id'], 'error', 'Request Rejected', "Your request #{$request['request_number']} was rejected. Reason: $comments", 'request', $request['request_id']);

            Database::commit();
            return true;
        } catch (Exception $e) {
            Database::rollBack();
            throw $e;
        }
    }

    /**
     * Condition logic
     */
    private static function shouldSkipStep($step, $request)
    {
        if ($step['condition_type'] === 'none' || empty($step['condition_value'])) {
            return false;
        }

        switch ($step['condition_type']) {
            case 'amount':
                // Requires item prices to be set. Calculate total value.
                $totalVal = Database::fetchOne("SELECT SUM(quantity_requested * unit_cost_estimate) as val FROM request_items WHERE request_id = ?", [$request['request_id']])['val'];
                return (float)$totalVal < (float)$step['condition_value'];
            
            case 'priority':
                return $request['priority'] !== $step['condition_value'];

            case 'category':
                // Check if any item in request belongs to this category
                $exists = Database::fetchOne("SELECT COUNT(*) as count FROM request_items ri JOIN items i ON ri.item_id = i.item_id WHERE ri.request_id = ? AND i.category_id = ?", [$request['request_id'], $step['condition_value']]);
                return $exists['count'] == 0;
        }

        return false;
    }

    private static function notifyApprover($wsiId, $request)
    {
        $wsi = WorkflowStepInstance::find($wsiId);
        $role = Database::fetchOne("SELECT role_name FROM roles WHERE role_id = ?", [$wsi['assigned_role_id']]);
        
        $title = "Approval Required";
        $message = "Request #{$request['request_number']} from {$request['requester_name']} requires your attention.";
        
        NotificationService::notifyRole($wsi['assigned_role_id'], 'warning', $title, $message, 'request', $request['request_id'], $request['department_id']);
    }

    private static function reserveStockForRequest($requestId)
    {
        $items = RequestItem::getByRequest($requestId);
        foreach ($items as $item) {
            if ($item['is_custom']) continue;
            
            RequestItem::update($item['request_item_id'], [
                'quantity_approved' => $item['quantity_requested']
            ]);
        }
    }
}
