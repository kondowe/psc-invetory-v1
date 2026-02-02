-- =================================================================
-- Seed Workflow Templates (System-Mandatory Steps)
-- =================================================================

USE inventory_system;

-- Global Workflow Template (System-Mandatory)
INSERT INTO workflow_templates (template_name, template_type, department_id, request_type, is_active, created_by_user_id) VALUES
('Global System Workflow', 'global', NULL, 'both', TRUE, 1);

-- System-Mandatory Workflow Steps (Cannot be removed or modified by departments)
-- These steps are enforced for ALL requests
INSERT INTO workflow_steps (workflow_template_id, step_order, step_name, approver_role_id, is_mandatory, is_system_step, can_be_removed, action_on_approval, action_on_rejection, sla_hours) VALUES
-- Step 1: Administration Manager (system-enforced)
(1, 1, 'Administration Manager Approval', 3, TRUE, TRUE, FALSE, 'proceed', 'end', 24),

-- Step 2: General Administration Manager (system-enforced)
(1, 2, 'General Administration Manager Approval', 4, TRUE, TRUE, FALSE, 'proceed', 'end', 24),

-- Step 3: Stores Officer Release Instruction (system-enforced)
(1, 3, 'Stores Officer - Release Instruction', 5, TRUE, TRUE, FALSE, 'complete', 'end', 48);

-- Sample Department-Specific Workflow for IT Department
INSERT INTO workflow_templates (template_name, template_type, department_id, request_type, is_active, created_by_user_id) VALUES
('IT Department Workflow', 'department', 4, 'both', TRUE, 4);

-- Department workflow steps (these come BEFORE system-mandatory steps)
INSERT INTO workflow_steps (workflow_template_id, step_order, step_name, approver_role_id, is_mandatory, is_system_step, can_be_removed, action_on_approval, action_on_rejection, sla_hours) VALUES
-- Department Step 1: Department Supervisor
(2, 1, 'IT Supervisor Approval', 2, TRUE, FALSE, TRUE, 'proceed', 'return_to_requester', 24),

-- Department Step 2: (Then goes to system-mandatory steps)
-- Note: System will automatically append global workflow steps after department steps

-- Sample Department-Specific Workflow for Operations Department
(2, 2, 'Operations Manager Review', 2, FALSE, FALSE, TRUE, 'proceed', 'return_to_requester', 12);

INSERT INTO workflow_templates (template_name, template_type, department_id, request_type, is_active, created_by_user_id) VALUES
('Operations Department Workflow', 'department', 5, 'both', TRUE, 5);

INSERT INTO workflow_steps (workflow_template_id, step_order, step_name, approver_role_id, is_mandatory, is_system_step, can_be_removed, action_on_approval, action_on_rejection, sla_hours) VALUES
(3, 1, 'Operations Supervisor Approval', 2, TRUE, FALSE, TRUE, 'proceed', 'return_to_requester', 24);
