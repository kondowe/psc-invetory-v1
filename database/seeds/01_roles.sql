-- =================================================================
-- Seed Roles
-- =================================================================

USE inventory_system;

INSERT INTO roles (role_name, role_key, description) VALUES
('Requester', 'requester', 'Creates item requests, views own requests and statuses'),
('Department Supervisor', 'dept_supervisor', 'Approves department requests, configures department workflow'),
('Administration Manager', 'admin_mgr', 'Approves/rejects requests system-wide, views inventory availability'),
('General Administration Manager', 'general_admin_mgr', 'Final approval authority, view system-wide dashboards and reports'),
('Stores Officer', 'stores_officer', 'Manages inventory stock, creates GRVs, issues items against approved requests');
