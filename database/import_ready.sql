-- =================================================================
-- Inventory Management System - FULL DATABASE SETUP
-- Consolidated for phpMyAdmin (MySQL/MariaDB)
-- =================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. DATABASE CREATION
DROP DATABASE IF EXISTS inventory_system;
CREATE DATABASE inventory_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventory_system;

-- =================================================================
-- 2. SCHEMA DEFINITION
-- =================================================================

-- Roles table
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) UNIQUE NOT NULL,
    role_key VARCHAR(50) UNIQUE NOT NULL COMMENT 'requester, dept_supervisor, admin_mgr, general_admin_mgr, stores_officer',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Permissions table
CREATE TABLE permissions (
    permission_id INT PRIMARY KEY AUTO_INCREMENT,
    permission_key VARCHAR(100) UNIQUE NOT NULL,
    module VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT,
    INDEX idx_module (module)
) ENGINE=InnoDB;

-- Role permissions mapping
CREATE TABLE role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(permission_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Departments table
CREATE TABLE departments (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100) NOT NULL,
    department_code VARCHAR(20) UNIQUE NOT NULL,
    supervisor_user_id INT NULL,
    parent_department_id INT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_supervisor (supervisor_user_id),
    INDEX idx_parent (parent_department_id),
    INDEX idx_status (status),
    FOREIGN KEY (parent_department_id) REFERENCES departments(department_id)
) ENGINE=InnoDB;

-- Users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role_id INT NOT NULL,
    department_id INT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_role (role_id),
    INDEX idx_department (department_id),
    INDEX idx_status (status),
    INDEX idx_deleted (deleted_at),
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
) ENGINE=InnoDB;

-- Add foreign key for department supervisor after users table is created
ALTER TABLE departments
ADD FOREIGN KEY (supervisor_user_id) REFERENCES users(user_id);

-- Sessions table
CREATE TABLE sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_last_activity (last_activity),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Item categories
CREATE TABLE item_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    category_code VARCHAR(20) UNIQUE NOT NULL,
    parent_category_id INT NULL,
    is_fuel_category BOOLEAN DEFAULT FALSE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_parent (parent_category_id),
    INDEX idx_fuel (is_fuel_category),
    FOREIGN KEY (parent_category_id) REFERENCES item_categories(category_id)
) ENGINE=InnoDB;

-- Units of measure
CREATE TABLE units_of_measure (
    uom_id INT PRIMARY KEY AUTO_INCREMENT,
    uom_name VARCHAR(50) NOT NULL,
    uom_code VARCHAR(10) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Items master
CREATE TABLE items (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    sku VARCHAR(50) UNIQUE NOT NULL,
    item_name VARCHAR(200) NOT NULL,
    category_id INT NOT NULL,
    uom_id INT NOT NULL,
    description TEXT,
    minimum_stock_level DECIMAL(10,2) DEFAULT 0,
    reorder_level DECIMAL(10,2) DEFAULT 0,
    unit_cost DECIMAL(10,2),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_category (category_id),
    INDEX idx_sku (sku),
    INDEX idx_active (is_active),
    FULLTEXT idx_search (item_name, description),
    FOREIGN KEY (category_id) REFERENCES item_categories(category_id),
    FOREIGN KEY (uom_id) REFERENCES units_of_measure(uom_id)
) ENGINE=InnoDB;

-- Stores/locations
CREATE TABLE stores (
    store_id INT PRIMARY KEY AUTO_INCREMENT,
    store_name VARCHAR(100) NOT NULL,
    store_code VARCHAR(20) UNIQUE NOT NULL,
    location VARCHAR(200),
    store_type ENUM('main', 'branch', 'department') DEFAULT 'main',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_active (is_active)
) ENGINE=InnoDB;

-- Stock levels
CREATE TABLE stock_levels (
    stock_id INT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    store_id INT NOT NULL,
    quantity_on_hand DECIMAL(10,2) DEFAULT 0,
    quantity_reserved DECIMAL(10,2) DEFAULT 0 COMMENT 'For approved but not issued requests',
    quantity_available DECIMAL(10,2) GENERATED ALWAYS AS (quantity_on_hand - quantity_reserved) STORED,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_item_store (item_id, store_id),
    INDEX idx_item (item_id),
    INDEX idx_store (store_id),
    INDEX idx_available (quantity_available),
    FOREIGN KEY (item_id) REFERENCES items(item_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id)
) ENGINE=InnoDB;

-- Fuel types
CREATE TABLE fuel_types (
    fuel_type_id INT PRIMARY KEY AUTO_INCREMENT,
    fuel_type_name VARCHAR(50) NOT NULL,
    fuel_code VARCHAR(10) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Vehicles/equipment for fuel tracking
CREATE TABLE vehicles (
    vehicle_id INT PRIMARY KEY AUTO_INCREMENT,
    vehicle_number VARCHAR(50) UNIQUE NOT NULL,
    vehicle_type VARCHAR(50),
    fuel_type_id INT,
    department_id INT,
    status ENUM('active', 'inactive', 'maintenance') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_department (department_id),
    INDEX idx_status (status),
    FOREIGN KEY (fuel_type_id) REFERENCES fuel_types(fuel_type_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
) ENGINE=InnoDB;

-- Suppliers
CREATE TABLE suppliers (
    supplier_id INT PRIMARY KEY AUTO_INCREMENT,
    supplier_name VARCHAR(200) NOT NULL,
    supplier_code VARCHAR(50) UNIQUE NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(50),
    address TEXT,
    supplier_type ENUM('general', 'fuel_vendor', 'both') DEFAULT 'general',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_active (is_active),
    INDEX idx_type (supplier_type)
) ENGINE=InnoDB;

-- GRV header
CREATE TABLE goods_received_vouchers (
    grv_id INT PRIMARY KEY AUTO_INCREMENT,
    grv_number VARCHAR(50) UNIQUE NOT NULL,
    supplier_id INT NOT NULL,
    store_id INT NOT NULL,
    reference_number VARCHAR(100) COMMENT 'PO/Donation/Transfer reference',
    reference_type ENUM('purchase_order', 'donation', 'transfer', 'other') DEFAULT 'purchase_order',
    received_date DATE NOT NULL,
    received_by_user_id INT NOT NULL,
    approved_by_user_id INT NULL,
    approved_date TIMESTAMP NULL,
    status ENUM('draft', 'pending_approval', 'approved', 'cancelled') DEFAULT 'draft',
    total_value DECIMAL(12,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_grv_number (grv_number),
    INDEX idx_supplier (supplier_id),
    INDEX idx_status (status),
    INDEX idx_received_date (received_date),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id),
    FOREIGN KEY (received_by_user_id) REFERENCES users(user_id),
    FOREIGN KEY (approved_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- GRV line items
CREATE TABLE grv_items (
    grv_item_id INT PRIMARY KEY AUTO_INCREMENT,
    grv_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    total_cost DECIMAL(12,2) GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    batch_number VARCHAR(50),
    expiry_date DATE NULL,
    is_fuel_coupon BOOLEAN DEFAULT FALSE COMMENT 'Fuel-specific field',
    fuel_type_id INT NULL,
    coupon_serial_from VARCHAR(50),
    coupon_serial_to VARCHAR(50),
    coupon_count INT,
    notes TEXT,
    INDEX idx_grv (grv_id),
    INDEX idx_item (item_id),
    INDEX idx_fuel (is_fuel_coupon),
    FOREIGN KEY (grv_id) REFERENCES goods_received_vouchers(grv_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id),
    FOREIGN KEY (fuel_type_id) REFERENCES fuel_types(fuel_type_id)
) ENGINE=InnoDB;

-- Fuel coupons (individual coupon tracking)
CREATE TABLE fuel_coupons (
    coupon_id INT PRIMARY KEY AUTO_INCREMENT,
    coupon_serial_number VARCHAR(50) UNIQUE NOT NULL,
    item_id INT NOT NULL COMMENT 'Links to items table',
    fuel_type_id INT NOT NULL,
    coupon_value DECIMAL(10,2) NOT NULL COMMENT 'Monetary value or liters',
    value_type ENUM('amount', 'liters') DEFAULT 'amount',
    expiry_date DATE NULL,
    status ENUM('available', 'reserved', 'issued', 'expired', 'cancelled') DEFAULT 'available',
    grv_id INT NOT NULL COMMENT 'Traceability to GRV',
    issued_in_issue_voucher_id INT NULL,
    issued_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_serial (coupon_serial_number),
    INDEX idx_status (status),
    INDEX idx_fuel_type (fuel_type_id),
    INDEX idx_expiry (expiry_date),
    INDEX idx_grv (grv_id),
    FOREIGN KEY (item_id) REFERENCES items(item_id),
    FOREIGN KEY (fuel_type_id) REFERENCES fuel_types(fuel_type_id),
    FOREIGN KEY (grv_id) REFERENCES goods_received_vouchers(grv_id)
) ENGINE=InnoDB;

-- Workflow templates
CREATE TABLE workflow_templates (
    workflow_template_id INT PRIMARY KEY AUTO_INCREMENT,
    template_name VARCHAR(100) NOT NULL,
    template_type ENUM('global', 'department') DEFAULT 'department',
    department_id INT NULL COMMENT 'NULL for global templates',
    request_type ENUM('item', 'fuel', 'both') DEFAULT 'both',
    is_active BOOLEAN DEFAULT TRUE,
    created_by_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_department (department_id),
    INDEX idx_active (is_active),
    INDEX idx_type (template_type),
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- Workflow steps definition
CREATE TABLE workflow_steps (
    workflow_step_id INT PRIMARY KEY AUTO_INCREMENT,
    workflow_template_id INT NOT NULL,
    step_order INT NOT NULL,
    step_name VARCHAR(100) NOT NULL,
    approver_role_id INT NOT NULL,
    is_mandatory BOOLEAN DEFAULT TRUE,
    is_system_step BOOLEAN DEFAULT FALSE COMMENT 'TRUE for Admin Mgr, Gen Admin Mgr, Stores Officer',
    can_be_removed BOOLEAN DEFAULT TRUE COMMENT 'FALSE for system steps',
    condition_type ENUM('none', 'amount', 'category', 'priority') DEFAULT 'none',
    condition_value VARCHAR(100) COMMENT 'JSON or simple value for conditions',
    action_on_approval ENUM('proceed', 'complete') DEFAULT 'proceed',
    action_on_rejection ENUM('end', 'return_to_requester') DEFAULT 'end',
    sla_hours INT DEFAULT 24 COMMENT 'Service level agreement hours',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_template (workflow_template_id),
    INDEX idx_order (workflow_template_id, step_order),
    INDEX idx_role (approver_role_id),
    FOREIGN KEY (workflow_template_id) REFERENCES workflow_templates(workflow_template_id) ON DELETE CASCADE,
    FOREIGN KEY (approver_role_id) REFERENCES roles(role_id)
) ENGINE=InnoDB;

-- Workflow instances
CREATE TABLE workflow_instances (
    workflow_instance_id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    workflow_template_id INT NOT NULL,
    current_step_order INT DEFAULT 1,
    status ENUM('in_progress', 'completed', 'rejected', 'cancelled') DEFAULT 'in_progress',
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    INDEX idx_request (request_id),
    INDEX idx_template (workflow_template_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Workflow step instances
CREATE TABLE workflow_step_instances (
    workflow_step_instance_id INT PRIMARY KEY AUTO_INCREMENT,
    workflow_instance_id INT NOT NULL,
    workflow_step_id INT NOT NULL,
    step_order INT NOT NULL,
    assigned_role_id INT NOT NULL,
    assigned_user_id INT NULL COMMENT 'Specific user if assigned',
    status ENUM('pending', 'approved', 'rejected', 'skipped', 'returned') DEFAULT 'pending',
    action_taken_by_user_id INT NULL,
    action_date TIMESTAMP NULL,
    comments TEXT,
    sla_due_date TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_instance (workflow_instance_id),
    INDEX idx_step (workflow_step_id),
    INDEX idx_status (status),
    INDEX idx_assigned_user (assigned_user_id),
    FOREIGN KEY (workflow_instance_id) REFERENCES workflow_instances(workflow_instance_id) ON DELETE CASCADE,
    FOREIGN KEY (workflow_step_id) REFERENCES workflow_steps(workflow_step_id),
    FOREIGN KEY (assigned_role_id) REFERENCES roles(role_id),
    FOREIGN KEY (assigned_user_id) REFERENCES users(user_id),
    FOREIGN KEY (action_taken_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- Requests header
CREATE TABLE requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    request_number VARCHAR(50) UNIQUE NOT NULL,
    request_type ENUM('item', 'fuel') DEFAULT 'item',
    requester_user_id INT NOT NULL,
    department_id INT NOT NULL,
    purpose TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('draft', 'pending', 'approved', 'rejected', 'partially_issued', 'issued', 'closed', 'cancelled') DEFAULT 'draft',
    date_required DATE,
    current_workflow_step_id INT NULL,
    workflow_instance_id INT NULL,
    vehicle_id INT NULL COMMENT 'Fuel-specific field',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    submitted_at TIMESTAMP NULL,
    closed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    INDEX idx_request_number (request_number),
    INDEX idx_requester (requester_user_id),
    INDEX idx_department (department_id),
    INDEX idx_status (status),
    FOREIGN KEY (requester_user_id) REFERENCES users(user_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id)
) ENGINE=InnoDB;

-- Update workflow_instances FK
ALTER TABLE workflow_instances ADD CONSTRAINT fk_request_instance FOREIGN KEY (request_id) REFERENCES requests(request_id);

-- Request line items
CREATE TABLE request_items (
    request_item_id INT PRIMARY KEY AUTO_INCREMENT,
    request_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity_requested DECIMAL(10,2) NOT NULL,
    quantity_approved DECIMAL(10,2) DEFAULT 0,
    quantity_issued DECIMAL(10,2) DEFAULT 0,
    quantity_outstanding DECIMAL(10,2) GENERATED ALWAYS AS (quantity_approved - quantity_issued) STORED,
    unit_cost_estimate DECIMAL(10,2),
    justification TEXT,
    status ENUM('pending', 'approved', 'rejected', 'partially_issued', 'issued') DEFAULT 'pending',
    INDEX idx_request (request_id),
    INDEX idx_item (item_id),
    FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id)
) ENGINE=InnoDB;

-- Issue vouchers
CREATE TABLE issue_vouchers (
    issue_voucher_id INT PRIMARY KEY AUTO_INCREMENT,
    issue_voucher_number VARCHAR(50) UNIQUE NOT NULL,
    request_id INT NOT NULL,
    store_id INT NOT NULL,
    issued_by_user_id INT NOT NULL,
    received_by_user_id INT NULL,
    received_by_name VARCHAR(100),
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('draft', 'issued', 'cancelled') DEFAULT 'issued',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_voucher_number (issue_voucher_number),
    FOREIGN KEY (request_id) REFERENCES requests(request_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id),
    FOREIGN KEY (issued_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- Issue voucher line items
CREATE TABLE issue_voucher_items (
    issue_voucher_item_id INT PRIMARY KEY AUTO_INCREMENT,
    issue_voucher_id INT NOT NULL,
    request_item_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity_issued DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2),
    batch_number VARCHAR(50),
    notes TEXT,
    INDEX idx_voucher (issue_voucher_id),
    FOREIGN KEY (issue_voucher_id) REFERENCES issue_vouchers(issue_voucher_id) ON DELETE CASCADE,
    FOREIGN KEY (request_item_id) REFERENCES request_items(request_item_id),
    FOREIGN KEY (item_id) REFERENCES items(item_id)
) ENGINE=InnoDB;

-- Fuel coupon issuance mapping
CREATE TABLE fuel_coupon_issuance (
    fuel_issuance_id INT PRIMARY KEY AUTO_INCREMENT,
    issue_voucher_id INT NOT NULL,
    coupon_id INT NOT NULL,
    request_id INT NOT NULL,
    vehicle_id INT NULL,
    issued_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (issue_voucher_id) REFERENCES issue_vouchers(issue_voucher_id),
    FOREIGN KEY (coupon_id) REFERENCES fuel_coupons(coupon_id),
    FOREIGN KEY (request_id) REFERENCES requests(request_id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id)
) ENGINE=InnoDB;

-- Stock movement log (immutable)
CREATE TABLE stock_movements (
    movement_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    item_id INT NOT NULL,
    store_id INT NOT NULL,
    movement_type ENUM('grv_in', 'issue_out', 'adjustment', 'transfer_in', 'transfer_out') NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    reference_type VARCHAR(50),
    reference_id INT,
    balance_before DECIMAL(10,2),
    balance_after DECIMAL(10,2),
    performed_by_user_id INT NOT NULL,
    movement_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    INDEX idx_item (item_id),
    INDEX idx_store (store_id),
    FOREIGN KEY (item_id) REFERENCES items(item_id),
    FOREIGN KEY (store_id) REFERENCES stores(store_id),
    FOREIGN KEY (performed_by_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- Audit & Activity Logs
CREATE TABLE audit_logs (
    audit_log_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    table_name VARCHAR(100) NOT NULL,
    record_id INT NOT NULL,
    action ENUM('create', 'update', 'delete', 'approve', 'reject', 'issue', 'receive', 'cancel') NOT NULL,
    user_id INT NOT NULL,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
    activity_log_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    activity_type VARCHAR(100) NOT NULL,
    module VARCHAR(50),
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

-- System config & sequences
CREATE TABLE system_config (
    config_id INT PRIMARY KEY AUTO_INCREMENT,
    config_key VARCHAR(100) UNIQUE NOT NULL,
    config_value TEXT,
    config_type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    description TEXT,
    is_editable BOOLEAN DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE number_sequences (
    sequence_id INT PRIMARY KEY AUTO_INCREMENT,
    sequence_name VARCHAR(50) UNIQUE NOT NULL,
    prefix VARCHAR(10),
    current_number INT DEFAULT 0,
    padding INT DEFAULT 6,
    reset_frequency ENUM('never', 'daily', 'monthly', 'yearly') DEFAULT 'yearly',
    last_reset_date DATE
) ENGINE=InnoDB;

-- =================================================================
-- 3. SEED DATA
-- =================================================================

-- Roles
INSERT INTO roles (role_name, role_key, description) VALUES
('Requester', 'requester', 'Creates item requests, views own requests and statuses'),
('Department Supervisor', 'dept_supervisor', 'Approves department requests, configures department workflow'),
('Administration Manager', 'admin_mgr', 'Approves/rejects requests system-wide, views inventory availability'),
('General Administration Manager', 'general_admin_mgr', 'Final approval authority, view system-wide dashboards and reports'),
('Stores Officer', 'stores_officer', 'Manages inventory stock, creates GRVs, issues items against approved requests');

-- Permissions (Subset for initial setup)
INSERT INTO permissions (permission_key, module, action, description) VALUES
('request.create', 'request', 'create', 'Create new request'),
('request.view_own', 'request', 'view_own', 'View own requests'),
('request.approve', 'request', 'approve', 'Approve requests'),
('grv.create', 'grv', 'create', 'Create GRV'),
('inventory.view', 'inventory', 'view', 'View inventory'),
('inventory.manage', 'inventory', 'manage', 'Manage inventory');

-- Departments
INSERT INTO departments (department_name, department_code, status) VALUES
('Administration', 'ADM', 'active'),
('Finance', 'FIN', 'active'),
('IT Services', 'IT', 'active'),
('Operations', 'OPS', 'active');

-- Users (Password for all: Admin@123)
INSERT INTO users (username, email, password_hash, full_name, role_id, department_id, status) VALUES
('admin', 'admin@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'System Administrator', 4, 1, 'active'),
('stores', 'stores@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'Stores Officer', 5, 4, 'active'),
('supervisor_it', 'supervisor.it@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'IT Supervisor', 2, 3, 'active'),
('requester1', 'requester1@inventorysystem.local', '$2y$12$LQv3c1yycwJdC.73NdJrmuXPXPBRKx8KgjGHN4fXVuKmZxLFKnRiq', 'John Doe', 1, 3, 'active');

-- Inventory Master
INSERT INTO item_categories (category_name, category_code, is_fuel_category, description) VALUES
('Office Supplies', 'OFF', FALSE, 'Stationery and office consumables'),
('IT Equipment', 'IT', FALSE, 'Computers, laptops, peripherals'),
('Fuel Coupons', 'FUEL', TRUE, 'Fuel coupons for vehicles');

INSERT INTO units_of_measure (uom_name, uom_code, description) VALUES
('Piece', 'PCS', 'Individual items'),
('Liter', 'L', 'Liters'),
('Unit', 'UNIT', 'Generic unit');

INSERT INTO stores (store_name, store_code, location, store_type, is_active) VALUES
('Main Store', 'MAIN', 'Building A, Ground Floor', 'main', TRUE);

INSERT INTO fuel_types (fuel_type_name, fuel_code, description) VALUES
('Petrol', 'PETROL', 'Regular petrol'),
('Diesel', 'DIESEL', 'Diesel fuel');

INSERT INTO suppliers (supplier_name, supplier_code, contact_person, supplier_type, is_active) VALUES
('ABC Supplies', 'ABC-001', 'John Smith', 'general', TRUE),
('Fuel Station', 'FUEL-001', 'Bob Jones', 'fuel_vendor', TRUE);

-- System Config
INSERT INTO system_config (config_key, config_value, config_type, description) VALUES
('app_name', 'Inventory Management System', 'string', 'Application name'),
('enable_email_notifications', 'true', 'boolean', 'Enable email notifications');

-- Number Sequences
INSERT INTO number_sequences (sequence_name, prefix, current_number, padding, reset_frequency, last_reset_date) VALUES
('request', 'REQ-', 0, 6, 'yearly', CURDATE()),
('grv', 'GRV-', 0, 6, 'yearly', CURDATE()),
('issue', 'IV-', 0, 6, 'yearly', CURDATE());

-- Final Commit
COMMIT;
