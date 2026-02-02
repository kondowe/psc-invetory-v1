-- =================================================================
-- Seed System Configuration
-- =================================================================

USE inventory_system;

INSERT INTO system_config (config_key, config_value, config_type, description, is_editable) VALUES
('app_name', 'Inventory Management System', 'string', 'Application name', TRUE),
('app_timezone', 'UTC', 'string', 'Application timezone', TRUE),
('session_timeout', '3600', 'number', 'Session timeout in seconds (1 hour)', TRUE),
('pagination_limit', '20', 'number', 'Default pagination limit', TRUE),
('low_stock_threshold_percentage', '20', 'number', 'Low stock alert threshold percentage', TRUE),
('enable_email_notifications', 'true', 'boolean', 'Enable email notifications', TRUE),
('enable_inapp_notifications', 'true', 'boolean', 'Enable in-app notifications', TRUE),
('default_sla_hours', '24', 'number', 'Default SLA hours for approvals', TRUE),
('allow_partial_issuance', 'true', 'boolean', 'Allow partial item issuance', TRUE),
('max_login_attempts', '5', 'number', 'Maximum login attempts before lockout', TRUE),
('lockout_duration', '900', 'number', 'Account lockout duration in seconds (15 minutes)', TRUE),
('password_min_length', '8', 'number', 'Minimum password length', FALSE),
('require_password_complexity', 'true', 'boolean', 'Require password complexity (uppercase, lowercase, number)', FALSE),
('fuel_coupon_expiry_alert_days', '30', 'number', 'Days before expiry to alert for fuel coupons', TRUE),
('enable_workflow_sla_reminders', 'true', 'boolean', 'Enable SLA reminders for pending approvals', TRUE),
('sla_reminder_hours_before', '2', 'number', 'Hours before SLA due to send reminder', TRUE);
