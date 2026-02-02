<?php
/**
 * System Constants
 *
 * Define system-wide constants
 */

// Prevent multiple inclusions
if (defined('CONSTANTS_LOADED')) {
    return;
}
define('CONSTANTS_LOADED', true);

// Request Status
define('REQUEST_STATUS_DRAFT', 'draft');
define('REQUEST_STATUS_PENDING', 'pending');
define('REQUEST_STATUS_APPROVED', 'approved');
define('REQUEST_STATUS_REJECTED', 'rejected');
define('REQUEST_STATUS_PARTIALLY_ISSUED', 'partially_issued');
define('REQUEST_STATUS_ISSUED', 'issued');
define('REQUEST_STATUS_CLOSED', 'closed');
define('REQUEST_STATUS_CANCELLED', 'cancelled');

// Request Types
define('REQUEST_TYPE_ITEM', 'item');
define('REQUEST_TYPE_FUEL', 'fuel');

// Request Priority
define('PRIORITY_LOW', 'low');
define('PRIORITY_MEDIUM', 'medium');
define('PRIORITY_HIGH', 'high');
define('PRIORITY_URGENT', 'urgent');

// User Status
define('USER_STATUS_ACTIVE', 'active');
define('USER_STATUS_INACTIVE', 'inactive');
define('USER_STATUS_SUSPENDED', 'suspended');

// GRV Status
define('GRV_STATUS_DRAFT', 'draft');
define('GRV_STATUS_PENDING_APPROVAL', 'pending_approval');
define('GRV_STATUS_APPROVED', 'approved');
define('GRV_STATUS_CANCELLED', 'cancelled');

// Issue Voucher Status
define('ISSUE_STATUS_DRAFT', 'draft');
define('ISSUE_STATUS_ISSUED', 'issued');
define('ISSUE_STATUS_CANCELLED', 'cancelled');

// Workflow Status
define('WORKFLOW_STATUS_IN_PROGRESS', 'in_progress');
define('WORKFLOW_STATUS_COMPLETED', 'completed');
define('WORKFLOW_STATUS_REJECTED', 'rejected');
define('WORKFLOW_STATUS_CANCELLED', 'cancelled');

// Workflow Step Status
define('STEP_STATUS_PENDING', 'pending');
define('STEP_STATUS_APPROVED', 'approved');
define('STEP_STATUS_REJECTED', 'rejected');
define('STEP_STATUS_SKIPPED', 'skipped');
define('STEP_STATUS_RETURNED', 'returned');

// Fuel Coupon Status
define('COUPON_STATUS_AVAILABLE', 'available');
define('COUPON_STATUS_RESERVED', 'reserved');
define('COUPON_STATUS_ISSUED', 'issued');
define('COUPON_STATUS_EXPIRED', 'expired');
define('COUPON_STATUS_CANCELLED', 'cancelled');

// Role Keys
define('ROLE_REQUESTER', 'requester');
define('ROLE_DEPT_SUPERVISOR', 'dept_supervisor');
define('ROLE_ADMIN_MGR', 'admin_mgr');
define('ROLE_GENERAL_ADMIN_MGR', 'general_admin_mgr');
define('ROLE_STORES_OFFICER', 'stores_officer');

// Stock Movement Types
define('MOVEMENT_GRV_IN', 'grv_in');
define('MOVEMENT_ISSUE_OUT', 'issue_out');
define('MOVEMENT_ADJUSTMENT', 'adjustment');
define('MOVEMENT_TRANSFER_IN', 'transfer_in');
define('MOVEMENT_TRANSFER_OUT', 'transfer_out');

// Notification Types
define('NOTIFICATION_APPROVAL_REQUIRED', 'approval_required');
define('NOTIFICATION_APPROVED', 'approved');
define('NOTIFICATION_REJECTED', 'rejected');
define('NOTIFICATION_ISSUED', 'issued');
define('NOTIFICATION_LOW_STOCK', 'low_stock');
define('NOTIFICATION_FUEL_EXPIRING', 'fuel_expiring');

// Audit Actions
define('AUDIT_CREATE', 'create');
define('AUDIT_UPDATE', 'update');
define('AUDIT_DELETE', 'delete');
define('AUDIT_APPROVE', 'approve');
define('AUDIT_REJECT', 'reject');
define('AUDIT_ISSUE', 'issue');
define('AUDIT_RECEIVE', 'receive');
define('AUDIT_CANCEL', 'cancel');

// Date Formats
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd M Y');
define('DISPLAY_DATETIME_FORMAT', 'd M Y H:i');

// Number Format
define('DECIMAL_PLACES', 2);
define('CURRENCY_SYMBOL', '$');

// Pagination
define('DEFAULT_PAGE_SIZE', 20);
define('MAX_PAGE_SIZE', 100);

// SLA
define('DEFAULT_SLA_HOURS', 24);
define('SLA_REMINDER_HOURS', 2);

// Session Keys
define('SESSION_USER_ID', 'user_id');
define('SESSION_USERNAME', 'username');
define('SESSION_ROLE_ID', 'role_id');
define('SESSION_ROLE_KEY', 'role_key');
define('SESSION_DEPARTMENT_ID', 'department_id');
define('SESSION_CSRF_TOKEN', 'csrf_token');
