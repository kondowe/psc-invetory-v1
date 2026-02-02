<?php
/**
 * Email Configuration
 *
 * PHPMailer SMTP settings
 */

return [
    // SMTP Settings
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_port' => getenv('SMTP_PORT') ?: 587,
    'smtp_auth' => true,
    'smtp_username' => getenv('SMTP_USERNAME') ?: '',
    'smtp_password' => getenv('SMTP_PASSWORD') ?: '',
    'smtp_secure' => 'tls', // tls or ssl

    // Sender Information
    'from_email' => getenv('MAIL_FROM_ADDRESS') ?: 'noreply@inventorysystem.local',
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'Inventory Management System',

    // Email Settings
    'enabled' => getenv('MAIL_ENABLED') === 'true' ?: false,
    'charset' => 'UTF-8',
    'html' => true,

    // Queue Settings
    'use_queue' => true,
    'max_retries' => 3,
    'retry_delay' => 300, // 5 minutes in seconds

    // Templates Path
    'templates_path' => __DIR__ . '/../views/emails/',
];
