-- =================================================================
-- Seed Users
-- Password for all users: Admin@123
-- Hash generated using PHP: password_hash('Admin@123', PASSWORD_BCRYPT, ['cost' => 12])
-- =================================================================

USE inventory_system;

INSERT INTO users (username, email, password_hash, full_name, role_id, department_id, status) VALUES
-- Admin (General Administration Manager)
('admin', 'admin@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'System Administrator', 4, 1, 'active'),

-- Administration Manager
('admin_mgr', 'admin.manager@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'Administration Manager', 3, 1, 'active'),

-- Stores Officer
('stores', 'stores@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'Stores Officer', 5, 7, 'active'),

-- Department Supervisors
('supervisor_it', 'supervisor.it@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'IT Supervisor', 2, 4, 'active'),
('supervisor_ops', 'supervisor.ops@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'Operations Supervisor', 2, 5, 'active'),

-- Requesters
('requester1', 'requester1@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'John Doe', 1, 4, 'active'),
('requester2', 'requester2@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'Jane Smith', 1, 5, 'active');

-- Update department supervisors
UPDATE departments SET supervisor_user_id = 4 WHERE department_id = 4; -- IT Supervisor
UPDATE departments SET supervisor_user_id = 5 WHERE department_id = 5; -- Operations Supervisor
