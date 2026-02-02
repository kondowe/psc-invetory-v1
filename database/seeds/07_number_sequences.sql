-- =================================================================
-- Seed Number Sequences
-- =================================================================

USE inventory_system;

INSERT INTO number_sequences (sequence_name, prefix, current_number, padding, reset_frequency, last_reset_date) VALUES
('request', 'REQ-', 0, 6, 'yearly', CURDATE()),
('grv', 'GRV-', 0, 6, 'yearly', CURDATE()),
('issue_voucher', 'ISS-', 0, 6, 'yearly', CURDATE());
