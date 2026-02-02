-- =================================================================
-- Seed Departments
-- =================================================================

USE inventory_system;

INSERT INTO departments (department_name, department_code, status) VALUES
('Administration', 'ADM', 'active'),
('Finance', 'FIN', 'active'),
('Human Resources', 'HR', 'active'),
('IT Services', 'IT', 'active'),
('Operations', 'OPS', 'active'),
('Procurement', 'PROC', 'active'),
('Logistics', 'LOG', 'active'),
('Maintenance', 'MAINT', 'active');
