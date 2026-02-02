-- =================================================================
-- Seed Permissions and Role-Permission Mappings
-- =================================================================

USE inventory_system;

-- Insert Permissions
INSERT INTO permissions (permission_key, module, action, description) VALUES
-- Request permissions
('request.create', 'request', 'create', 'Create new request'),
('request.view_own', 'request', 'view_own', 'View own requests'),
('request.view_department', 'request', 'view_department', 'View department requests'),
('request.view_all', 'request', 'view_all', 'View all requests'),
('request.edit_own', 'request', 'edit_own', 'Edit own requests'),
('request.cancel_own', 'request', 'cancel_own', 'Cancel own requests'),
('request.approve', 'request', 'approve', 'Approve requests'),

-- GRV permissions
('grv.create', 'grv', 'create', 'Create GRV'),
('grv.view', 'grv', 'view', 'View GRV'),
('grv.approve', 'grv', 'approve', 'Approve GRV'),

-- Issue permissions
('issue.create', 'issue', 'create', 'Create issue voucher'),
('issue.view', 'issue', 'view', 'View issue vouchers'),

-- Inventory permissions
('inventory.view', 'inventory', 'view', 'View inventory'),
('inventory.manage', 'inventory', 'manage', 'Manage inventory'),

-- Workflow permissions
('workflow.configure', 'workflow', 'configure', 'Configure workflow'),
('workflow.view', 'workflow', 'view', 'View workflow'),

-- Report permissions
('report.view_own', 'report', 'view_own', 'View own reports'),
('report.view_department', 'report', 'view_department', 'View department reports'),
('report.view_all', 'report', 'view_all', 'View all reports'),

-- User management permissions
('user.create', 'user', 'create', 'Create users'),
('user.edit', 'user', 'edit', 'Edit users'),
('user.view', 'user', 'view', 'View users'),

-- Dashboard permissions
('dashboard.requester', 'dashboard', 'requester', 'Access requester dashboard'),
('dashboard.supervisor', 'dashboard', 'supervisor', 'Access supervisor dashboard'),
('dashboard.admin_manager', 'dashboard', 'admin_manager', 'Access admin manager dashboard'),
('dashboard.general_admin', 'dashboard', 'general_admin', 'Access general admin dashboard'),
('dashboard.stores_officer', 'dashboard', 'stores_officer', 'Access stores officer dashboard');

-- Role-Permission Mappings
-- Requester (role_id = 1)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, permission_id FROM permissions WHERE permission_key IN (
    'request.create',
    'request.view_own',
    'request.edit_own',
    'request.cancel_own',
    'report.view_own',
    'dashboard.requester'
);

-- Department Supervisor (role_id = 2)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 2, permission_id FROM permissions WHERE permission_key IN (
    'request.create',
    'request.view_own',
    'request.view_department',
    'request.approve',
    'workflow.configure',
    'workflow.view',
    'report.view_department',
    'dashboard.supervisor'
);

-- Administration Manager (role_id = 3)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 3, permission_id FROM permissions WHERE permission_key IN (
    'request.view_all',
    'request.approve',
    'inventory.view',
    'report.view_all',
    'workflow.view',
    'dashboard.admin_manager'
);

-- General Administration Manager (role_id = 4)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 4, permission_id FROM permissions WHERE permission_key IN (
    'request.view_all',
    'request.approve',
    'inventory.view',
    'report.view_all',
    'workflow.view',
    'user.create',
    'user.edit',
    'user.view',
    'dashboard.general_admin'
);

-- Stores Officer (role_id = 5)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 5, permission_id FROM permissions WHERE permission_key IN (
    'grv.create',
    'grv.view',
    'grv.approve',
    'issue.create',
    'issue.view',
    'inventory.view',
    'inventory.manage',
    'report.view_all',
    'dashboard.stores_officer'
);
